<?php
/*
Plugin Name: pixoona Plugin
Plugin URI: http://pixoona.com/
Description: This is the official pixoona plugin. It adds the pixoona PIXSETTING technology to your blog.
Author: redpeppix. GmbH & Co. KG
Version: 2.2
* 
Author URI: http://pixoona.com/

Copyright (C) 2010 redpeppix. GmbH & Co. KG

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.

*/

require_once (dirname(__FILE__).'/functions.php');
if (!class_exists('rpxLoader')) {
class rpxLoader {
	
	var $version	  	  = '2.0';
	var $minium_WP		  = '2.8.6';
	var $redpeppixURL	  = '';
	var $redpeppixWpURL = 'http://www.redpeppix.com/';
	
	function rpxLoader() {
		
		$this->redpeppixURL = (isset($_SERVER['HTTPS'])) ? 'https://www.redpeppix.com/' : 'http://www.redpeppix.com/';
		
		$this->plugin_name = plugin_basename(__FILE__);
		
		// check minimum WordPress-Version
		if (!$this->version_requirement()) {
			return;
		}
		
		// load options
		$this->load();
		
		// start after loading is finished
		add_action('wp_head', array(&$this, 'start'));
		
		// add limiters if limiting is activated
		if ($this->limitedToPosts == 1) {
			add_action('loop_start', array(&$this, 'limiterPre'));
			add_action('loop_end', array(&$this, 'limiterPost'));
		}
	}
	
	private function load() {
		
		define('RPX_BASE_PATH', plugin_basename(dirname(__FILE__)));
		define('RPX_CORE_PATH', plugin_basename(dirname(__FILE__))."/core");
		define('RPX_ADMIN_PATH', plugin_basename(dirname(__FILE__))."/admin");
		define('RPX_LANG_PATH', plugin_basename(dirname(__FILE__))."/languages");
		
		require_once (dirname(__FILE__).'/core/load.php');

		// load admin resources if authorized
		if (is_admin()) {	
			require_once (dirname(__FILE__).'/admin/admin.php');
			$this->rpxAdmin = new rpxAdmin();
		}
	}
	
	function version_requirement() {
		global $wp_version;
			
		if (!version_compare($wp_version, $this->minium_WP, '>=')) {
			add_action("admin_notices", create_function("", 'global $redpeppix; printf(\'<div id="message" class="error"><p><strong>\'.__(\'You need at least WordPress Version %s to run the pixoona plugin.\', "redpeppix").\'</strong></p></div>\', $redpeppix->minium_WP);'));
			return false;
		}
		return true;
	}
	
	function verifyApikey() {
		return require_once (dirname(__FILE__).'/core/verify.php');
	}
	
	function updateHostSettings() {
		return require_once (dirname(__FILE__).'/core/update.php');
	}
	
	function start() {
		require_once (dirname(__FILE__).'/core/start.php');
	}
	
	function limiterPre() {
		require_once (dirname(__FILE__).'/core/limit_pre.php');
	}
	
	function limiterPost() {
		require_once (dirname(__FILE__).'/core/limit_post.php');
	}
}
	// Engage!
	global $redpeppix;
	$redpeppix = new rpxLoader();
}
?>