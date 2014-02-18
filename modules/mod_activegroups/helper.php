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

require_once( JPATH_ROOT .'/components/com_community/libraries/core.php');
require_once( JPATH_ROOT .'/components/com_community/helpers/string.php' );

class modActiveGroupsHelper
{
	function getGroupsData( &$params )
	{
		$model	= CFactory::getModel( 'groups' );

		$db = JFactory::getDBO();

		$count 		= $params->get('count', '5');

		$sql = "SELECT ".$db->quoteName('cid').", COUNT(".$db->quoteName('cid').") AS ".$db->quoteName('count')." FROM
						".$db->quoteName('#__community_activities')." a
			 			INNER JOIN	".$db->quoteName('#__community_groups')." b ON a.".$db->quoteName('cid')." = b.".$db->quoteName('id')." WHERE
						a.".$db->quoteName('app')." LIKE ".$db->quote('%groups%')." AND
						b.".$db->quoteName('published')." = ".$db->quote('1')." AND
						a.".$db->quoteName('archived')." = ".$db->quote('0')." AND
						a.".$db->quoteName('cid')." != ".$db->quote('0')." GROUP BY a.".$db->quoteName('cid')."
			   			ORDER BY ".$db->quoteName('count')." DESC
			   			LIMIT ".$count;

		$query = $db->setQuery($sql);
		$row = $db->loadObjectList();

		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
	    }

		$_groups     = array();

		if ( !empty( $row ) ) {
			foreach ( $row as $data )
			{

				$group = $model->getGroup($data->cid);
				if ( $group->id )
				{
				    $groupAvatar= JTable::getInstance( 'group', 'CTable' );
				    $groupAvatar->bind($group);

					$_obj		= new stdClass();
				    $_obj->id    		= $group->id;
                    $_obj->name      	= $group->name;
					$_obj->avatar    	= $groupAvatar->getThumbAvatar();
					$_obj->totalMembers	= count($model->getMembers( $group->id , NULL, true, false, true ));
					//$_obj->totalMembers	= $model->getMembersCount($group->id);

					$_groups[]	= $_obj;
				}
			}
		}
		return $_groups;
	}
}