<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
defined('_JEXEC') or die();
?>
<div class="<?php echo $classAttribute;?>">
	<select name="<?php echo $nameAttribute;?>" class="js_PrivacySelect js_PriDefault">
		<?php if( isset( $access[ 'public'] ) && $access['public'] === true ){ ?>
		<option class="js_PriOption js_Pri-0" value="0"<?php echo $selectedAccess == 0 ? ' selected="selected"' : '';?>><?php echo JText::_('COM_COMMUNITY_PRIVACY_PUBLIC');?></option>
		<?php } ?>
		
		<?php if( isset( $access[ 'members'] ) && $access['members'] === true ){ ?>
		<option class="js_PriOption js_Pri-20" value="20"<?php echo $selectedAccess == 20 ? ' selected="selected"' : '';?>><?php echo JText::_('COM_COMMUNITY_PRIVACY_SITE_MEMBERS');?></option>
		<?php } ?>
		
		<?php if( isset( $access[ 'friends'] ) && $access['friends'] === true ){ ?>
		<option class="js_PriOption js_Pri-30" value="30"<?php echo $selectedAccess == 30 ? ' selected="selected"' : '';?>><?php echo JText::_('COM_COMMUNITY_PRIVACY_FRIENDS');?></option>
		<?php } ?>
		
		<?php if( isset( $access[ 'self'] ) && $access['self'] === true ){ ?>
		<option class="js_PriOption js_Pri-40" value="40"<?php echo $selectedAccess == 40 ? ' selected="selected"' : '';?>><?php echo JText::_('COM_COMMUNITY_PRIVACY_ME');?></option>
		<?php } ?>
	</select>
	<div class="clear"></div>
</div>
