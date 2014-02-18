<?php
/**
* @version		$Id: (mod_qrcodes_local) helper.php 2.5.1
* @copyright	Copyright (C) 2012 Dave Airey. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_SITE.DS.'modules'.DS.'mod_qrcodes_local'.DS.'tmpl'.DS.'qr_img.php');

class modQRCodesLocalHelper
{
	function strpos_arr($haystack, $needle) {
		if(!is_array($needle)) $needle = array($needle);
		foreach($needle as $what) {
			if(($pos = strpos($haystack, $what))!==false) return $pos;
		}
		return false;
	}

	function getHTTPS() {
		if(!empty($_SERVER["HTTPS"])) {
			if($_SERVER["HTTPS"]!=="off") {
				return true; //https
			}
			else {
				return false; //http
			}
		}
		else {
			return false; //http
		}
	}

	function getPicture(&$params)
	{
		$pageURL = $_SERVER["REQUEST_URI"];
		$include_secure = $params->get( 'include_secure', 'No' );
		$secure_exception_list = $params->get( 'secure_exception_list', '' );
		$secure_exception_list = array_map('trim',explode(",",$secure_exception_list));

		// MAKE SURE WE ARE ON NORMAL HTTP OR IF HTTPS IS TO BE INCLUDED
		if (modQRCodesLocalHelper::getHTTPS() === false || $include_secure == "Yes" || (modQRCodesLocalHelper::strpos_arr($pageURL, $secure_exception_list) !== false )) {

			$expires_time = (int) $params->get( 'expires_time', 10080 );
			$expires_period = $params->get( 'expires_period', 'minutes' );
			$fixed_text = trim( $params->get( 'fixed_text', '' ) );
			$ignore_all = $params->get( 'ignore_all', 'No' );
			// REMOVE ANY TRAILING SLASH, otherwise this would generate 2 QR CODES one for "/shop" and one for "/shop/" which is basically the same page.
			$pageURL = rtrim( $pageURL, '/' );
			// REMOVE ANY LEADING SLASH, otherwise this would generate file with dash(-) at beginning of filename.
			$pageURL = ltrim( $pageURL, '/' );
			$SERVER_PATH_FOLDER = JPATH_CACHE.DS."qrcodes_local".DS;
			$CACHE_FOLDER = "cache".DS."qrcodes_local".DS;

			// LIST OF EXCLUDED WORDS OR PAGES e.g. CART, CHECKOUT STEPS, ACCOUNT MAINTENANCE, USER, 404, FEEDS OR PORT REQUESTS
			//$exclusion_list = array('/page-', 'undefined', 'cart', 'checkout', 'account', 'user', '404', ':', 'upload', 'feed', 'dp1', 'dp2', 'dp3', 'dp4');
			$exclusion_list = $params->get( 'exclusion_list', '' );
			$exclusion_list = array_map('trim',explode(",",$exclusion_list));

			// CHECK SO WE DON'T SHOW QR CODE FOR EXCLUDED WORDS
			if ( modQRCodesLocalHelper::strpos_arr($pageURL, $exclusion_list) === false ) {

				// CREATE cache folder with write perms, the @ means it won't show errors if cache folder exists already
				@mkdir($SERVER_PATH_FOLDER,0755);

				// CREATE BLANK index.html IF IT DOESN'T EXIST SO FOLDER CAN'T BE BROWSED
				$filename = $SERVER_PATH_FOLDER."index.html";
				if (!file_exists($filename)) {
					file_put_contents($filename,"<html><body bgcolor='#FFFFFF'></body></html>");
					chmod($filename, 0644);
				}

				// CHECK IF THERE IS FIXED TEXT FOR MODULE IF SO WE CAN JUST SET IT.
				if ( $fixed_text != '' ){
					$picture_name = $fixed_text;
				}
				else {
					//CHECK FOR PARAMETERS APPENDED AFTER URL
					$question_mark_array =  explode('?',$pageURL);
					$picture_name = $pageURL;
					if ( $ignore_all == 'Yes' ) {
						// IF ? is found $picture_name is bit before ?
						if ( count($question_mark_array) > 1 ) {
							$picture_name = $question_mark_array[0];
						}
					}
					else {
						if ( count($question_mark_array) > 1 ) {
							$ignore_some = $params->get( 'ignore_some', '' );
							$ignore_some = array_map('trim',explode(",",$ignore_some));
							if ( $ignore_some[0] == "" ) {
								$picture_name = $pageURL;
							}
							else {
								$startURL = $question_mark_array[0];
								$new_params = $question_mark_array[1];
								if ( strlen($new_params) > 0 ) { 
									$new_params = array_map('trim', explode("&", $new_params));
									// NOT SURE THIS IS THE MOST EFFICIENT WAY OF DOING THIS.
									// I NEED TO CHECK EVERY VALUE IN ignore_some AGAINST EVERY POSSIBLE VALUE IN new_params AND REPLACE WITH '' IF IT's THERE.
									foreach ($ignore_some as $item ) {
										//$keys = array_keys($new_params,$item);
										$keys = preg_grep('/'.$item.'(.*)/s', $new_params);
										foreach ($keys as $index => $value) {
											// REPLACE IT IF IT IS IN THE PARAMS
											$new_params[$index] = preg_replace('/'.$item.'(.*)/s', '', $new_params[$index]);
										}
									}
									$new_params = array_filter($new_params);
									$endURL = join("&",$new_params);
									if (count($new_params) > 0 ) {
										$picture_name = $startURL."?".$endURL;
									}
									else {
										$picture_name = $startURL;
									}
								}
							}
						}
					}
				}
				// COPY ALTERED PICTURE NAME BACK TO MAKE URL THE SAME
				$pageURL = $picture_name;
				// picture_name - NEED to REPLACE certain characters for file system
				// $picture_name = str_replace( array( " ", "/", ".", "?", "&", "=", "%", ",", "[", "]", "<", ">", "*", "!", "|", "\"", "'" ), "-", $picture_name );
				$picture_name = preg_replace("/[^a-z0-9_-s.]/i","-",$picture_name); 
				// CHECK IF QR CODE FOR THIS URL ALREADY EXISTS
				if (!file_exists($SERVER_PATH_FOLDER.$picture_name.".png") ) {
					// ADD Website to PAGE ADDRESS
					$pageURL = $_SERVER['HTTP_HOST'].'/'.$pageURL;
					if ( $fixed_text != '' ){
						$pageURL = $fixed_text;
					}
					// IF NOT CREATE IT
					// using function create($name, $url, $output, $type, $size, $data, $correct, $version)
					qrcode_image::create( $picture_name, $pageURL, $CACHE_FOLDER, "", 4, $pageURL, 'M', "" );
					chmod($SERVER_PATH_FOLDER.$picture_name.".png", 0644);
				}
				// CREATE AN EXPIRES FILE FOR THE CACHED FILE (IF ONE DOESN'T EXIST) WITHOUT THE IF UPDATES AT EACH USE.
				//if (!file_exists($SERVER_PATH_FOLDER.$picture_name.".png_expire") ) {
					$date = new DateTime();
					$date->modify('+'.$expires_time.' '.$expires_period);
					file_put_contents($SERVER_PATH_FOLDER.$picture_name.".png_expire",$date->getTimestamp());
					chmod($SERVER_PATH_FOLDER.$picture_name.".png_expire", 0644);
				//}
				$qr_code = '<img src="'.$CACHE_FOLDER.$picture_name.'.png" alt="QRCODE - '.$picture_name.'" />';
			}
			else {
				$qr_code = '';
			}
		}
		else {
			$qr_code = '';
		}
	// Return IMAGE and TEXT
	$headerText	= trim( $params->get( 'header_text' ) );
	$footerText	= trim( $params->get( 'footer_text' ) );
	if ( $qr_code == '' ) {
		$headerText	= '';
		$footerText	= '';
	}
	return array($headerText, $qr_code, $footerText);
	}
}
?>
