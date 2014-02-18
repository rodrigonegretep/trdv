<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/

$user = CFactory::getUser($this->act->actor);
$config = CFactory::getConfig();

// Setup group table
$group = $this->group;

// Setup Announcement Table
$bulletin = JTable::getInstance('Bulletin', 'CTable');
$bulletin->load($act->cid);

// Load params
$param = new JRegistry($this->act->params);
$action = $param->get('action');
$actors = $param->get('actors');
$this->set('actors', $actors);

$stream = new stdClass();
$stream->actor = $user;
$stream->target = null;
$stream->headline = '<a class="cStream-Author" href="' .CUrlHelper::userLink($user->id).'">'.$user->getDisplayName().'</a>'
		. JText::sprintf('COM_COMMUNITY_GROUPS_NEW_GROUP_NEWS' , CRoute::_('index.php?option=com_community&view=groups&task=viewbulletin&groupid=' . $group->id . '&bulletinid=' . $bulletin->id ), $bulletin->title );
$stream->message = "";
$stream->group = $group;
$stream->attachments = array();

$attachment = new stdClass();
$attachment->type = 'quote';
$attachment->message = $this->escape(JHTML::_('string.truncate', $bulletin->message, $config->getInt('streamcontentlength'), true, false ));
$stream->attachments[] = $attachment;

$this->set('stream', $stream);
$this->load('activities.stream');