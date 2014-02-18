<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
require_once( JPATH_ROOT .'/components/com_community/models/models.php' );

class CommunityModelBlock extends JCCModel
{
	/**
         * Check if $myId is blocked by $userId
         * @param type $myId
         * @param type $userId
         * @return boolean
         */
	public function getBlockStatus($myId,$userId)
	{
		// A Guest obviously has not blocked anyone or
		// have anyone else blocked hi
		if($userId == 0 || $myId == 0){
			return false;
		}

		$db	= $this->getDBO();

		$query	= 'SELECT ' . $db->quoteName('id')
					.' FROM ' . $db->quoteName('#__community_blocklist')
					.' WHERE ' . $db->quoteName('blocked_userid') .'=' . $db->Quote($myId)
					.' AND ' . $db->quoteName('userid') .'=' . $db->Quote($userId);

		$db->setQuery( $query );
		$result	= $db->loadObject() ? true : false;

		if($db->getErrorNum())
		{
			JError::raiseError(500, $db->stderr());
		}

		return $result;
	}

	/**
	 * ban a user
	 */
	public function blockUser($myId,$userId)
	{
		$db	= $this->getDBO();

		// check if user is banned
		if( !$this->getBlockStatus($userId,$myId) && $myId!=$userId ){

			$query	= 'INSERT INTO ' . $db->quoteName('#__community_blocklist')
					. ' SET ' . $db->quoteName('blocked_userid').'=' . $db->Quote($userId)
					. ' , ' . $db->quoteName('userid') .'=' . $db->Quote($myId);

			$db->setQuery( $query );
			$db->query();

			if($db->getErrorNum())
			{
				JError::raiseError(500, $db->stderr());
			}

			return true;

		}

	}

	/**
	 * remove ban a user (unban)
	 */
	public function removeBannedUser($myId,$userId)
	{
		$db	= $this->getDBO();

		// check if user is banned
		if( $this->getBlockStatus($userId,$myId) ){

			$query	= 'DELETE FROM ' . $db->quoteName('#__community_blocklist')
					. ' WHERE ' . $db->quoteName('blocked_userid') .'=' . $db->Quote($userId)
					. ' AND ' . $db->quoteName('userid') .'=' . $db->Quote($myId);

			$db->setQuery( $query );
				$db->query();

			if($db->getErrorNum())
			{
				JError::raiseError(500, $db->stderr());
			}

			return true;

		}

	}

	/**
	 * get list of ban user
	 */
	public function getBanList($myId)
	{
		$db	= $this->getDBO();

		$query	= "SELECT m.*,n.`name` FROM `#__community_blocklist` m "
				. "LEFT JOIN `#__users` n ON m.`blocked_userid`=n.`id` "
				. "WHERE m.`userid`=" . $db->Quote($myId) . " "
                . "AND m.`blocked_userid`!=0";
		$db->setQuery( $query );

		$result	= $db->loadObjectList();

		return $result;
	}

}
