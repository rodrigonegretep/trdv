<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/

$usersModel			= CFactory::getModel( 'user' );
$now				= new JDate();
$date				= CTimeHelper::getDate();
$users				= $usersModel->getLatestMember(10);
$totalRegistered	= count($users);
$title				= JText::sprintf('COM_COMMUNITY_TOTAL_USERS_REGISTERED_THIS_MONTH_ACTIVITY_TITLE', $totalRegistered , $date->monthToString($now->format('%m')));

?>
<div class="cStream-Content">
	<div class="cStream-Headline">
		<b><?php echo JText::_('COM_COMMUNITY_LAST_10_USERS_REGISTERED'); ?></b>
		<!--div class="cStream-Status">
			<?php // echo $title; ?>
		</div-->
	</div>
	<?php if($totalRegistered > 0) { ?>
	<div class="cStream-Attachment">

		<div class="cSnippets Block">
		<?php foreach($users  as $user ) { ?>
		<?php
			$registerDate = $user->registerDate;
		?>
			<div class="cSnip clearfix">
				<a class="cSnip-Avatar cFloat-L" href="#">
					<img class="cAvatar" src="<?php echo $user->getThumbAvatar(); ?>" >
				</a>
				<div class="cSnip-Detail">
					<a class="cSnip-Title" href="<?php echo CUrlHelper::userLink($user->id); ?>">
						<?php echo $user->getDisplayName(); ?>
					</a>
					<div class="cSnip-Info small">
						<span>
							<?php echo JText::_('COM_COMMUNITY_MEMBER_SINCE'); ?>: <?php echo JHTML::_('date', $registerDate , JText::_('DATE_FORMAT_LC1')); ?>
						</span>
						<span>
							<a href="javascript:void(0)" onclick="joms.friends.connect('<?php echo $user->id;?>')"><span><?php echo JText::_('COM_COMMUNITY_PROFILE_ADD_AS_FRIEND'); ?></span></a>
						</span>
					</div>
				</div>
			</div>
		<?php } ?>
		</div>
	</div>
	<?php } ?>
	<?php $this->load('activities.actions'); ?>
</div>