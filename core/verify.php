<?php

/*
 * verify.php
 * This script verifies the given api-key via the pixoona-API
 * 
 * Version: 1.4
 * Date: 2012-04-03
 * Author: redpeppix. GmbH & Co KG
 */

if ($this->apiKey != "") {
	// initialize all attributes as false
	$this->verified   = false;
	$this->locked	  = 0;
	$this->hashKey 	  = "";
	$site 			  = explode("/", get_option("siteurl"));
	$siteUrl 		  = $site[2];

	do {

		// load host list
		$result = rpx_get($this->redpeppixURL."hosts.xml?api_key=".$this->apiKey);
		if (!$result) {
			break;
		}

		// if host exists use hashkey
		$resultXml = @new SimpleXMLElement($result);
		$exists = false;
		foreach ($resultXml->host as $host) {

			if ($host->domain == $siteUrl) {
				$this->hashKey 	  = (string)$host->hashkey;
				$this->locked	  = ((int)$host->{"status-id"} != 4) ? 1 : 0;
				$exists = true;
				break;
			}
		} // NEXT host

		// add host and set hashkey if it does not exist
		if ($exists) {
			break;
		}
		$result = rpx_post($this->redpeppixURL."hosts.xml?api_key=".$this->apiKey, "<host><domain>".$siteUrl."</domain></host>");
		if (!$result) {
			break;
		}
		$resultXml = @new SimpleXMLElement($result);
		$this->hashKey = (string)$resultXml->hashkey;

	} while (false);

	$this->verified = $this->hashKey != "";
	update_option("rpx_verified", 	(int)$this->verified);
	update_option("rpx_locked",		(int)$this->locked);
	update_option("rpx_hashkey",	$this->hashKey);
	return $this->verified;
}
?>