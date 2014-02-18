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

require_once (COMMUNITY_COM_PATH . '/models/videos.php');

/**
 * Class to manipulate data from MySpace
 *
 * @access	public
 */
class CTableVideoMyspace extends CVideoProvider {

    var $xmlContent = null;
    var $url = '';
    var $videoId = '';

    /**
     * Return feedUrl of the video
     */
    public function getFeedUrl() {
        /**
         * @since 3.0.6 we use url same as input for myspace. Not do id converting
         */
        return $this->url;
    }

    /**
     * Extract MySpace video id from the video url submitted by the user
     *
     * @access	public
     * @param	video url
     * @return videoid
     */
    public function getId() {
        $pattern = '/(\d{9})/';
        preg_match($pattern, $this->url, $match);

        return !empty($match[1]) ? $match[1] : null;
    }

    /**
     * Return the video provider's name
     *
     */
    public function getType() {
        return 'myspace';
    }

    public function getTitle() {
        $title = '';

        $pattern = "'<meta property=\"og:title\" content=\"(.*?)\"'s";
        preg_match_all($pattern, $this->xmlContent, $matches);

        $title = $matches[1][0];

        if ($title == '') {
            $pattern = "'name=\"context_title\" value=\"(.*?)\"'s";
            preg_match_all($pattern, $this->xmlContent, $matches);

            $title = $matches[1][0];
        }

        return $title;
    }

    public function getDescription() {
        $description = '';

        // Get description
        $pattern = "'<meta property=\"og:description\" content=\"(.*?)\"'s";
        preg_match_all($pattern, $this->xmlContent, $matches);

        $description = $matches[1][0];

        if ($description == '') {
            $pattern = "'desc\":\"(.*?)\"'s";
            preg_match_all($pattern, $this->xmlContent, $matches);

            $description = stripslashes($matches[1][0]);
        }

        return $description;
    }

    public function getDuration() {
        $pattern = "'<meta property=\"video:duration\" content=\"(.*?)\"'s";
        preg_match_all($pattern, $this->xmlContent, $matches);

        return $matches[1][0];
    }

    public function getThumbnail() {
        $thumbnail = '';

        $pattern = "'thmb_url\":\"(.*?)\"'s";
        preg_match_all($pattern, $this->xmlContent, $matches);

        $thumbnail = (!isset($matches[1][0])) ? '' : stripslashes($matches[1][0]);

        if ($thumbnail == '') {
            $pattern = "'<meta property=\"og:image\" content=\"(.*?)\"'s";

            preg_match_all($pattern, $this->xmlContent, $matches);

            if ($matches && !empty($matches[1][0])) {
                $thumbnail = urldecode($matches[1][0]);
            }
        }

        return CVideosHelper::getIURL($thumbnail);
    }

    /**
     *
     *
     * @return $embedvideo specific embeded code to play the video
     */
    public function getViewHTML($videoId, $videoWidth, $videoHeight) {
        if (!$videoId) {
            $videoId = $this->videoId;
        }
        if (strpos($videoId, "&") == true) {
            $videoId_tmp = substr($videoId, strpos($videoId, "&"));
            $videoId = CString::str_ireplace($videoId_tmp, "", $videoId);
        }

        $embedCode = '<object width="' . $videoWidth . 'px" height="' . $videoHeight . 'px" ><param name="allowFullScreen" value="true"/><param name="wmode" value="transparent"/><param name="movie" value="' . CVideosHelper::getIURL('http://mediaservices.myspace.com/services/media/embed.aspx/m=' . $videoId . ',t=1,mt=video') . '"/><embed src="' . CVideosHelper::getIURL('http://mediaservices.myspace.com/services/media/embed.aspx/m=' . $videoId . ',t=1,mt=video') . '" width="' . $videoWidth . '" height="' . $videoHeight . '" allowFullScreen="true" type="application/x-shockwave-flash" wmode="transparent"/></object>';

        return $embedCode;
    }

}