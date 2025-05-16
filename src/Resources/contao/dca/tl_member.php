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

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_member']['fields']['marked_for_removal'] = [
  'filter'                  => true,
  'inputType'               => 'checkbox',
  'eval'                    => array('tl_class'=>'w50 wizard'),
  'sql'                     => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_member']['fields']['removal_reminder_send'] = [
  'inputType'               => 'text',
  'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
  'sql'                     => "varchar(10) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_member']['fields']['remove_on'] = [
  'inputType'               => 'text',
  'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
  'sql'                     => "varchar(10) NOT NULL default ''"
];

PaletteManipulator::create()
  ->addLegend('jvh_auto_cleanup')
  ->addField('marked_for_removal')
  ->addField('removal_reminder_send')
  ->addField('remove_on')
  ->applyToPalette('default', 'tl_member')
;