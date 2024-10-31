<?php

/*
 * load.php
 * This script loads the required environment for the pixoona-functionality
 * 
 * Version: 1.5
 * Date: 2012-04-03
 * Author: redpeppix. GmbH & Co KG
 */

	$this->hashKey		    = get_option('rpx_hashkey');
	$this->apiKey		      = get_option('rpx_apikey');
	$this->locked		      = (int)get_option('rpx_locked');
	$this->limitedToPosts = (int)get_option('rpx_limited_to_posts');
	if ($this->locked) {
	  $this->scriptTag	  = "";
	} else {
  	if ($this->hashKey != "") {
  	  $this->scriptTag	  = '<script type="text/javascript" src="'.$this->redpeppixURL.'hosts/'.$this->hashKey.'/javascript_v2.js"></script>';
  	} else {
  	  $this->scriptTag	  = '<script type="text/javascript" src="http://www.redpeppix.com/pixtec.js"></script>';
  	}
	}
	#$this->limitStartTag  = ($this->locked) ?  "" : '<div class="rpx_limit">';
	#$this->limitEndTag	  = ($this->locked) ?  "" : '</div>';

	// load translation
	load_plugin_textdomain('redpeppix', null, RPX_LANG_PATH);
	
	// load verified-status
	$this->verified = (get_option("rpx_verified", 0) == 1);
?>