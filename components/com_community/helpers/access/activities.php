<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/

Class CActivitiesAccess implements CAccessInterface{

    /**
	 * Method to check if a user is authorised to perform an action in this class
	 *
	 * @param	integer	$userId	Id of the user for which to check authorisation.
	 * @param	string	$action	The name of the action to authorise.
	 * @param	mixed	$asset	Name of the asset as a string.
	 *
	 * @return	boolean	True if authorised.
	 * @since	Jomsocial 2.4
	 */
	static public function authorise()
	{
		$args      = func_get_args();
		$assetName = array_shift ( $args );

		if (method_exists(__CLASS__,$assetName)) {
			return call_user_func_array(array(__CLASS__, $assetName), $args);
		} else {
			return null;
		}
	}

	/*
	 * @param : int(activity_id)
	 * This function will get the permission to add for profile/mainstream activity
	 *
	 * @return : bool
	 */
	static public function activitiesCommentAdd($userId, $assetId, $obj=NULL){

		//$obj = func_get_arg(2);
		$params = func_get_args();
		$obj = (!isset($params[2])) ? NULL : $params[2] ;
		$model		= CFactory::getModel('activities');
		$result		= false;
		$config		= CFactory::getConfig();

		// Guest can never leave a comment
		if( $userId == 0){
			return false;
		}

		// If global config allow all members to comment, allow it
		if( $config->get( 'allmemberactivitycomment' ) == '1')
		{
			return true;
		}

		$allow_comment = false;

		// if all activity comment is allowed, return true
		$config			= CFactory::getConfig();
		if($config->get( 'allmemberactivitycomment' ) == '1' && COwnerHelper::isRegisteredUser()){
			$allow_comment = true;
		}

		if($obj instanceof CTableEvent || $obj instanceof CTableGroup){
			//event or group activities only
			if($obj -> isMember($userId)){
				$allow_comment = true;
			}
		}else if($config->get( 'allmemberactivitycomment' ) == '1' && COwnerHelper::isRegisteredUser()){
			// if all activity comment is allowed, return true
			$allow_comment = true;
		}

		if($allow_comment || CFriendsHelper::isConnected($assetId, $userId) || COwnerHelper::isCommunityAdmin()){
			$result = true;
		}

		return $result;
	}

	/*
	 * @param : int(activity_id)
	 * This function will get the permission to delete for profile/mainstream activity
	 *
	 * @return : bool
	 */
	static public function activitiesDelete($userId, $assetId){
		$obj = func_get_arg(0);
		$model		  = CFactory::getModel('activities');
		$result = false;

		if($obj instanceof CTableEvent || $obj instanceof CTableGroup){
			//event or group activities only
			$isAppOwner =  $obj->isAdmin($userId);
			if($isAppOwner || COwnerHelper::isCommunityAdmin() || $model->getActivityOwner($assetId) == $userId){
				$result = true;
			}
		}else{
			if($model->getActivityOwner($assetId) == $userId){
				$result = true;
			}
		}

		return $result;
	}
}