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

/* class exist checking */
if (!class_exists('CHeadHelper')) {

    /**
     * HTML head helper
     * This class provide method to set HTML head and opengraph metas
     * @since 3.0.1
     */
    class CHeadHelper {

        /**
         * Set page title
         * @param string $title
         */
        public static function setTitle($title) {
            $document = JFactory::getDocument();
            $document->setTitle($title);
            self::addOpengraph('og:title', $title);
        }

        /**
         * Set page description
         * @param string $content
         */
        public static function setDescription($content) {
            if ($content !== '') {
                $document = JFactory::getDocument();
                $document->setDescription($content);
                self::addOpengraph('og:description', $content);
            }
        }

        /**
         * Add Opengraph meta into head
         * @staticvar array $metas
         * @param string $property
         * @param string $content
         * @param boolean $isArray
         */
        public static function addOpengraph($property, $content, $isArray = false) {
            static $metas = array();
            $documentHTML = JFactory::getDocument();

            /* check if property already added */
            if (isset($metas[$property])) {
                /* only adding if it's array type */
                if ($isArray) {
                    $meta = '<meta property="' . $property . '" content="' . $content . '"/>';
                    $metas[$property][] = $meta;
                    $documentHTML->addCustomTag($meta);
                }
            } else { /* property is not exist than add it */
                $meta = '<meta property="' . $property . '" content="' . $content . '"/>';
                /* if this's array we'll store into array too */
                if ($isArray) {
                    $metas[$property][] = $meta;
                } else {
                    $metas[$property] = $meta;
                }
                $documentHTML->addCustomTag($meta);
            }
        }

    }

}