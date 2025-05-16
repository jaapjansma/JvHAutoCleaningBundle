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

namespace JvH\JvHAutoCleaningBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AddFrontendUserCleaners implements CompilerPassInterface {
  /**
   * You can modify the container here before it is dumped to PHP code.
   */
  public function process(ContainerBuilder $container)
  {
    if (!$container->has('jvh.auto_cleaning.member')) {
      return;
    }

    $serviceIds = $container->findTaggedServiceIds('jvh.auto_cleaning.member.task');
    $definition = $container->findDefinition('jvh.auto_cleaning.member');

    foreach ($serviceIds as $serviceId => $tags) {
      foreach ($tags as $attributes) {
        $priority = 0;
        if (isset($attributes['priority'])) {
          $priority = $attributes['priority'];
        }

        $definition->addMethodCall(
          'addCleaner',
          [
            new Reference($serviceId), $priority,
          ]
        );
      }
    }
  }


}