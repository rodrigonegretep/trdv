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

joms.status.Creator['message'] =
{
	focus: function()
	{
		this.Message.defaultValue("<?php echo JText::_('COM_COMMUNITY_STATUS_MESSAGE_HINT'); ?>", 'hint');
	},

	submit: function()
	{
		if(this.Message.val() !== "<?php echo JText::_('COM_COMMUNITY_STATUS_MESSAGE_HINT'); ?>")
		{
			return !this.Message.hasClass('hint');
		}
	},

	getAttachment: function()
	{
		return { type: 'message' };
	}
};

})(joms.jQuery);

//]]>
</script>

<div class="creator-view type-message">
<div class="creator-hint"></div>
</div>