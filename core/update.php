<?php

/*
 * update.php
 * This script updates the blog's settings using the given pixoona api-key
 * 
 * Version: 1.4
 * Date: 2012-04-03
 * Author: redpeppix. GmbH & Co KG
 */

if ($this->hashKey != "") {
	$success = false;
	do {

		$this->locked	  = (int)get_option('rpx_locked');

		$result = rpx_put($this->redpeppixURL."hosts/".$this->hashKey.".xml?api_key=".$this->apiKey."&hashkey=".$this->hashKey, "<host><locked>".$this->locked."</locked></host>");
		if (!$result) {
			break;
		}

		$resultXml = @new SimpleXMLElement($result);
		$this->hashKey 	  = (string)$resultXml->hashkey;
		$this->locked	  = ((int)$resultXml->{"status-id"} != 4) ? 1 : 0;

		update_option("rpx_locked",		(int)$this->locked);
		update_option("rpx_hashkey",	$this->hashKey);

		$success = true;

	} while (false);

	return $success;
} else {
  return true;
}
?>