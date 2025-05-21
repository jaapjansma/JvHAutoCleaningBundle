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

$GLOBALS['TL_DCA']['tl_settings']['fields']['jvh_auto_cleaning_enable_member'] = [
  'inputType'               => 'checkbox',
  'eval'                    => array('tl_class'=>'w50 clr', 'submitOnChange' => true)
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['jvh_auto_cleaning_member_start_time'] = [
  'inputType'               => 'text',
  'eval'                    => array('tl_class'=>'w50 clr', 'rgxp'=>'time', 'mandatory' => true)
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['jvh_auto_cleaning_member_end_time'] = [
  'inputType'               => 'text',
  'eval'                    => array('tl_class'=>'w50 clr', 'rgxp'=>'time', 'mandatory' => true)
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['jvh_auto_cleaning_member_batch_size'] = [
  'inputType'               => 'text',
  'eval'                    => array('tl_class'=>'w50 clr', 'rgxp'=>'natural', 'mandatory' => true)
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['jvh_auto_cleaning_member_years_ago'] = [
  'inputType'               => 'text',
  'eval'                    => array('tl_class'=>'w50 clr', 'rgxp'=>'natural', 'mandatory' => true)
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['jvh_auto_cleaning_member_grace_period'] = [
  'inputType'               => 'text',
  'eval'                    => array('tl_class'=>'w50 clr', 'rgxp'=>'natural', 'mandatory' => true)
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['jvh_auto_cleaning_enable_orders'] = [
  'inputType'               => 'checkbox',
  'eval'                    => array('tl_class'=>'w50 clr', 'submitOnChange' => true)
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['jvh_auto_cleaning_orders_years_ago'] = [
  'inputType'               => 'text',
  'eval'                    => array('tl_class'=>'w50 clr', 'rgxp'=>'natural', 'mandatory' => true)
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['jvh_auto_cleaning_enable_packaging_slips'] = [
  'inputType'               => 'checkbox',
  'eval'                    => array('tl_class'=>'w50 clr', 'submitOnChange' => true)
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['jvh_auto_cleaning_packaging_slips_years_ago'] = [
  'inputType'               => 'text',
  'eval'                    => array('tl_class'=>'w50 clr', 'rgxp'=>'natural', 'mandatory' => true)
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['jvh_auto_cleaning_enable_compacting_of_bookings'] = [
  'inputType'               => 'checkbox',
  'eval'                    => array('tl_class'=>'w50 clr', 'submitOnChange' => true)
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['jvh_auto_cleaning_bookings_years_ago'] = [
  'inputType'               => 'text',
  'eval'                    => array('tl_class'=>'w50 clr', 'rgxp'=>'natural', 'mandatory' => true)
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['jvh_auto_cleaning_batch_size'] = [
  'inputType'               => 'text',
  'eval'                    => array('tl_class'=>'w50 clr', 'rgxp'=>'natural', 'mandatory' => true)
];

$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['jvh_auto_cleaning_enable_member'] = '';
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['jvh_auto_cleaning_enable_orders'] = '';
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['jvh_auto_cleaning_enable_packaging_slips'] = '';
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['jvh_auto_cleaning_enable_compacting_of_bookings'] = '';
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'jvh_auto_cleaning_enable_member';
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'jvh_auto_cleaning_enable_orders';
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'jvh_auto_cleaning_enable_packaging_slips';
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'jvh_auto_cleaning_enable_compacting_of_bookings';

\Contao\CoreBundle\DataContainer\PaletteManipulator::create()
  ->addLegend('jvh_auto_cleaning_legend')
  ->addField('jvh_auto_cleaning_enable_member')
  ->addField('jvh_auto_cleaning_enable_orders')
  ->addField('jvh_auto_cleaning_enable_packaging_slips')
  ->addField('jvh_auto_cleaning_enable_compacting_of_bookings')
  ->addField('jvh_auto_cleaning_batch_size')
  ->applyToPalette('default', 'tl_settings');
\Contao\CoreBundle\DataContainer\PaletteManipulator::create()
  ->addField('jvh_auto_cleaning_member_years_ago')
  ->addField('jvh_auto_cleaning_member_grace_period')
  ->addField('jvh_auto_cleaning_member_batch_size')
  ->addField('jvh_auto_cleaning_member_start_time')
  ->addField('jvh_auto_cleaning_member_end_time')
  ->applyToSubpalette('jvh_auto_cleaning_enable_member', 'tl_settings');
\Contao\CoreBundle\DataContainer\PaletteManipulator::create()
  ->addField('jvh_auto_cleaning_orders_years_ago')
  ->applyToSubpalette('jvh_auto_cleaning_enable_orders', 'tl_settings');
\Contao\CoreBundle\DataContainer\PaletteManipulator::create()
  ->addField('jvh_auto_cleaning_packaging_slips_years_ago')
  ->applyToSubpalette('jvh_auto_cleaning_enable_packaging_slips', 'tl_settings');
\Contao\CoreBundle\DataContainer\PaletteManipulator::create()
  ->addField('jvh_auto_cleaning_bookings_years_ago')
  ->applyToSubpalette('jvh_auto_cleaning_enable_compacting_of_bookings', 'tl_settings');