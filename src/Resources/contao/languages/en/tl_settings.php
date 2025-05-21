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

$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_legend'] = 'JvH Auto Cleaning';
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_enable_member'] = ['Enable auto cleaning of members', ''];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_member_years_ago'] = ['Clean after x years not logged in', 'In years'];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_member_grace_period'] = ['Grace period in which the user can login and keep his or her account', 'In days.'];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_member_batch_size'] = ['Size of list of members marked to be removed', ''];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_member_start_time'] = ['Send reminders from this time of the day', ''];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_member_end_time'] = ['Send reminders up to this time of the day', ''];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_enable_orders'] = ['Enable auto cleaning of orders', ''];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_orders_years_ago'] = ['Clean after x years', 'In years'];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_enable_packaging_slips'] = ['Enable auto cleaning of packaging slips', ''];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_packaging_slips_years_ago'] = ['Clean after x years', 'In years'];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_enable_compacting_of_bookings'] = ['Enable auto cleaning of bookings', 'Booking will be merged per product, per period and per year'];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_bookings_years_ago'] = ['Clean after x years', 'In years'];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_batch_size'] = ['Cron Job Batch Size', 'How many records will be cleaned in one run. The cron job runs every minute'];