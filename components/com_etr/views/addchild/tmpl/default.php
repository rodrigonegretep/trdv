<?php
/**
 * @version		$Id: default.php 15 2009-11-02 18:37:15Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
   ?><p>
      <label for="childName">Nombre</label>
      <input type="text" name="childName"  id="childName" value="">
    </p>
    <p>
      <label for="childMail">Correo</label>
      <input type="text" name="childMail" id="childMail" value="">
    </p>
    <p>
      <label for="childRelation">Correo</label>
      <select name="childRelation" id="childRelation" >
        <?php
        foreach($this->arr_parentesco as $parentesco){
          echo '<option value="'.$parentesco.'">'.$parentesco.'</option>';
        }
        ?>
      </select>
    </p>
