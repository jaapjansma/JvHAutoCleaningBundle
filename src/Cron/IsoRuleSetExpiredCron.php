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
use Isotope\Model\ProductCollection;
use Symfony\Component\DependencyInjection\ContainerInterface;
class IsoRuleSetExpiredCron {

  /**
   * @param ContaoFramework $contaoFramework
   */
  public function __construct(ContaoFramework $contaoFramework)
  {
    $contaoFramework->initialize();
  }

  public function __invoke(): void {
    $db = Database::getInstance();
    $db->prepare("UPDATE `tl_iso_rule` SET `is_expired` = '1' WHERE NOT `is_expired` = '1' AND NOT `endDate` != '' AND `endDate` < ?")->execute([strtotime('+1 day')]);
    $db->prepare("UPDATE `tl_iso_rule` SET `enabled` = '0' WHERE `enabled` = ''")->execute();
  }

}