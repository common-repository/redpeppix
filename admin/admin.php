<?php

/*
 * admin.php
 * Class for handling all pixoona administrative stuff
 * 
 * Version: 0.2
 * Date: 2012-04-03
 * Author: redpeppix. GmbH & Co KG
 */

class rpxAdmin{
	
	// constructor
	function rpxAdmin() {

		// add redpeppix-options to the general options menu
		add_action("admin_menu", array (&$this, "add_menus"));
	}
	
	function add_menus() {
		add_menu_page(__("pixoona Configuration","redpeppix"), __("pixoona","redpeppix"), "manage_options", "rpx_settings", array (&$this, "show_menu"));
		add_submenu_page("rpx_settings", __("Configuration","redpeppix"), __("Configuration","redpeppix"), "manage_options", "rpx_settings", array (&$this, "show_menu"));
	}

	// display the selected options page
	function show_menu() {
		
		global $redpeppix;
		
		if (!current_user_can("manage_options"))  {
			wp_die(__("You do not have sufficient permissions to access this page.", "redpeppix"));
		}
		
		switch ($_GET["page"]){
			
			case "rpx_statistics" : {
				include_once (dirname(__FILE__)."/statistics.php" );
				break;
			}
			case "rpx_settings" : 
			default : {
				include_once (dirname(__FILE__)."/settings.php" );
			}
		}
	}
}
?>