<?php 
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
JHtml::_('behavior.framework', true);
?>
<script type="text/javascript">
//<![CDATA[

(function($) {

var Creator;

joms.status.Creator['event'] =
{
	initialize: function()
	{
		Creator = this;

		Creator.Form = Creator.View.find('.creator-form');

		Creator.Hint = Creator.View.find('.creator-hint');
	},

	focus: function()
	{
		this.Message.defaultValue("<?php echo JText::_('COM_COMMUNITY_STATUS_EVENT_HINT'); ?>", 'hint');

		Creator.Privacy.parent().hide();
	},

	blur: function()
	{
		Creator.Privacy.parent().show();
	},

	getAttachment: function()
	{
		var attachment = Creator.Form.serializeJSON();

		attachment.type = 'event';

		return attachment;
	},

	submit: function()
	{
		return true; // Let server-side do all validation work
	},

	reset: function()
	{
		Creator.Form[0].reset();
		toggleEventDateTime();
		toggleEventRepeat();
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
	success: function(message)
	{
		Creator.Hint
				.html(message)
				.show()
				.fadeOut(5000);
		Creator.reset();
	}
}

})(joms.jQuery);

//]]>
</script>

<div class="creator-view type-event">
	<div class="creator-hint alert"></div>

	<form class="creator-form align-inherit reset-gap">
		<ul class="cFormList cFormHorizontal createEvent cResetList">
			<li>
				<label for="title" class="form-label" title="<?php echo JText::_('COM_COMMUNITY_EVENTS_TITLE_LABEL'); ?>">
					<?php echo JText::_('COM_COMMUNITY_EVENTS_TITLE_LABEL'); ?>
				</label>
				<div class="form-field">
					<input name="title" id="title" type="text" class="required jomNameTips" title="<?php echo JText::_('COM_COMMUNITY_EVENTS_TITLE_TIPS'); ?>" value="" />
				</div>
			</li>
			<li>
				<label for="catid" class="form-label" title="<?php echo JText::_('COM_COMMUNITY_EVENTS_CATEGORY');?>">
					<?php echo JText::_('COM_COMMUNITY_EVENTS_CATEGORY');?>
				</label>
				<div class="form-field">
					<span class="jomNameTips" title="<?php echo JText::_('COM_COMMUNITY_EVENTS_CATEGORY_TIPS');?>"><?php echo $lists['categoryid']; ?></span>
				</div>
			</li>
			<li>
				<label for="location" class="form-label"><?php echo JText::_('COM_COMMUNITY_EVENTS_LOCATION'); ?></label>
				<div class="form-field">
					<input name="location" id="location" type="text" class="required jomNameTips" title="<?php echo JText::_('COM_COMMUNITY_EVENTS_LOCATION_TIPS'); ?>" value="" />
					<div class="small">
						<?php echo JText::_('COM_COMMUNITY_EVENTS_LOCATION_DESCRIPTION');?>
					</div>
				</div>
			</li>
			<li>
				<label class="form-label" title="<?php echo JText::_('COM_COMMUNITY_EVENTS_START_TIME'); ?>">
					<?php echo JText::_('COM_COMMUNITY_EVENTS_START_TIME'); ?>
				</label>
				<div class="form-field">
					<span class="jomNameTips" title="<?php echo JText::_('COM_COMMUNITY_EVENTS_START_TIME_TIPS'); ?>">
						<input type="text" name="startdate" id="startdate" style="width:auto;" size="10" class="span2 input-medium" readonly/>						
						<script>
							joms.jQuery("#startdate" ).datepicker
								({
									
									minDate: 0,
									changeMonth: true,
									changeYear: true,
									dateFormat: 'yy-mm-dd',
									onClose: function ( selectedDate ) {									
										var startDate = new Date(selectedDate);
										var endDate = new Date(joms.jQuery('#enddate').datepicker('getDate'));										
                                                                                /* set minDate as startDate */
                                                                                joms.jQuery('#enddate').datepicker('option','minDate',selectedDate); 
										if ( startDate > endDate ) {											
											joms.jQuery('#enddate').datepicker('setDate',selectedDate); /* reset endDate same as startDate */
										}
									}									
								}).datepicker('setDate', new Date());
						</script>

						<span id="start-time">
						<?php echo $startHourSelect; ?>:<?php  echo $startMinSelect; ?> <?php echo $startAmPmSelect;?>
						</span>
					</span>
				</div>
			</li>
			<li id="event-end-datetime">
				<label class="form-label" title="<?php echo JText::_('COM_COMMUNITY_EVENTS_END_TIME'); ?>">
					<?php echo JText::_('COM_COMMUNITY_EVENTS_END_TIME'); ?>
				</label>
				<div class="form-field">
					<span class="jomNameTips" title="<?php echo JText::_('COM_COMMUNITY_EVENTS_END_TIME_TIPS'); ?>">
						<input type="text" name="enddate" id="enddate" style="width:auto;" size="10" class="required input-medium" readonly/>												
						<script>
							joms.jQuery("#enddate" ).datepicker
								({
									minDate: 0,
									changeMonth: true,
									changeYear: true,
									dateFormat: 'yy-mm-dd',								
								}).datepicker('setDate', new Date());
						</script>
						<span id="end-time">
							<?php echo $endHourSelect; ?>:<?php echo $endMinSelect; ?> <?php echo $endAmPmSelect;?>
						</span>
					</span>
				</div>
			</li>
			<li>
				<div class="form-field">
					<span class="jomNameTips" title="<?php echo JText::_('COM_COMMUNITY_EVENTS_ALL_DAY_TIPS');?>" style="display: inline-block">
						<label class="label-checkbox" for="allday">
							<input id="allday" name="allday" type="checkbox" class="input checkbox" onclick="toggleEventDateTime();" value="1"/>
							<?php echo JText::_('COM_COMMUNITY_EVENTS_ALL_DAY'); ?>
						</label>
					</span>
					<script type="text/javascript">
					function toggleEventDateTime()
					{
						if( joms.jQuery('#allday').attr('checked') == 'checked' ){
							joms.jQuery('span#start-time, span#end-time').hide();
							joms.jQuery('#starttime-hour').val('12');
							joms.jQuery('#starttime-min').val('00');
							joms.jQuery('#starttime-ampm').val('am');
							joms.jQuery('#endtime-hour').val('11');
							joms.jQuery('#endtime-min').val('59');
							joms.jQuery('#endtime-ampm').val('pm');

						}else{
							joms.jQuery('span#start-time, span#end-time').show();
						}
					}

					function toggleEventRepeat()
					{
						if( joms.jQuery('#repeat').val() != '' )
						{
							joms.jQuery('#repeatendinput').show();
							joms.jQuery('input#repeatend').addClass('required');

							if (joms.jQuery('#repeat').val() == 'daily') {
									limitdesc = '<?php echo addslashes(sprintf(Jtext::_('COM_COMMUNITY_EVENTS_REPEAT_LIMIT_DESC'), COMMUNITY_EVENT_RECURRING_LIMIT_DAILY));?>';
							}else if (joms.jQuery('#repeat').val() == 'weekly') {
									limitdesc = '<?php echo addslashes(sprintf(Jtext::_('COM_COMMUNITY_EVENTS_REPEAT_LIMIT_DESC'), COMMUNITY_EVENT_RECURRING_LIMIT_WEEKLY));?>';
							}else if (joms.jQuery('#repeat').val() == 'monthly') {
									limitdesc = '<?php echo addslashes(sprintf(Jtext::_('COM_COMMUNITY_EVENTS_REPEAT_LIMIT_DESC'), COMMUNITY_EVENT_RECURRING_LIMIT_MONTHLY));?>';
							}
						}
						else
						{
								joms.jQuery('#repeatendinput').hide();
								joms.jQuery('input#repeatend').removeClass('required');
						}
					}
					</script>
				</div>
			</li>

			<?php if ($enableRepeat) { ?>
			<li>
				<label for="repeat" class="form-label" title="<?php echo JText::_('COM_COMMUNITY_EVENTS_REPEAT'); ?>"><?php echo JText::_('COM_COMMUNITY_EVENTS_REPEAT'); ?></label>
				<div class="form-field">
					<span class="jomNameTips" original-title="<?php echo JText::_('COM_COMMUNITY_EVENTS_REPEAT_TIPS'); ?>">
					<span id="repeatcontent"></span>
					<select name="repeat" id="repeat" onChange="toggleEventRepeat()" class="input select">
						<option value=""><?php echo JText::_('COM_COMMUNITY_EVENTS_REPEAT_NONE'); ?></option>
						<option value="daily"><?php echo JText::_('COM_COMMUNITY_EVENTS_REPEAT_DAILY'); ?></option>
						<option value="weekly"><?php echo JText::_('COM_COMMUNITY_EVENTS_REPEAT_WEEKLY'); ?></option>
						<option value="monthly"><?php echo JText::_('COM_COMMUNITY_EVENTS_REPEAT_MONTHLY'); ?></option>
					</select>
					</span>

					<span id="repeatendinput">
					<span class="label">&nbsp;&nbsp;*<?php echo JText::_('COM_COMMUNITY_EVENTS_REPEAT_END'); ?>&nbsp;</span>
					<span class="jomNameTips" title="<?php echo JText::_('COM_COMMUNITY_EVENTS_REPEAT_END_TIPS'); ?>">

                                            <input type="text" name="repeatend" id="repeatend" style="width:auto;" size="10" class="input-medium" readonly/>
                                                <script>
                                                        joms.jQuery("#repeatend" ).datepicker
                                                                ({
                                                                        minDate: 0,
                                                                        changeMonth: true,
                                                                        changeYear: true,
                                                                        dateFormat: 'yy-mm-dd',
                                                                        onClose: function ( selectedDate ) {
										var repeatEndDate = new Date(selectedDate);
                                                                                var startDate = new Date(joms.jQuery('#startdate').datepicker('getDate'));
										var endDate = new Date(joms.jQuery('#enddate').datepicker('getDate'));
										if ( repeatEndDate < startDate ) {
											joms.jQuery('#startdate').datepicker('setDate',selectedDate);
										}
										if ( repeatEndDate < endDate ) {
                                                                                    
											joms.jQuery('#enddate').datepicker('option','minDate',joms.jQuery('#startdate').datepicker('getDate')).datepicker('setDate',selectedDate);
										}
									}
                                                                });
                                                </script>
					</span>
					</span>
				</div>
			</li>
			<?php  } ?>
		</ul>
	</form>
</div>

<script type="text/javascript">
	joms.jQuery(document).ready(function(){
		toggleEventRepeat();
	});

</script>