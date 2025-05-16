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

namespace JvH\JvHAutoCleaningBundle\EventListener\DataContainer;

use Contao\DataContainer;
use Contao\Image;

class MemberLabelCallback {

  public function __invoke(array $row, string $label, DataContainer $dc, array $labels): array
  {
    $key = 0;
    //if ($dc->id) {
    if (!empty($row['marked_for_removal'])) {
      $image = 'news';
      $labels[0] = sprintf(
        '<div class="list_icon_new" style="background-image:url(\'%s\')" title="Heeft lange tijd niet ingelogd en wordt verwijderd">&nbsp;</div>',
        Image::getPath($image),
      );
    }
    return $labels;
  }

}