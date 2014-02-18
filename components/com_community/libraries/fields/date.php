<?php

/**
 * @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
 * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author iJoomla.com <webmaster@ijoomla.com>
 * @url https://www.jomsocial.com/license-agreement
 * The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
 * More info at https://www.jomsocial.com/license-agreement
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.utilities.date');
require_once (COMMUNITY_COM_PATH . '/libraries/fields/profilefield.php');

class CFieldsDate extends CProfileField {

    /**
     *
     * @var boolean
     */
    protected $_yearRanger = '';

    /**
     * Method to format the specified value for text type
     * */
    public function getFieldData($field) {
        $value = $field['value'];
        if (empty($value))
            return $value;

        if (!class_exists('CFactory')) {
            require_once( JPATH_ROOT . '/components/com_community/libraries/core.php' );
        }
        require_once( JPATH_ROOT . '/components/com_community/models/profile.php' );
        $params = new CParameter($field['params']);
        $format = $params->get('date_format');
        $model = CFactory::getModel('profile');
        $myDate = $model->formatDate($value, $format);

        return $myDate;
    }

    public function getFieldHTML($field, $required) {
        $params = new CParameter($field->params);
     
        $html = '';

        $day = '';
        $month = 0;
        $year = '';

        $datepickerID = 'datePickerField' . $field->id;
        $showdate = '';

        $readonly = $params->get('readonly') && !COwnerHelper::isCommunityAdmin() ? ' disabled=""' : ' readonly=""';
        $style = $this->getStyle() ? ' style="' . $this->getStyle() . '" ' : '';
        if (!empty($field->value)) {
            if (!is_array($field->value)) {
                $myDateArr = explode(' ', $field->value);
            } else {
                $myDateArr[0] = $field->value[2] . '-' . $field->value[1] . '-' . $field->value[0];
            }

            if (is_array($myDateArr) && count($myDateArr) > 0) {
                $myDate = explode('-', $myDateArr[0]);

                if (strlen($myDate[0]) > 2) {
                    $year = !empty($myDate[0]) ? $myDate[0] : '';
                    $day = !empty($myDate[2]) ? $myDate[2] : '';
                } else {
                    $day = !empty($myDate[0]) ? $myDate[0] : '';
                    $year = !empty($myDate[2]) ? $myDate[2] : '';
                }

                $month = !empty($myDate[1]) ? $myDate[1] : '';
            }
        }

        if (empty($day) || empty($month) || empty($year)) {
            $showdate = '';
        } else {
            $showdate = $this->_fillZero($year, 4) . '-' . $this->_fillZero($month, 2) . '-' . $this->_fillZero($day, 2);
        }

        $class = ($field->required == 1) ? ' required' : '';
        $class .=!empty($field->tips) ? ' jomNameTips tipRight' : '';
        $title = ' title="' . CStringHelper::escape(JText::_($field->tips)) . '"';
        //CFactory::load( 'helpers' , 'string' );
        //$class	= !empty( $field->tips ) ? ' jomNameTips tipRight' : '';
        $html .= '<div style="display: inline-block;">';

        // Individual field should not have a tooltip
        //$class	= ($required) ? 'required' : '' ;

        $html .= '<input type="hidden" id="dpField' . $field->id . 'day" name="field' . $field->id . '[]" value="' . $day . '"  />';
        $html .= '<input type="hidden" id="dpField' . $field->id . 'month" name="field' . $field->id . '[]" value="' . $month . '" />';
        $html .= '<input type="hidden" id="dpField' . $field->id . 'year" name="field' . $field->id . '[]" value="' . $year . '" />';
        $html .= '<span id="errfield' . $field->id . 'msg" style="display:none;">&nbsp;</span>';
        $html .= "<input type=\"text\" id=\"" . $datepickerID . "\" style=\"width:auto; cursor: pointer;\" size=\"10\" class=\"" . $class. " input-medium\" value=\"" . $showdate . "\" class=\"input-small validate-custom-date " . $class . "\"" . $title . $style . $readonly . " />";
        $html .= "<script type=\"text/javascript\">\n";
        $html .= "joms.jQuery(\"#" . $datepickerID . "\" ).datepicker({\n";
        if (isset($this->_yearRanger) && !empty($this->_yearRanger)) {
            $html .= "yearRange: \"" . $this->_yearRanger . "\",\n";
        }
        $html .= "changeMonth: true,\n";
        $html .= "changeYear: true,\n";
        $html .= "dateFormat: 'yy-mm-dd',\n";
        $html .= "onClose: function ( selectedDate ) {\n";
        $html .= "var sDate = new Date(selectedDate);";
        $html .= "joms.jQuery(\"#dpField" . $field->id . "day\").val(sDate.getDate());\n";
        $html .= "joms.jQuery(\"#dpField" . $field->id . "month\").val(sDate.getMonth() + 1);\n";
        $html .= "joms.jQuery(\"#dpField" . $field->id . "year\").val(sDate.getUTCFullYear());\n";
        $html .= "}\n";
        $html .= "});\n";
        $html .= "</script>";
        $html .= '</div>';
        return $html;
    }

    public function isValid($value, $required) {
        if (($required && empty($value)) || !isset($this->fieldId)) {
            return false;
        }

        $db = JFactory::getDBO();
        $query = 'SELECT * FROM ' . $db->quoteName('#__community_fields')
                . ' WHERE ' . $db->quoteName('id') . '=' . $db->quote($this->fieldId);
        $db->setQuery($query);
        $field = $db->loadAssoc();

        $params = new CParameter($field['params']);
        $max_range = $params->get('maxrange');
        $min_range = $params->get('minrange');
        $value = JFactory::getDate(strtotime($value))->toUnix();
        $max_ok = true;
        $min_ok = true;

        //$ret = true;

        if ($max_range) {
            $max_range = JFactory::getDate(strtotime($max_range))->toUnix();
            $max_ok = ($value < $max_range);
        }
        if ($min_range) {
            $min_range = JFactory::getDate(strtotime($min_range))->toUnix();
            $min_ok = ($value > $min_range);
        }

        return ($max_ok && $min_ok) ? true : false;
        //return $ret;
    }

    public function formatdata($value) {
        $finalvalue = '';

        if (is_array($value)) {
            if (empty($value[0]) || empty($value[1]) || empty($value[2])) {
                $finalvalue = '';
            } else {
                $day = intval($value[0]);
                $month = intval($value[1]);
                $year = intval($value[2]);

                $day = !empty($day) ? $day : 1;
                $month = !empty($month) ? $month : 1;
                $year = !empty($year) ? $year : 1970;

                if (!checkdate($month, $day, $year)) {
                    return $finalvalue;
                }

                $finalvalue = $year . '-' . $month . '-' . $day . ' 23:59:59';
            }
        }

        return $finalvalue;
    }

    public function getType() {
        return 'date';
    }

    /**
     * Fill string with zeros until touch limit
     * @param any $val
     * @param int $limit
     * @return string
     */
    private function _fillZero($val, $limit) {
        /* Convert to string */
        $val = (string) $val;
        /* While strlen untouch limit */
        while (strlen($val) < $limit) {
            $val = '0' . $val;
        }
        return $val;
    }

}
