<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/

	$model		= CFactory::getModel( 'photos');
	$photos		= $model->getPopularPhotos( 9 , 0 );

	$tmpl   =	new CTemplate();
?>
<div class="cStream-Content">
	<div class="cStream-Headline">
		<b><?php echo JText::_('COM_COMMUNITY_ACTIVITIES_TOP_PHOTOS'); ?></b>
	</div>
	<div class="cStream-Attachment">
		<div class="cStream-Photo top-photos js-col-layout">
		<?php
		foreach( $photos as $photo )
		{
		?>
			<div class="js-col4">
				<a href="<?php echo $photo->getPhotoLink();?>" class="cSnip-Photo" title="<?php echo $this->escape($photo->caption);?>">
					<?php 
					$user = CFactory::getUser($photo->creator); 
					?>
					<img class="cPhoto-Thumb" alt="<?php echo $this->escape($photo->caption);?>" src="<?php echo $photo->getThumbURI();?>" />
					<span>
						<i><?php echo JText::sprintf('COM_COMMUNITY_PHOTOS_UPLOADED_BY' , $user->getDisplayName() );?></i>
					</span>
				</a>
			</div>
		<?php
		}
		?>
		</div>
	</div>
	<?php $this->load('activities.actions'); ?>
</div>