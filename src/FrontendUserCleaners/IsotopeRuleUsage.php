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

namespace JvH\JvHAutoCleaningBundle\FrontendUserCleaners;

use Doctrine\DBAL\Connection;

class IsotopeRuleUsage implements Cleaner {

  private Connection $connection;

  public function __construct(Connection $connection)
  {
    $this->connection = $connection;
  }
  public function cleanupMember(int $memberId): void
  {
    $this->connection->prepare("UPDATE `tl_iso_rule_usage` SET `member` = 0 WHERE `member` = ?")->executeQuery([$memberId]);
  }


}