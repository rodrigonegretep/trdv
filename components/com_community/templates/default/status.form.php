<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
defined('_JEXEC') OR DIE();
?>


<script type="text/javascript">

(function($) {

var Status,
StatusUI,
CreatorViews,
CreatorMessage,
CreatorPrivacy,
CreatorLocation,
CreatorShareButton,
CreatorLoadingIndicator,
InitialCreator,
CurrentCreator,
ActivityTarget,
ActivityType,
ActivityContainer;

joms.extend({
status:
{
Creator : {},

submitting: false,

initialize: function(options)
{
Status = this;

StatusUI = $(options.element);

ActivityTarget = options.activityTarget;
ActivityType = options.activityType;
ActivityList = $(options.activityList);

if (StatusUI.length < 0)
return;

CreatorViews = StatusUI.find('.creator-views');
CreatorMessage = StatusUI.find('.creator-message');
CreatorPrivacy = StatusUI.find('[name=creator-privacy]');
CreatorLocation = StatusUI.find('.creator-location');
CreatorShareButton = StatusUI.find('.creator-share');
CreatorLoadingIndicator = StatusUI.find('.creator-loading');
CreatorUIs = StatusUI.find('.creator:not(.stub)');
CreatorTextBox = StatusUI.find('textarea.creator-message');
CreatorTextBoxHeight = CreatorTextBox.height();

//maximum char for status
var maxChar = '<?php echo $maxStatusChar;?>';
CreatorTextBox.keyup(function(){
var content = CreatorTextBox.val();
if(content.length > maxChar){
CreatorTextBox.val(content.substr(0 , maxChar));
}
});

$.each(CreatorUIs, function(i, CreatorUI)
{
Creator = Status.create(CreatorUI);

Creator.View
.appendTo(CreatorViews);

if (i==0) InitialCreator = Creator;
});

CreatorMessage
.stretchToFit()
.autogrow({});

CreatorShareButton.click(function()
{
Status.submit();
});

InitialCreator.display();

joms.privacy.init();
},

create: function(CreatorUI)
{
var CreatorUI = $(CreatorUI);
var CreatorView = CreatorUI.find('.creator-view');
var CreatorType = CreatorUI.attr('type') || CreatorUI[0].getAttribute('type');

// Expose creator to these references
Creator = {
Status: Status,
StatusUI: StatusUI,
UI: CreatorUI,
Type: CreatorType,
View: CreatorView,
Message: CreatorMessage,
Privacy: CreatorPrivacy,
Location: CreatorLocation,
ShareButton: CreatorShareButton,
LoadingIndicator: CreatorLoadingIndicator,
display: function()
{
Status.switchTo(CreatorType);
}
};

Creator = $.extend(Status.Creator[CreatorType], Creator);

try { Creator.initialize(); } catch (err) {};

CreatorUI.click(function()
{
Status.switchTo(CreatorType);
});

return Creator;
},

switchTo: function(CreatorType)
{
if (Status.submitting)
return;

try {

CurrentCreator.UI
.removeClass('active');

CurrentCreator.View
.removeClass('active');

StatusUI
.removeClass('on-' + CurrentCreator.Type);

CurrentCreator.blur();

} catch(err) {};

Creator = Status.Creator[CreatorType];

Creator.UI
.addClass('active');

Creator.View
.addClass('active');

StatusUI
.addClass('on-' + Creator.Type);

try { Creator.focus(); } catch (err) {};

CurrentCreator = Creator;

//special case if its Photos: disable the Privacy Select and use the Preference instead - added 5nov12
if(CreatorType == 'photo'){
joms.jQuery('.creator-privacy').hide();
joms.jQuery('[name="creator-privacy"]').attr('disabled','disabled');
}else{
joms.jQuery('.creator-privacy').show();
joms.jQuery('[name="creator-privacy"]').removeAttr('disabled');
}
},

reset: function()
{
CreatorMessage.val('').blur();

$.each(Status.Creator, function(i, Creator)
{
try { Creator.reset(); } catch (err) {};
});

InitialCreator.display();

CreatorTextBox.height(CreatorTextBoxHeight);
},

submit: function()
{
if (Status.submitting)
return;

if (!Creator.submit())
return;

var message    = CreatorMessage.hasClass('hint') ? '' : CreatorMessage.val();

attachment = (CurrentCreator.getAttachment) ? CurrentCreator.getAttachment() : {};

attachment.privacy = Creator.Privacy.find('option:selected').val();
attachment.target = ActivityTarget;
attachment.element= ActivityType;
attachment.filter = joms.jQuery('ul.cStreamList').data('filter');

Status.add(message, attachment);
},

add: function(message, attachment, callback)
{
message    = $.trim(message);
attachment = JSON.stringify(attachment);

joms.ajax.call('system,ajaxStreamAdd', [message, attachment],
{
beforeSend: function()
{
CreatorLoadingIndicator.show();

Status.submitting = true;
},
success: function()
{
if (typeof(callback)=='function')
callback.apply(this, arguments);

try { CurrentCreator.created.apply(CurrentCreator, arguments) } catch (err) {};

Status.reset();

//joms.activities.initMap();
},
error: function()
{
try { CurrentCreator.error.apply(CurrentCreator, arguments) } catch (err) {};
},
complete: function()
{
CreatorLoadingIndicator.hide();

Status.submitting = false;
}
});
}
}
});

$(document).ready(function()
{
joms.status.initialize({
element: '.community-status',
activityTarget: <?php echo $target; ?>,
activityType: '<?php echo $type; ?>',
activityList: '#activity-stream-container'

});
});

})(joms.jQuery);

</script>

<div class="cStreamComposer community-status clearfull">

<b class="cStream-Avatar cFloat-L status-author">
<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile')?>"><img src="<?php echo $my->getThumbAvatar(); ?>" class="cAvatar" /></a>
</b>

<div class="cStream-Content status-creator">
<ul class="creators cResetList cFloatedList clearfix">
<li class="creator stub"><strong><?php echo JText::_('COM_COMMUNITY_SHARE');?></strong></li>
<?php //dump($creators); ?>
<?php foreach($creators as $creator) { ?>
<?php if ( ( $creator->type == 'event'  && $my->canCreateEvents() ) || ($creator->type != 'event') ) { ?>
<li class="creator <?php echo $creator->class; ?>" type="<?php echo $creator->type; ?>">
<a class="creator-menu"><span><?php echo $creator->title; ?></span></a>
<?php echo $creator->html; ?>
</li>
<?php } ?>

<?php } ?>

<li class="creator gift" type="gift">
<a class="creator-menu"><span>Regalos</span></a>
<?php
$template= new CTemplate();
$html = $template->fetch('etrcommunity.gift');
 echo $html; 
?>

</ul>

<div class="creator-views">

</div>

<div class="creator-message-container">
<textarea class="creator-message no-box-shadow input-block-level"></textarea>
</div>

<div class="creator-actions clearfix">
<span class="creator-loading"></span>

<button class="creator-share btn btn-primary pull-right"><?php echo JText::_('COM_COMMUNITY_SHARE');?></button>
<?php if($type=='profile'):
$self = ($my->id == $target) ? true:false;
$access =  array( 'public' => true , 'members' => true , 'friends' => true , 'self' => $self );
$userParams= $my->getParams();
$profileAccess= $userParams->get('privacyProfileView');
$defaultSelect = 0;
switch( $profileAccess ){

case PRIVACY_PRIVATE:
//unset($access['friends']);
$defaultSelect = PRIVACY_PRIVATE;
break;
case PRIVACY_FRIENDS:
//unset($access['members']);
$defaultSelect = PRIVACY_FRIENDS;
break;
case PRIVACY_MEMBERS:
//unset($access['public']);
$defaultSelect = PRIVACY_MEMBERS;
break;
case 0:
break;
case 10:
break;

}
?>
<div class="creator-privacy">
<div class="js_PriCell"><?php echo CPrivacy::getHTML('creator-privacy', $defaultSelect, 'COMMUNITY_PRIVACY_BUTTON_SMALL', $access ); ?></div>
</div>
<?php endif; ?>
</div>
</div>

</div>