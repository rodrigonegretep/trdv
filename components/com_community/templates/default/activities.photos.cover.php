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
$user 	= CFactory::getUser($this->act->actor);
$params = new JRegistry($this->act->params);
$type 	= $params->get('type');
$extraMessage = '';
if(strtolower($type) !=='profile')
{
	$id = $type.'id';

	$cTable = JTable::getInstance(ucfirst($type),'CTable');
	$cTable->load($this->act->$id);

	if($type == 'group')
	{
		$extraMessage = ', <a href="'.$cTable->getLink().'">'.$cTable->name.'</a>';
	}
	if($type == 'event')
	{
		$extraMessage = ', <a href="'.CUrlHelper::eventLink($cTable->id).'">'.$cTable->title.'</a>';
	}

}

?>

<a class="cStream-Avatar cFloat-L" href="<?php echo CUrlHelper::userLink($user->id); ?>">
	<img class="cAvatar" data-author="<?php echo $user->id; ?>" src="<?php echo $user->getThumbAvatar(); ?>">
</a>

<div class="cStream-Content">
	<div class="cStream-Headline">
		<a class="cStream-Author" href="<?php echo CUrlHelper::userLink($user->id); ?>"><?php echo $user->getDisplayName(); ?></a>
		<?php echo JText::sprintf('COM_COMMUNITY_PHOTOS_COVER_UPLOAD',Jtext::_('COM_COMMUNITY_COVER_'.strtoupper($type))).$extraMessage; ?>
	</div>

	<div class="cStream-Attachment">
		<?php
		$avatarPath = $params->get('attachment');
		?>
		<img src="<?php echo $avatarPath ?>" class="img-half-width" />
	</div>

	<?php $this->load('activities.actions'); ?>
</div>