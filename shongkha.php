<?php 

/*
Plugin Name: Shongkha
Plugin URI: http://wordpress.org/extend/plugins/shongkha/
Description: A very simple plugin to change Date, Time, Months and Days in Bangla. It will change default Archive and Calendar Widget too. 
Version: 1.3
Author: TeamFanush
Author URI: http://labs.fanush.net/
*/

/**
 * Copyright (c) 2013, the Fanush Team. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */


define('S_FOLDER', dirname(plugin_basename(__FILE__)));
define('S', WP_PLUGIN_URL.'/'.S_FOLDER);
require_once('calendar_filter.php');




/******************************************************
### GET ALL URL FORM STRING/CONTENT
### PARAMETER: CONTENT
### RETURN ALL URL/LINK AS ARRAY
*****************************************************/

function geturls($string) {
        $regex = '/https?\:\/\/[^\" ]+/i';
        preg_match_all($regex, $string, $matches);
        return ($matches[0]);
}




/***************************************************
### MATCH AND REPLACE STRING FROM CONTENT
***************************************************/
function replace_matches($content,$match, $replace){
	return str_replace($match, $replace, $content);
}




/******************************************************
### GET ALL QUOTE FORM STRING/CONTENT
### PARAMETER: CONTENT
### RETURN ALL QUOTED STRING AS ARRAY
*****************************************************/


function get_quote($input){
	preg_match_all('~([\'"])(.*?)\1~s', $input, $result);
	return ($result[0]);
}





/******************************************************
### GET ALL QUOTE FORM STRING/CONTENT
### PARAMETER: CONTENT
### RETURN ALL QUOTED STRING AS ARRAY
*****************************************************/


function en_to_bangla($content=''){
	include('language.php');
	

	$converted = replace_matches($content, array_keys($digits), $digits);
	$converted = replace_matches($converted, array_keys($days), $days);
	$converted = replace_matches($converted, array_keys($enstrings), $enstrings);	
	$converted = replace_matches($converted, array_keys($months), $months);
	

	$converted_urls = array();
	$allurls = geturls($content);
	
	
	if($allurls){
		foreach($allurls as $url) {
			$converted_digits[] = replace_matches($url, array_keys($digits), $digits);
			$converted_months[] = replace_matches($converted_digits, array_keys($months), $months);
			$converted_days[] = replace_matches($converted_months, array_keys($days), $days);
			$all_converted_url =  array_unique(array_merge($converted_digits,$converted_months,$converted_days));
		}

		$converted_exclude_links = replace_matches($converted, $all_converted_url, $allurls);
	}

	

	
	$converted_quotes = array();
	$allquotes = get_quote($content);
	
	if($allquotes){
		
		foreach($allquotes as $quote) {
			$qconverted_digits[] = replace_matches($quote, array_keys($digits), $digits);
			$qconverted_months[] = replace_matches($qconverted_digits, array_keys($months), $months);
			$qconverted_days[] = replace_matches($qconverted_months, array_keys($days), $days);
			$all_converted_quotes = array_unique(array_merge($qconverted_digits,$qconverted_months,$qconverted_days));
		}
	
		$converted_exclude_links = replace_matches($converted, $all_converted_quotes, array_unique($allquotes));	
		
		return $converted_exclude_links;
	}	else{

		return $converted;
	}

	

}





add_filter( 'get_the_time', 'en_to_bangla' );
add_filter( 'the_date', 'en_to_bangla' );
add_filter( 'get_the_date', 'en_to_bangla' );
add_filter( 'comments_number', 'en_to_bangla' );
add_filter( 'get_comment_count', 'en_to_bangla' );
add_filter( 'get_comment_date', 'en_to_bangla' );
add_filter( 'get_comment_time', 'en_to_bangla' );
add_filter( 'get_archives_link', 'en_to_bangla' );
add_filter( 'wp_list_categories', 'en_to_bangla' );