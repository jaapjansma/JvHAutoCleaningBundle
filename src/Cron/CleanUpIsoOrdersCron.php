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
class CleanUpIsoOrdersCron {

  private int $timestamp;

  private int $batchSize;

  /**
   * @param ContaoFramework $contaoFramework
   * @param ContainerInterface $container
   */
  public function __construct(ContaoFramework $contaoFramework, ContainerInterface $container)
  {
    $contaoFramework->initialize();
    $cleanupAfterInYears = $GLOBALS['TL_CONFIG']['jvh_auto_cleaning_orders_years_ago'] ?? 3;
    $cleanupAfterInSeconds = $cleanupAfterInYears * 365 * 24 * 60 * 60;
    $this->timestamp = time() - $cleanupAfterInSeconds;
    $this->batchSize = $GLOBALS['TL_CONFIG']['jvh_auto_cleaning_batch_size'] ?? 100;
  }

  public function __invoke(): void {
    if (empty($GLOBALS['TL_CONFIG']['jvh_auto_cleaning_enable_orders'])) {
      return;
    }
    $db = Database::getInstance();
    $orders = $db->prepare("SELECT `id` FROM `tl_iso_product_collection` WHERE `type`  = 'order' AND `member` = 0 AND `tstamp` < ? ORDER BY `tstamp` ASC")
      ->limit($this->batchSize)
      ->execute([$this->timestamp]);
    while($order = $orders->fetchAssoc()) {
      $objOrder = ProductCollection::findByPk($order['id']);
      if ($objOrder) {
        $db->prepare("UPDATE `tl_isotope_packaging_slip_product_collection` SET `document_number` = '' WHERE `document_number` = ?")->execute([$objOrder->document_number]);
        $db->prepare("UPDATE `tl_isotope_stock_booking` SET `order_id` = 0 WHERE `order_id` = ?")->execute([$objOrder->id]);
        $objOrder->delete(TRUE);
      }
    }
  }

}