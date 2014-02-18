<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license http://www.azrul.com Copyrighted Commercial Software
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');

if(!class_exists('plgCommunityInvite'))
{
	class plgCommunityInvite extends CApplications
	{
		var $name		= 'Invite';
		var $_name		= 'invite';
		var $_user		= null;
	
	    function plgCommunityInvite(& $subject, $config)
	    {
			$this->_user	= CFactory::getRequestUser();
			$this->_my		= CFactory::getUser();
		
			parent::__construct($subject, $config);
	    }
		
		// detect GET['invite'] and add cookies 
		function onSystemStart() {
			
			$inviteid = JRequest::getVar('invite', '', 'GET');
			if( !empty( $inviteid ) ){
				setcookie('inviteId', $inviteid, time()+60*60*24, '/');
			}
			
		}
		
		function onUserRegisterFormDisplay(&$text) {
			$invite = JRequest::getVar('inviteId', '', 'COOKIE');
			$text = JString::str_ireplace('</form>', '<input type="hidden" name="invite" value="'. $invite .'"></form>', $text);
		}
		
		function onAfterUserRegistration() {
		}
	}	
}

