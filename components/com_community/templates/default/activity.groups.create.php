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
<ul class="cDetailList clrfix">
	<li class="avatarWrap">
		<a href="<?php echo CRoute::_( 'index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $group->id );?>">
			<img class="cAvatar cAvatar-Large" alt="<?php echo $this->escape($group->name );?>" src="<?php echo $group->getThumbAvatar();?>" />
		</a>
	</li>
	<li class="detailWrap">
		<strong><a href="<?php echo CRoute::_( 'index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $group->id );?>"><?php echo $group->name; ?></a></strong>
		<small>
			<?php echo JHTML::_('string.truncate',strip_tags($group->description) , $config->getInt('streamcontentlength'));?>
		</small>
	</li>
</ul>
<div class="clr"></div>