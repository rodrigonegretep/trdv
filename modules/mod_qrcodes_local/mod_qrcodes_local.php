<?php
/**
* @version		$Id: mod_qrcodes_local.php 2.5.1
* @copyright	Copyright (C) 2012 Dave Airey. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the qr functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$headerStyle = trim( $params->get( 'header_style', '' ) );
$picAlign = trim( $params->get( 'pic_align', '' ) );
$footerStyle = trim( $params->get( 'footer_style', '' ) );
$folderPermissions = trim( $params->get( 'folder_permissions', '755' ) );
$filePermissions = trim( $params->get( 'file_permissions', '644' ) );
$CODE_ARRAY = modQRCodesLocalHelper::getPicture($params);
$headerText = $CODE_ARRAY[0];
$QRCODE = $CODE_ARRAY[1];
$footerText = $CODE_ARRAY[2];
require(JModuleHelper::getLayoutPath('mod_qrcodes_local'));
?>
