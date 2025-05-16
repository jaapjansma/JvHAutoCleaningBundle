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
use Krabo\IsotopeStockBundle\Model\BookingLineModel;
use Krabo\IsotopeStockBundle\Model\BookingModel;
use Symfony\Component\DependencyInjection\ContainerInterface;
class CleanUpIsoStockBookingsCron {

  private int $timestamp;

  private int $batchSize;

  /**
   * @param ContaoFramework $contaoFramework
   * @param ContainerInterface $container
   */
  public function __construct(ContaoFramework $contaoFramework, ContainerInterface $container)
  {
    $contaoFramework->initialize();
    $cleanupAfterInDays = $container->getParameter('jvh.auto_cleaning.iso_booking_cleanup_after');
    $cleanupAfterInSeconds = $cleanupAfterInDays * 24 * 60 * 60;
    $this->timestamp = time() - $cleanupAfterInSeconds;
    $this->batchSize = $container->getParameter('jvh.auto_cleaning.cronjob_batch_size');
  }

  public function __invoke(): void {
    $batchSize = $this->batchSize;
    $db = Database::getInstance();
    $years = $db->prepare("SELECT COUNT(*) as `total`, YEAR(FROM_UNIXTIME(`date`)) as `year`, `period_id`, `product_id` FROM `tl_isotope_stock_booking` WHERE `date` < ? AND `order_id` = 0 AND `packaging_slip_id` = 0 GROUP BY `year`, `product_id` HAVING `total` > 0 ORDER BY `year`, `product_id`, `period_id`")->execute([$this->timestamp]);
    $mergedBookings = [];
    $bookingIdsToDelete = [];
    while(($year = $years->fetchAssoc()) && $batchSize > 0) {
      $y = $year['year'];
      $period = $year['period_id'];
      $product_id = $year['product_id'];
      $bookings = $db->prepare("SELECT * FROM `tl_isotope_stock_booking` WHERE `date` < ? AND YEAR(FROM_UNIXTIME(`date`)) = ? AND `order_id` = 0 AND `packaging_slip_id` = 0 AND `period_id` = ? AND `product_id` = ? ORDER BY `tl_isotope_stock_booking`.`id`")
        ->limit($batchSize)
        ->execute([$this->timestamp, $y, $period, $product_id]);
      while($booking = $bookings->fetchAssoc()) {
        $lines = $db->prepare("SELECT * FROM `tl_isotope_stock_booking_line` WHERE `tl_isotope_stock_booking_line`.`pid` = ?")->execute([$booking['id']]);
        while ($line = $lines->fetchAssoc()) {
          $account = $line['account'];
          if (!isset($mergedBookings[$y][$product_id][$period][$account])) {
            $mergedBookings[$y][$product_id][$period][$account] = [
              'debit' => 0,
              'credit' => 0,
            ];
          }
          $mergedBookings[$y][$product_id][$period][$account]['debit'] += $line['debit'];
          $mergedBookings[$y][$product_id][$period][$account]['credit'] += $line['credit'];
        }
        $bookingIdsToDelete[] = $booking['id'];
        $batchSize--;
      }
    }
    foreach ($mergedBookings as $year => $booking) {
      foreach ($booking as $product_id => $periods) {
        foreach ($periods as $period => $accounts) {
          $b = new BookingModel();
          $b->description = 'Gearchiveerde boeking op ' . date('d-m-Y');
          $b->date = strtotime($year . '-01-01');
          $b->period_id = $period;
          $b->product_id = $product_id;
          $b->type_id = 0;
          $b->save();
          foreach ($accounts as $account_id => $account) {
            if ($account['debit'] > 0 || $account['credit'] > 0) {
              $bookingLine = new BookingLineModel();
              $bookingLine->debit = $account['debit'];
              $bookingLine->credit = $account['credit'];
              $bookingLine->account = $account_id;
              $bookingLine->pid = $b->id;
              $bookingLine->save();
            }
          }
        }
      }
    }
    foreach ($bookingIdsToDelete as $bookingId) {
      $db->prepare("DELETE FROM `tl_isotope_stock_jvh_booking_event` WHERE `booking_id` = ?")->execute([$bookingId]);
      $db->prepare("DELETE FROM `tl_isotope_stock_booking_event` WHERE `booking_id` = ?")->execute([$bookingId]);
      $db->prepare("DELETE FROM `tl_isotope_stock_booking_line` WHERE `pid` = ?")->execute([$bookingId]);
      $db->prepare("DELETE FROM `tl_isotope_stock_booking` WHERE `id` = ?")->execute([$bookingId]);
    }
  }

}