<?php
/**
 * Copyright (C) 2024  Jaap Jansma (jaap.jansma@civicoop.org)
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

namespace JvH\JvHAutoCleaningBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Config\ConfigInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use JvH\JvHPuzzelDbBundle\JvHPuzzelDbBundle;

class Plugin implements BundlePluginInterface {
  /**
   * Gets a list of autoload configurations for this bundle.
   *
   * @return array<ConfigInterface>
   */
  public function getBundles(ParserInterface $parser)
  {
    return [
      BundleConfig::create('JvH\JvHAutoCleaningBundle\JvHAutoCleaningBundle')
        ->setLoadAfter(['isotope', 'isotope_rules']),
    ];
  }


}