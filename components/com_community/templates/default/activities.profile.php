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

$user = CFactory::getUser($this->act->actor);
$target = CFactory::getUser($this->act->target);

if($act->app == 'profile.avatar.upload'){
	$this->load('activities.profile.avatar.upload');
	return;
}
?>

<a class="cStream-Avatar cFloat-L" href="<?php echo CUrlHelper::userLink($user->id); ?>">
	<img class="cAvatar" data-author="<?php echo $user->id; ?>" src="<?php echo $user->getThumbAvatar(); ?>">
</a>

<div class="cStream-Content">
	<div class="cStream-Headline" style="display:block">

		<a class="cStream-Author" href="<?php echo CUrlHelper::userLink($user->id); ?>"><?php echo $user->getDisplayName(); ?></a>

		<?php
		if($act->eventid)
		{
			$event = $this->event;
		?>
			<span class="cStream-Reference">
				 ➜ <a class="cStream-Reference" href="<?php echo CUrlHelper::eventLink($event->id); ?>"><?php echo $event->title; ?></a>
			</span>
		<?php
		}
		else if($act->groupid)
		{
			$group = $this->group;
		?>
			<span class="cStream-Reference">
				 ➜ <a class="cStream-Reference" href="<?php echo CUrlHelper::groupLink($group->id); ?>"><?php echo $group->name; ?></a>
			</span>
		<?php
		}
		?>

		<?php
		// Ad target if the post is on other's page
		if( !empty($act->target) && $act->target != $act->actor)
		{ ?>
			<span class="cStream-Reference">
			 ➜ <a class="cStream-Author" href="<?php echo CUrlHelper::userLink($target->id); ?>"><?php echo $target->getDisplayName(); ?></a>
			</span>
		<?php }
		?>
		</br>
	</div>

	<div class="cStream-Attachment">
		<?php
		echo CActivities::format($act->title);
		?>
	</div>

	<?php $this->load('activities.actions'); ?>
</div>