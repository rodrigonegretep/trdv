<?php // no direct access
/*
* @version		$Id: (mod_qrcodes_local) default.php 2.5.1
* @copyright	Copyright (C) 2012 Dave Airey. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*/
defined('_JEXEC') or die('Restricted access');
?>
<div id="modQRCodesLocalHeaderText" style="<?php echo $headerStyle; ?>">
<?php
// DISPLAY TEXT
echo $headerText;
?>
</div>
<div id="modQRCodesLocalImage" style="text-align:<?php echo $picAlign; ?>;">
<?php
//DISPLAY IMAGE
echo $QRCODE;
?>
</div>
<div id="modQRCodesLocalFooterText" style="<?php echo $footerStyle; ?>">
<?php
//DISPLAY TEXT
echo $footerText;
?>
</div>
