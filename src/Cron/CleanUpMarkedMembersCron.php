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
use Jvh\JvHAutoCleaningBundle\Factory\FrontendUserAutoCleanFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CleanUpMarkedMembersCron {

  private int $batchSize;

  /**
   * @var FrontendUserAutoCleanFactory
   */
  private FrontendUserAutoCleanFactory $factory;

  /**
   * @param ContaoFramework $contaoFramework
   * @param ContainerInterface $container
   */
  public function __construct(ContaoFramework $contaoFramework, ContainerInterface $container)
  {
    $contaoFramework->initialize();
    $this->batchSize = $container->getParameter('jvh.auto_cleaning.cronjob_batch_size');
    $this->factory = $container->get('jvh.auto_cleaning.member');
  }

  public function __invoke(): void {
    $db = Database::getInstance();
    $members = $db->prepare("SELECT * FROM `tl_member` WHERE `remove_on` < ? AND `marked_for_removal` = 1 ORDER BY `lastLogin` ASC")
      ->limit($this->batchSize)
      ->execute([time()]);
    while($member = $members->fetchAssoc()) {
      $this->factory->cleanupMember($member['id']);
    }
  }

}