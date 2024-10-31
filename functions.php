<?php

/*
 * functions.php
 * This script provides essential functionality for the redpeppix.-plugin
 * 
 * Version: 1.2
 * Date: 2010-11-17
 * Author: redpeppix. GmbH & Co KG
 */

// sends a HTTP-GET-request depending on the environment
function rpx_get($url) {
	global $redpeppix;
	$result = "";
	
	try {
	
		// first, try cUrl
		if (function_exists("curl_init")) {
			$curlHdl = curl_init($url);
			curl_setopt_array($curlHdl, array(CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPGET => true));
			$result = curl_exec($curlHdl);
			if ($result === false) { return false; }
			curl_close($curlHdl);
			
		// second, try fsockopen & fgets
		} else if (function_exists("fsockopen")) {
			$errNo = 0;
			$errString = "";
			$socketHdl = fsockopen(str_replace("http://", "", $url), 80, $errNo, $errString, 30);  	
			if ($socketHdl) {
				fputs($socketHdl, "GET ".$url." HTTP/1.1\r\nHost: ".$redpeppix->redpeppixURL."\r\nConnection: Close\r\n\r\n");
				do { 
					$result .= fgets($socketHdl, 4096);
				} while (!feof($socketHdl));
				fclose($socketHdl); 
				$result = end(explode("\r\n\r\n", $result));
			}
			
		// third, try stream_context_create & fopen
		} else if (function_exists("stream_context_create")) {
			$context = stream_context_create(array("http" => array("method" => "GET")));
			$fileHdl = fopen($url, "r", false, $context);
			do { 
				$result .= fgets($fileHdl, 4096);
			} while (!feof($fileHdl));
			fclose($fileHdl);
			
		// finally, if nothing of the above works (bummer!), discontinue processing  
		} else {
			return false;
		}
		
		return $result;
	
	} catch (Exception $e) {}
	
	return false;
}

// sends a HTTP-POST-request depending on the environment
function rpx_post($url, $postXml) {
	global $redpeppix;
	$result = "";
	
	try {

		// first, try cUrl
		if (function_exists("curl_init")) {
			$curlHdl = curl_init($url);
			curl_setopt_array($curlHdl, array(CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_HTTPHEADER => array ("Content-Type: text/xml; charset=utf-8"), CURLOPT_POSTFIELDS => $postXml));
			$result = curl_exec($curlHdl);
			if ($result === false) { return false; }
			curl_close($curlHdl);
			
		// second, try fsockopen & fgets	
		} else if (function_exists("fsockopen")) {
			
			$errNo = 0;
			$errString = "";
			$result = "";
			$socketHdl = fsockopen(str_replace("http://", "", $url), 80, $errNo, $errString, 30);  	
			if ($socketHdl) {
				fputs($socketHdl, "POST ".$url." HTTP/1.1\r\nHost: ".$redpeppix->redpeppixURL."\r\nContent-Type: text/xml; charset=utf-8\r\nContent-length: ".strlen($postXml)."\r\nConnection: Close\r\n\r\n".$postXml."\r\n\r\n");
				do { 
					$result .= fgets($socketHdl, 4096);
				} while (!feof($socketHdl));
				$result = end(explode("\r\n\r\n", $result));
			}
			
		// third, try stream_context_create & fopen
		} else if (function_exists("stream_context_create")) {
			$context = stream_context_create(array("http" => array("method" => "POST", "header" => "Content-Type: text/xml; charset=utf-8\r\nContent-length: ".strlen($postXml)."\r\n", "content" => $postXml)));
			$fileHdl = fopen($url, "r", false, $context);
			do { 
				$result .= fgets($fileHdl, 4096);
			} while (!feof($fileHdl));
			fclose($fileHdl);
			
		// finally, if nothing of the above works (bummer!), discontinue processing  
		} else {
			return false;
		}
		
		return $result;
	
	} catch (Exception $e) {}
	
	return false;
}

// sends a HTTP-PUT-request depending on the environment
function rpx_put($url, $putXml) {
	global $redpeppix;
	$result = "";
	
	try {
	
		// first, try cUrl
		if (function_exists("curl_init")) {
			
			// prepare a buffer for the PUT request
			$requestLength = strlen($putXml);  
		    $fileHdl = fopen('php://memory', 'rw');  
		    fwrite($fileHdl, $putXml);  
		    rewind($fileHdl);
			$curlHdl = curl_init($url);
			curl_setopt_array($curlHdl, array(CURLOPT_RETURNTRANSFER => true, CURLOPT_PUT => true, CURLOPT_HTTPHEADER => array ("Content-Type: text/xml; charset=utf-8"), CURLOPT_INFILE => $fileHdl, CURLOPT_INFILESIZE => $requestLength));
			$result = curl_exec($curlHdl);
			if ($result === false) { return false; }
			curl_close($curlHdl);
		
		// second, try fsockopen & fgets
		} else if (function_exists("fsockopen")) {
			
			$errNo = 0;
			$errString = "";
			$result = "";
			$socketHdl = fsockopen(str_replace("http://", "", $url), 80, $errNo, $errString, 30);  	
			if ($socketHdl) {
				fputs($socketHdl, "PUT ".$url." HTTP/1.1\r\nHost: ".$redpeppix->redpeppixURL."\r\nContent-Type: text/xml; charset=utf-8\r\nContent-length: ".strlen($putXml)."\r\nConnection: Close\r\n\r\n".$putXml."\r\n\r\n");
				do { 
					$result .= fgets($socketHdl, 4096);
				} while (!feof($socketHdl));
				$result = end(explode("\r\n\r\n", $result));
			}
			
		// third, try stream_context_create & fopen
		} else if (function_exists("stream_context_create")) {
			$context = stream_context_create(array("http" => array("method" => "PUT", "header" => "Content-Type: text/xml; charset=utf-8\r\nContent-length: ".strlen($putXml)."\r\n", "content" => $putXml)));
			$fileHdl = fopen($url, "r", false, $context);
			do { 
				$result .= fgets($fileHdl, 4096);
			} while (!feof($fileHdl));
			fclose($fileHdl);
			
		// finally, if nothing of the above works (bummer!), discontinue processing  
		} else {
			return false;
		}
		
		return $result;
	
	} catch (Exception $e) {}
	
	return false;
}

?>