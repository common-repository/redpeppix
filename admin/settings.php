<?php 

/*
 * settings.php
 * Handler for viewing and editing administrative pixoona options
 * 
 * Version: 0.3
 * Date: 2012-04-03
 * Author: redpeppix. GmbH & Co KG
 */

$messages = array();

// update host settings
if (isset($_POST["action"]) && $_POST["action"] == "activate") {
		
	if ((int)$_POST["locked"] 	  == $redpeppix->locked &&
		(int)$_POST["limitedToPosts"] == $redpeppix->limitedToPosts
	) {
		$messages[] = array("type" => "notice", "text" => __("No changes have been made.", "redpeppix"));
	} else {
	
		update_option("rpx_locked",			      (int)$_POST["locked"]);
		update_option("rpx_limited_to_posts", (int)$_POST["limitedToPosts"]);
		
		$success = $redpeppix->updateHostSettings();

		$redpeppix->locked         = (int)get_option('rpx_locked');
		$redpeppix->limitedToPosts = (int)get_option('rpx_limited_to_posts');

		if ($success) {
			$messages[] = array("type" => "notice", "text" => __("Your settings have been saved.", "redpeppix"));
		} else {
			$messages[] = array("type" => "error", "text" => __("Your settings could not be saved.", "redpeppix"));
		}
	}
}

// verify key
$redpeppix->verifyApikey();

?>
<div class="wrap">
	<?php screen_icon('redpeppix'); ?>
	<h2><?php _e('pixoona Configuration', 'redpeppix'); ?></h2>
<?php

	//TODO message typ auswerten
	if (is_array($messages) && count($messages) > 0) {
		echo '<div class="wrap"><h2></h2><div class="updated fade" id="message">';
		foreach ($messages as $message) {
			echo "<p>".$message["text"]."</p>";
		}
		echo "</div></div>\n";
	} 
?>
	<div id="poststuff">
	<div class="postbox" id="host_settings_box">
	    <h3 class="hndle"><span><?php _e('PIXSETTING rights', 'redpeppix'); ?></span></h3>
		<div class="inside">
			<form name="update_host_settings" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
				<?php wp_nonce_field('rpx_settings') ?>
				<input type="hidden" name="action" value="activate">
				<h4><?php _e("Define your PIXSETTING rights", "redpeppix"); ?></h4> 
				<?php _e("Activate/deactivate the PIX-technology on your blog", "redpeppix"); ?>:
				<select name="locked">
					<option value="1"<?php if ($redpeppix->locked) echo " selected"; ?>><?php _e("Not active", "redpeppix"); ?></option>
					<option value="0"<?php if (!$redpeppix->locked) echo " selected"; ?>><?php _e("Active", "redpeppix"); ?></option>
				</select>
				<h4><?php _e("PIX-scope on your blog", "redpeppix"); ?></h4>
				<?php _e("Define if you want to release the PIXSETTING on your blog in total, like on the layout pictures of the blog theme or if you want to limit it on your posts and comments.", "redpeppix"); ?><br />
				<br />
				<input type="hidden" name="limitedToPosts" value="0">
				<input type="checkbox" id="limitedToPosts" name="limitedToPosts"<?php if ($redpeppix->limitedToPosts) echo ' checked="checked"'; ?>  value="1" /> <label for="limitedToPosts"><?php _e("activate PIXSETTING limiter", "redpeppix"); ?></label>
				<br />
				<br />
				<input type="submit" class="button" name="domainsettings" value="<?php _e('Update settings', 'redpeppix') ;?>" />
			</form>
		</div>
	</div>
	</div>