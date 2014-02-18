<?php
defined('_JEXEC') or die('Restricted access');
require_once JPATH_ROOT . '/components/com_community/libraries/core.php';

if(!class_exists('plgCommunityEtrcommunity'))
{
	class plgCommunityEtrcommunity extends CApplications {
		var $name = "EtrCommunity";
		var $_name = 'etrcommunity';

		function __construct(& $subject, $config) {
			parent::__construct($subject, $config);
          $this->loadLanguage();
			//dump("plgCommunityEtrcommunity","__construct");
          jimport('joomla.utilities.arrayhelper');

          $mainframe = JFactory::getApplication();
          $jinput = $mainframe->input;

          $my = JFactory::getUser();
          $userid = $jinput->get('userid', $my->id, 'INT'); //JRequest::getVar('userid', $my->id);
          $user = CFactory::getUser($userid);
          $template	= new CTemplate();

          $status = new CUserStatus($user->id, 'profile');
          $creator        = new CUserStatusCreator('gift');
          $creator->title = JText::_('PLG_ETRCOM_GIFTCREATOR_TITLE');
          $creator->html  = $template->fetch('etrcommunity.gift');

          $status->addCreator($creator);

         // CUserHelper::addDefaultStatusCreator($status);

          global $jaxFuncNames;
          $jaxFuncNames[] = "plugins,etrcommunity,ajaxaddchild";
          $jaxFuncNames[] = "plugins,etrcommunity,ajaxSaveChild";
        }

		function onProfileDisplay() {
			ob_start();

			echo 'Hello World';

			$content = ob_get_contents();
			ob_end_clean();
			//dump($content,"onProfileDisplay");
			return $content;
		}

		function onFormDisplay($form_name) {
			/*	Add additional form elements at the bottom privacy page	*/
			//dump($form_name,"onFormDisplay");
			$elements = array();
			if ($form_name == 'jsform-groups-form') {


              $catid_user = 8;
              $db = JFactory::getDbo();
              $query = $db->getQuery(true);

              $query->select(array('id', 'title'));
              $query->from('#__content');

              $query->where('catid ='.$catid_user);
              $db->setQuery($query);
              $results = $db->loadObjectList();
              $opciones = array();
              $txt_opciones = "";
              foreach ($results as $key=>$result) {
                //$opciones[$result->id] = $result->title;
                $txt_opciones .= '<option value="'.$result->id.'">'.$result->title.'</option>';
              }


              $obj = new CFormElement();
				$obj->label = 'Labe1 1';
				$obj->position = 'before';
				$obj->html = '<select name="custom1" id="custom1">'.$txt_opciones.'</select>';
				$elements[] = $obj;

				$obj = new CFormElement();
				$obj->label = 'Labe1 2';
				$obj->position = 'after';
				$obj->html = '<input name="custom2" type="text">';
				$elements[] = $obj;

			}

			return $elements;
		}

      public function onCommunityStreamRender($act)
      {
        JPlugin::loadLanguage ( 'plg_example', JPATH_ADMINISTRATOR ); // only use if theres any language file
        $actor = CFactory::getUser($act->actor);
        $actorLink = '<a class="cStream-Author" href="' .CUrlHelper::userLink($actor->id).'">'.$actor->getDisplayName().'</a>';

        $stream    = new stdClass();
        $stream->actor  = $actor;
        $stream->headline = JText::sprintf('PLG_ETRCOM_ACTIVITY_HEADLINE', $actorLink );
        $stream->message = 'Message';

        return $stream;
      }


      function ajaxaddchild($objResponse,$friendid,$groupid) {
        //$objResponse = new JAXResponse();
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
ob_start();
?>
<script>
  function save(fid, gid) {
    name = joms.jQuery('#childName').val();
    mail = joms.jQuery('#childMail').val();
    relation = joms.jQuery('#childRelation').val();
    id = joms.jQuery('#idparent').val();
    jax.call('community', 'plugins,etrcommunity,ajaxSaveChild',fid,gid,name,mail,relation);
  }
</script>
        <p>
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
            foreach($arr_parentesco as $parentesco){
              echo '<option value="'.$parentesco.'">'.$parentesco.'</option>';
            }
            ?>
          </select>
        </p>
<input type="hidden" name="idparent" value="<?php echo $friendid; ?>">
<input type="hidden" name="idgroup" value="<?php echo $groupid; ?>">
        <?php
$html = ob_get_contents();
ob_end_clean();
$actions = '<button class="button" onclick="save('.$friendid.' , '.$groupid.');" name="save">Save</button>';

$actions .= '<button class="button" onclick="javascript:cWindowHide();" name="cancel">Cancel</button>';

$objResponse->addAssign('cwin_logo', 'innerHTML', 'Agregar Descendiente');
$objResponse->addScriptCall('cWindowAddContent', $html, $actions);
return $objResponse;
}

      function ajaxSaveChild($objResponse,$friendid,$gid,$name,$mail,$relation) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select("COUNT(*)");
        $query->from('#__etr_tree');

        $query->where('gid ='.$gid.' and nombre="'.$name.'" and email="'.$mail.'" and parentesco padre="'.$relation.'"');
        $db->setQuery($query);
        $results = $db->loadResult();
        //echo "results:".print_r($results,true).";";
        if(!$results){

          $db->setQuery( 'INSERT INTO #__etr_tree (gid,nombre,email,parentesco) VALUES ('.$gid.' , "'.$name.'","'.$mail.'","'.$relation.'")' );
          $db->query();
          if (!$db->query())
          {
            throw new Exception($db->getErrorMsg());
          }
echo '{relacion creada}';
        } else {
          echo "{relacion existente}";
        }

      }


    }

}
