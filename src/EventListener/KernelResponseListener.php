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

namespace JvH\JvHAutoCleaningBundle\EventListener;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\FrontendUser;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Core\Security;

class KernelResponseListener {

  private Security $security;
  private ScopeMatcher $scopeMatcher;

  public function __construct(Security $security, ScopeMatcher $scopeMatcher)
  {
    $this->security = $security;
    $this->scopeMatcher = $scopeMatcher;
  }

  public function __invoke(ResponseEvent $event): void {
    if (!$this->scopeMatcher->isFrontendMainRequest($event)) {
      return;
    }

    $user = $this->security->getUser();
    if ($user instanceof FrontendUser && $user->marked_for_removal) {
      $user->marked_for_removal = '0';
      $user->remove_on = '';
      $user->save();
    }
  }

}