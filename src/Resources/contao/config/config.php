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

$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['tl_jvh_autoclean']['notify_account_expired']['recipients'] = array('recipient_email');
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['tl_jvh_autoclean']['notify_account_expired']['email_text'] = array(
  'member_*', // All Member fields
);
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['tl_jvh_autoclean']['notify_account_expired']['email_subject'] = &$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['tl_jvh_autoclean']['notify_account_expired']['email_text'];
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['tl_jvh_autoclean']['notify_account_expired']['email_html'] = &$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['tl_jvh_autoclean']['notify_account_expired']['email_text'];
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['tl_jvh_autoclean']['notify_account_expired']['email_replyTo'] = &$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['tl_jvh_autoclean']['notify_account_expired']['recipients'];
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['tl_jvh_autoclean']['notify_account_expired']['email_recipient_cc'] = &$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['tl_jvh_autoclean']['notify_account_expired']['recipients'];
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['tl_jvh_autoclean']['notify_account_expired']['email_recipient_bcc'] = &$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['tl_jvh_autoclean']['notify_account_expired']['recipients'];