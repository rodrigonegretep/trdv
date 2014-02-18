<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
?>
<script type="text/javascript">
//<![CDATA[

(function($) {

var Creator;

joms.status.Creator['photo'] =
{
	attachment: {},

	initialize: function()
	{
		Creator = this;

		Creator.Preview = Creator.View.find('.creator-preview');

		Creator.Form = Creator.View.find('.creator-form');

		Creator.Hint = Creator.View.find('.creator-hint');

		Creator.UploadContainer = Creator.View.find('.creator-upload-container');

		Creator.ToggleUpload = Creator.View.find('.creator-toggle-upload');

		Creator.ToggleUpload
			.hide()
			.click(function()
			{
				Creator.ToggleUpload.hide();
				Creator.newUpload();
			});

		Creator.newUpload();
	},

	focus: function()
	{
		Creator.Message.defaultValue("<?php echo JText::_('COM_COMMUNITY_STATUS_PHOTO_HINT'); ?>", 'hint');
	},

	newUpload: function()
	{

		if(joms.jQuery('#creator-upload').length)
		{
			joms.jQuery('.creator-upload-container.label-filetype a').remove();
			joms.jQuery('#creator-upload').remove();
		}
		var uploadClick = ''

		if(joms.jQuery.browser.msie)
		{
			var uploadClick = 'style="display:none"';
		}

		Creator.Upload =
			$('<div class="btn btn-primary btn-block" onclick="joms.jQuery(\'.type-photo input\').click();return false;"> <?php echo JText::_('COM_COMMUNITY_PHOTOS_SELECT_FILE'); ?></div><input id="creator-upload" name="filedata" type="file" size="35" '+uploadClick+'/>')
				.change(function()
					{
						Creator.add.apply(Creator);
					})
				.prependTo(Creator.UploadContainer);

		Creator.Form.show();
	},

	add: function()
	{
		Creator.LoadingIndicator.show();

		$.ajaxFileUpload({

			fileElementId: 'creator-upload',
			url: "{url}",

			dataType: 'json',

			success: function(photo)
			{
				if (photo.error)
				{
					Creator.Hint
						.html(photo.msg)
						.show();

					Creator.newUpload();

					return;
				}

				photo.preview = $(decodeURIComponent(photo.html));

				photo.preview
					.find('.creator-change-photo')
					.click(function()
					{
						Creator.remove();
					});

				Creator.Preview.append(photo.preview);

				Creator.attachment = photo;

				Creator.Form.hide();

				Creator.Hint.hide();

				Creator.Upload.remove();

				Creator.LoadingIndicator.hide();
			},

			error: function()
			{
				Creator.LoadingIndicator.hide();
			}

		});

	},

	remove: function()
	{
		Creator.attachment.preview.remove();

		Creator.attachment = {};

		Creator.newUpload();
	},

	submit: function()
	{
		if(Creator.attachment.id==undefined)
		{

			if(this.Message.hasClass('hint'))
			{
				Creator.Hint
				.html("<?php echo JText::_('COM_COMMUNITY_STATUS_PHOTOS_ERROR'); ?>")
				.show();
			}
		}

		return Creator.attachment.id!=undefined;
	},

	reset: function()
	{
		Creator.remove();
	},

	error: function(message)
	{
		if ($.trim(message).length>0)
		{
			Creator.Hint
				.html(message)
				.show();
		}
	},

	getAttachment: function()
	{
		var attachment = {
			type: 'photo',
			id: Creator.attachment.id
		}

		return attachment;
	}
};

})(joms.jQuery);

//]]>
</script>

<script type="text/javascript" src="<?php echo JURI::root(); ?>components/com_community/assets/ajaxfileupload.pack.js"></script>

<div class="creator-view type-photo">
	<div class="creator-hint"></div>

	<div class="creator-preview"></div>

	<div class="creator-form">
		<label class="creator-upload-container label-filetype" for="creator-upload"></label>
		<a class="creator-toggle-upload icon-add" href="javascript: void(0);"><?php echo JText::_('COM_COMMUNITY_ADD_PHOTO'); ?></a>
	</div>
</div>