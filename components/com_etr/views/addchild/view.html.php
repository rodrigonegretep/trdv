<?php

/**
 * @version		$Id: view.html.php 15 2009-11-02 18:37:15Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the HelloWorld Component
 */
class EtrViewAddChild extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
	$arr_parentesco = array();
    $arr_parentesco[] = "Conyuge";
    $arr_parentesco[] = "Hija/o";
    $arr_parentesco[] = "Nieta/o";
    $arr_parentesco[] = "Padre";
    $arr_parentesco[] = "Madre";
    $arr_parentesco[] = "Hermana/o";
    $arr_parentesco[] = "Sobrina/o";
    $arr_parentesco[] = "Prima/o";
    $arr_parentesco[] = "Cuñada/o";
    $arr_parentesco[] = "Amiga/o";
	$this->arr_parentesco = $arr_parentesco;
		// Display the view
		parent::display($tpl);
	}
}
