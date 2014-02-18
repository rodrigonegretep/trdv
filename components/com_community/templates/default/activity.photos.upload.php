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

<div class="js-stream-photos bottom-gap">
	<?php if($batchCount == 1 || $batchCount >= 5) { ?>
	<div class="row-fluid">
		<div class="span12">
			<a href="<?php echo $photos[0]->getPhotoLink();?>" class="cPhoto-Thumb">
				<img alt="<?php echo $this->escape($photos[0]->caption);?>" src="<?php echo $photos[0]->getImageURI();?>" />
			</a>
		</div>
	</div>
	<?php }

	if($batchCount != 1) { ?>
	<div class="row-fluid">
	<?php
		if($batchCount >= 5) {
			unset($photos[0]);
			$batchCountSlice = 5;
		} else {
			$batchCountSlice = $batchCount;
		}

		$photos = array_slice($photos, 0, $batchCountSlice);

		foreach($photos as $photo)
		{
	?>
		<div class="span3 top-gap">
			<a href="<?php echo $photo->getPhotoLink();?>" class="cPhoto-Thumb"><img alt="<?php echo $this->escape($photo->caption);?>" src="<?php echo $photo->getThumbURI();?>" /></a>
		</div>
	<?php
		} ?>
		</div>
	<?php
	}
	?>
</div>

<?php if ( $album->description ) { ?>
<div class="cStream-Quote">
	<?php echo JHTML::_('string.truncate', $album->description, $config->getInt('streamcontentlength') );?>
</div>
<?php } ?>

