<?php
defined('_JEXEC') or die('Restricted access');
if (!class_exists('plgSystemEtrsystem')) {
  class plgSystemEtrsystem extends JPlugin {
    /**
     * Method construct
     */
    function plgSystemEtrsystem($subject, $config) {
      parent::__construct($subject, $config);
      JPlugin::loadLanguage('plg_system_etrsystem', JPATH_ADMINISTRATOR); // only use if theres any language file
      include_once(JPATH_ROOT . '/components/com_community/libraries/core.php'); // loading the core library now
      //dump("etrsystem");
    }

    /**
     * This event is triggered after the framework has loaded and the application initialise method has been called.
     */
    public function onAfterRoute() {

      // Do something here :)

    }

    function onFormDisplay($form_name) {
      /*
          Add additional form elements at the bottom privacy page
       */
      dump($form_name);
      $elements = array();
      if ($form_name == 'jsform-profile-privacy') {
        $obj = new CFormElement();
        $obj->label = 'Labe1 1';
        $obj->position = 'after';
        $obj->html = '<input name="custom1" type="text">';
        $elements[] = $obj;

        $obj = new CFormElement();
        $obj->label = 'Labe1 2';
        $obj->position = 'after';
        $obj->html = '<input name="custom2" type="text">';
        $elements[] = $obj;

      }

      return $elements;
    }

  }
}

?>
