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

$param = new CParameter($this->act->params);
$actors = $param->get('actors');

$user 		= CFactory::getUser($this->act->actor);
$users 		= explode(',', $actors);
$userCount 	= count($users);
switch($this->act->app){
	case 'profile.like':
		$cid 		= CFactory::getUser($this->act->cid);
		$urlLink 	= CUrlHelper::userLink($cid->id);
		$name 		= $cid->getDisplayName();
		$element	= 'COM_COMMUNITY_STREAM_LIKES_ELEMENT_PROFILE';
	break;
	case 'groups.like':
		$cid = JTable::getInstance('Group', 'CTable');
		$cid->load($this->act->groupid);
		$urlLink 	= $cid->getLink();
		$name 		= $cid->name;
		$element	= 'COM_COMMUNITY_STREAM_LIKES_ELEMENT_GROUP';
	break;
	case 'events.like':
		$cid = JTable::getInstance('Event','CTable');
		$cid->load($this->act->eventid);
		$urlLink 	= $cid->getLink();
		$name 		= $cid->title;
		$element	= 'COM_COMMUNITY_STREAM_LIKES_ELEMENT_EVENT';
	break;
	case 'photo.like':
		$cid = JTable::getInstance('Photo','CTable');
		$cid->load($this->act->cid);

		$urlLink 	= $cid->getPhotoLink();
		$name 		= $cid->caption;
		$element	= 'COM_COMMUNITY_STREAM_LIKES_ELEMENT_PHOTO';
	break;
	case 'videos.like':
		$cid = JTable::getInstance('Video','CTable');
		$cid->load($this->act->cid);

		$urlLink 	= $cid->getViewURI();
		$name 		= $cid->getTitle();
		$element	= 'COM_COMMUNITY_STREAM_LIKES_ELEMENT_VIDEO';
	break;
	case 'album.like':
		$cid = JTable::getInstance('Album','CTable');
		$cid->load($this->act->cid);

		$urlLink 	= $cid->getURI();
		$name 		= $cid->name;
		$element	= 'COM_COMMUNITY_STREAM_LIKES_ELEMENT_ALBUM';
	break;
}

$slice 		= 2;

if($userCount > 2)
{
	$slice = 1;
}

$users = array_slice($users,0,$slice);

$actorsHTML = array();
?>
<div class="cStream-Content">
	<i class="cStream-Icon com-icon-groups"></i>
	<?php foreach($users as $actor) {
		$user = CFactory::getUser($actor);
		$actorsHTML[] = '<a class="cStream-Author" href="'. CUrlHelper::userLink($user->id).'">'. $user->getDisplayName().'</a>';
	}

	$others = '';

	if($userCount > 2)
	{
		$others = JText::sprintf('COM_COMMUNITY_STREAM_OTHERS_JOIN_GROUP' , $userCount-1);
	}

	$jtext =($userCount>1) ? 'COM_COMMUNITY_STREAM_LIKES_PLURAL' : 'COM_COMMUNITY_STREAM_LIKES_SINGULAR';

	echo implode( ' '. JText::_('COM_COMMUNITY_AND') . ' ' , $actorsHTML).$others.JText::sprintf($jtext,$urlLink,$name,JText::_($element)) ;

	?>
</div>