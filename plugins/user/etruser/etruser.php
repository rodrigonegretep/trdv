<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
 
class plgUserEtruser extends JPlugin {
 
    public function plgUserEtruser(& $subject, $config) {
        parent::__construct($subject, $config);
    }
 
    public function onUserAfterSave($user, $isnew, $success, $msg) {
        if ($isnew) {
            $this->addStream($user['id']);
        }
    }
 
    private function addStream($userid) {
        $lang = & JFactory::getLanguage();
        $lang->load('plg_user_etruser', JPATH_ADMINISTRATOR);
 
        require_once JPATH_ROOT .'/components/com_community/libraries/core.php';
        CFactory::load('libraries', 'activities');
        $my = & CFactory::getUser($userid);
 
        $act = new stdClass();
        $act->cmd = 'members.register';
        $act->actor = $my->id;
        $act->target = 0;
        $act->title = JText::_($this->params->get('activitymessage', 'PLG_ETRUSER_NEW_USER_REGISTERED'));
        $act->content = '';
        $act->app = 'profile';
        $act->cid = 0;
        $act->params = '';
 
        CActivityStream::add($act);
    }

  function onProfileDisplay()
  {
    $content = "ETR USER : contenidos";
    dump($content);
    return $content;

  }
 
}

?>