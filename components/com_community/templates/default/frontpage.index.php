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
$moduleCount = count(JModuleHelper::getModules('js_side_frontpage')) + count(JModuleHelper::getModules('js_side_top'));
$class = ($moduleCount > 0) ? 'span8' : 'span12';
?>
<script type="text/javascript">joms.filters.bind();</script>
<!-- begin: #cFrontpageWrapper -->

	<?php
	/**
	 * if user logged in
	 * 		load frontpage.members.php
	 * else
	 * 		load frontpage.guest.php
	 */
	echo $header;
	?>

		<div class="row-fluid">
			<div class="<?php echo $class ;?>">
				<!-- begin: .cMain -->
				<div class="cMain">
				<?php
				/**
				 * ----------------------------------------------------------------------------------------------------------
				 * Activity stream section here
				 * ----------------------------------------------------------------------------------------------------------
				 */
				?>
				<?php if( $config->get('showactivitystream') == '1' || ($config->get('showactivitystream') == '2' && $my->id != 0 ) ) { ?>
				<!-- Recent Activities -->
				<h2 class="cResetH"><?php echo JText::_('COM_COMMUNITY_FRONTPAGE_RECENT_ACTIVITIES'); ?></h2>

				<?php $userstatus->render(); ?>

				<?php if ( $alreadyLogin == 1 ) : ?>
					<div id="activity-stream-nav" class="cFilter clearfull">
						<ul class="filters cFloat-R cResetList cFloatedList">
							<li class="filter <?php echo $config->get('frontpageactivitydefault') == 'all' ? ' active': '';?>">
								<a class="all-activity" href="javascript:void(0);"><?php echo JText::_('COM_COMMUNITY_VIEW_ALL') ?></a>
							</li>
							<li class="filter <?php echo $config->get('frontpageactivitydefault') == 'friends' ? ' active': '';?>">
								<a class="me-and-friends-activity" href="javascript:void(0);"><?php echo JText::_('COM_COMMUNITY_ME_AND_FRIENDS') ?></a>
							</li>
						</ul>
					</div>
				<?php endif; ?>

				<div class="cActivity cFrontpage-Activity" id="activity-stream-container">
					<div class="cActivity-LoadLatest joms-latest-activities-container">
						<a id="activity-update-click" class="btn btn-block" href="javascript:void(0);"></a>
					</div>

					<?php echo $userActivities; ?>
				</div>
			<?php } ?>
			</div>
			<!-- end: .cMain -->

			</div>
			<?php if($moduleCount > 0) { ?>
			<div class="span4">
				<div class="cSidebar">
							<?php $this->renderModules( 'js_side_top' ); ?>
							<?php $this->renderModules( 'js_side_frontpage' ); ?>

				</div>
				<!-- end: .cSidebar -->
			</div>
			<?php }?>
		</div>