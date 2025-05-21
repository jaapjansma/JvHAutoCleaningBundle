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

$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_legend'] = 'JvH Automatisch opschonen';
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_enable_member'] = ['Inschakelen automatisch opschonen van leden', ''];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_member_years_ago'] = ['Verwijderen na x jaren niet ingelogd', 'In jaren'];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_member_grace_period'] = ['Periode waarin de gebruiker nog kan inloggen', 'In dagen.'];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_member_batch_size'] = ['Aantal leden op de lijst om verwijderd te worden', ''];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_member_start_time'] = ['Stuur herinnering naar leden vanaf deze tijd', ''];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_member_end_time'] = ['Stuur herinnering naar leden tot deze tijd', ''];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_enable_orders'] = ['Inschakelen automatisch opschonen van bestellingen', ''];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_orders_years_ago'] = ['Verwijderen na x jaren', 'In jaren'];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_enable_packaging_slips'] = ['Inschakelen automatisch opschonen van pakbonnen', ''];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_packaging_slips_years_ago'] = ['Verwijderen na x jaren', 'In jaren'];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_enable_compacting_of_bookings'] = ['Inschakelen automatisch opschonen van boekingen', 'Boekingen worden verdund en samengevoegd, per product, per periode en per jaar.'];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_bookings_years_ago'] = ['Verwijderen na x jaren', 'In jaren'];
$GLOBALS['TL_LANG']['tl_settings']['jvh_auto_cleaning_batch_size'] = ['Cron Job Batch Grootte', 'Hoeveel records worden in een cron job run verwijderd. De cron job draait iedere minuut.'];