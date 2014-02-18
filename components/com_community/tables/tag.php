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

class CTableTag extends JTable
{
	var $id 		= null;
	var $element 	= null;
	var $userid 		= null;
	var $cid 		= null;
	var $created 	= null;
	var $tag 		= null;

	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__community_tags', 'id', $db );

	}

	public function store( $updateNulls = false ){
		// Make sure that there is no duplicate tag
		$tagModel = CFactory::getModel('tags');
		$tags = $tagModel->getTags($this->element, $this->cid);
		foreach($tags as $row){
			if($row->tag == $this->tag){
				return false;
			}
		}

		// Need to store first before updating the tag stats
		$result = parent::store();

		if($result){
			// Update tag words count
			$word = JTable::getInstance('Tagword', 'CTable');
			$word->load($this->tag);
			$word->update();
		}

		return $result;
	}

}