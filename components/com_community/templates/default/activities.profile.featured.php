
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

$param = new CParameter($this->act->params);

$user = CFactory::getUser($param->get('userid'));

?>

<a href="<?php echo CRoute::_($param->get('owner_url'))?>" class="cStream-Avatar cFloat-L">
	<img src="<?php echo $user->getThumbAvatar()?>" data-author="<?php echo $param->get('userid')?>" class="cAvatar">
</a>
<div class="cStream-Content">
	<div class="cStream-Headline">
		<b>
			<?php echo JText::sprintf('COM_COMMUNITY_MEMBER_IS_FEATURED','<a href="'.CRoute::_($param->get('owner_url')).'" class="cStream-Author">'.$user->getDisplayName().'</a>'); ?>

		</b>
	</div>
	<?php
		// Tell actions that this is a featured stream
		$this->act->isFeatured = true;
		$this->load('activities.actions');
	?>
</div>