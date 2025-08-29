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

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Database;
use Krabo\IsotopePackagingSlipBundle\Model\IsotopePackagingSlipModel;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
class CleanUpIsoPackagingSlipsCron {

  private int $timestamp;

  private int $batchSize;

  private LoggerInterface $logger;

  /**
   * @param ContaoFramework $contaoFramework
   * @param LoggerInterface|null $logger
   */
  public function __construct(ContaoFramework $contaoFramework, LoggerInterface $logger = null)
  {
    $contaoFramework->initialize();
    $cleanupAfterInYears = $GLOBALS['TL_CONFIG']['jvh_auto_cleaning_packaging_slips_years_ago'] ?? 3;
    $cleanupAfterInSeconds = $cleanupAfterInYears * 365 * 24 * 60 * 60;
    $this->timestamp = time() - $cleanupAfterInSeconds;
    $this->batchSize = $GLOBALS['TL_CONFIG']['jvh_auto_cleaning_batch_size'] ?? 100;
    $this->logger = $logger ?? new NullLogger();
  }

  public function __invoke(): void {
    if (empty($GLOBALS['TL_CONFIG']['jvh_auto_cleaning_enable_packaging_slips'])) {
      return;
    }
    $db = Database::getInstance();
    $packagingSlips = $db->prepare("SELECT `id` FROM `tl_isotope_packaging_slip` WHERE `member` = 0 AND `date` < ? ORDER BY `tstamp` ASC")
      ->limit($this->batchSize)
      ->execute([$this->timestamp]);
    while($packagingSlip = $packagingSlips->fetchAssoc()) {
      $objPackagingSlip = IsotopePackagingSlipModel::findByPk($packagingSlip['id']);
      if ($objPackagingSlip) {
        $db->prepare("UPDATE `tl_isotope_stock_booking` SET `packaging_slip_id` = 0 WHERE `packaging_slip_id` = ?")->execute([$objPackagingSlip->id]);
        $db->prepare("DELETE FROM `tl_isotope_packaging_slip_mail_message` WHERE `pid` = ?")->execute([$objPackagingSlip->id]);
        $db->prepare("DELETE FROM `tl_isotope_packaging_slip_product_collection` WHERE `pid` = ?")->execute([$objPackagingSlip->id]);
        $objPackagingSlip->delete();
        $this->logger->info('Delete packaging slip with' . $objPackagingSlip->id . ' and document number '.$objPackagingSlip->document_number);
      }
    }
  }

}