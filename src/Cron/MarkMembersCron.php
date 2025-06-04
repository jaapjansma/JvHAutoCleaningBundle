<?php
/**
 * Copyright (C) 2025  Jaap Jansma (jaap.jansma@civicoop.org)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace JvH\JvHAutoCleaningBundle\Cron;

use Contao\ArrayUtil;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Database;
use Contao\MemberModel;
use Contao\StringUtil;
use Contao\System;
use NotificationCenter\Model\Notification;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MarkMembersCron {
  private int $max;

  private int $lastLoginTimestamp;

  private int $gracePeriod;

  private int $batchSize;

  private int $lastLoginThresholdInYears;

  private int $gracePeriodInDays;

  private ContaoFramework $framework;

  private LoggerInterface $logger;

  /**
   * @param ContaoFramework $contaoFramework
   * @param LoggerInterface|null $logger
   */
  public function __construct(ContaoFramework $contaoFramework, LoggerInterface $logger = null) {
    $contaoFramework->initialize();
    $this->logger = $logger ?? new NullLogger();
    $this->framework = $contaoFramework;
    $this->max = $GLOBALS['TL_CONFIG']['jvh_auto_cleaning_member_batch_size'] ?? 100;
    $this->lastLoginThresholdInYears = $GLOBALS['TL_CONFIG']['jvh_auto_cleaning_member_years_ago'] ?? 3;
    $lastLoginThresholdInSeconds = $this->lastLoginThresholdInYears * 365 * 24 * 60 * 60;
    $this->lastLoginTimestamp = time() - $lastLoginThresholdInSeconds;
    $this->gracePeriodInDays = $GLOBALS['TL_CONFIG']['jvh_auto_cleaning_member_grace_period'] ?? 14;
    $this->gracePeriod = $this->gracePeriodInDays * 24 * 60 * 60;
    $this->batchSize = $GLOBALS['TL_CONFIG']['jvh_auto_cleaning_batch_size'] ?? 100;
  }

  public function __invoke(): void
  {
    $now = new \DateTime();
    $today = new \DateTime();
    $today->setTime(0,0);
    // Calculate seconds since start of the day.
    // Also substruct an hour because the start and end time have an hour difference.
    $seconds = $now->getTimestamp() - $today->getTimestamp() - 3600;
    if ($seconds < 0) {
      $seconds = 0;
    }
    if (empty($GLOBALS['TL_CONFIG']['jvh_auto_cleaning_enable_member'])) {
      return;
    }
    if (!empty($GLOBALS['TL_CONFIG']['jvh_auto_cleaning_member_start_time']) && $GLOBALS['TL_CONFIG']['jvh_auto_cleaning_member_start_time'] > $seconds) {
      return;
    }
    if (!empty($GLOBALS['TL_CONFIG']['jvh_auto_cleaning_member_end_time']) && $GLOBALS['TL_CONFIG']['jvh_auto_cleaning_member_end_time'] < $seconds) {
      return;
    }
    $currentMarkCount = $this->getCurrentMarkCount();
    $limit = $this->max - $currentMarkCount;
    $removeOn = time() + $this->gracePeriod;
    if ($limit > 0) {
      if ($limit > $this->batchSize) {
        $limit = $this->batchSize;
      }
      System::loadLanguageFile('tl_member');
      $objNotificationCollection = Notification::findByType('notify_account_expired');

      $db = Database::getInstance();
      $members = $db->prepare("SELECT * FROM `tl_member` WHERE `lastLogin` < ? AND (`marked_for_removal` IS NULL OR `marked_for_removal` = 0) ORDER BY `lastLogin` ASC, `dateAdded` ASC, `id` ASC")
        ->limit($limit)
        ->execute([$this->lastLoginTimestamp]);
      while($member = $members->fetchAssoc()) {
        // Send e-mail to the user
        if (null !== $objNotificationCollection) {
          $this->notifyMember($member['id'], $objNotificationCollection);
        }
        $db->prepare("UPDATE `tl_member` SET `marked_for_removal` = '1', `removal_reminder_send` = UNIX_TIMESTAMP(), `remove_on` = ? WHERE `id` = ?")->execute([$removeOn, $member['id']]);
        $this->logger->info('Marked member with id ' . $member['id'] . ' and username ' .  $member['username'] . ' for removal.');
      }
    }
  }

  protected function getCurrentMarkCount(): int {
    $db = Database::getInstance();
    $result = $db->query("SELECT COUNT(*) FROM `tl_member` WHERE (`marked_for_removal` IS NOT NULL AND `marked_for_removal` != 0)");
    return (int) $result->first()->fetchField();
  }

  protected function notifyMember(int $memberId, $notifications): void {
    $language = $GLOBALS['TL_LANGUAGE'];
    $emailLanguage = null;
    $objMember = MemberModel::findByPk($memberId);
    if ($objMember && !empty($objMember->language)) {
      $emailLanguage = $objMember->language;
    }
    if ($emailLanguage === null) {
      $emailLanguage = 'nl_NL';
    }
    $GLOBALS['TL_LANGUAGE'] = $emailLanguage;
    $arrTokens = [];
    foreach ($objMember->row() as $k => $v) {
      $arrTokens = $this->flatten($v, 'member_' . $k, $arrTokens);
    }
    $arrTokens['recipient_email'] = $objMember->email;
    $arrTokens['grace_period'] = $this->gracePeriod;
    $arrTokens['years_ago'] = $this->lastLoginThresholdInYears;
    while ($notifications->next()) {
      $objNotification = $notifications->current();
      $objNotification->send($arrTokens, $GLOBALS['TL_LANGUAGE']);
    }
    $notifications->reset();
    $GLOBALS['TL_LANGUAGE'] = $language;
  }

  /**
   * Flatten input data, Simple Tokens can't handle arrays.
   *
   * @param mixed $varValue
   * @param string $strKey
   * @param array $arrData
   * @return array
   */
  private function flatten(mixed $varValue, string $strKey, array $arrData): array
  {
    /** @var StringUtil $stringUtilAdapter */
    $stringUtilAdapter = $this->framework->getAdapter(StringUtil::class);

    if (!empty($varValue) && is_string($varValue) && strlen($varValue) > 3 && is_array($stringUtilAdapter->deserialize($varValue))) {
      $varValue = $stringUtilAdapter->deserialize($varValue);
    }

    if (is_object($varValue)) {
      return $arrData;
    }

    if (!is_array($varValue)) {
      $arrData[$strKey] = $varValue;

      return $arrData;
    }

    $blnAssoc = ArrayUtil::isAssoc($varValue);

    $arrValues = [];
    foreach ($varValue as $k => $v) {
      if ($blnAssoc || is_array($v)) {
        $arrData = $this->flatten($v, $strKey . '_' . $k, $arrData);
      } else {
        $arrData[$strKey.'_'.$v] = '1';
        $arrValues[] = $v;
      }
    }

    $arrData[$strKey] = implode(', ', $arrValues);

    return $arrData;
  }

}