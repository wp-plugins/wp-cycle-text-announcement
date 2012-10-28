<?php

/*
Plugin Name: Wp cycle text announcement
Plugin URI: http://www.gopiplus.com/work/2012/04/07/wp-cycle-text-announcement-wordpress-plugin/
Description: Wp cycle text plugin is to show the text news with cycle jQuery. Display one news at a time and cycle the remaining in the mentioned location.
Author: Gopi.R
Version: 5.0
Author URI: http://www.gopiplus.com/work/2012/04/07/wp-cycle-text-announcement-wordpress-plugin/
Donate link: http://www.gopiplus.com/work/2012/04/07/wp-cycle-text-announcement-wordpress-plugin/
Tags: Cycle, text, announcement, wordpress, plugin
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

global $wpdb, $wp_version;
define("WP_WPCYTXT_SETTINGS", $wpdb->prefix . "cycletext_settings");
define("WP_WPCYTXT_CONTENT", $wpdb->prefix . "cycletext_content");

function wpcytxt($setting) 
{
	global $wpdb;
	$sSql = "select wpcytxt_sid, wpcytxt_sname, wpcytxt_slink, wpcytxt_sdirection,";
	$sSql = $sSql . " wpcytxt_sspeed, wpcytxt_stimeout, wpcytxt_srandom from ". WP_WPCYTXT_SETTINGS ." where 1=1";
	$sSql = $sSql . " and wpcytxt_sname='".strtoupper($setting)."'";
	$wpcycletxt_settings = $wpdb->get_results($sSql);
	if ( ! empty($wpcycletxt_settings) ) 
	{
			$settings = $wpcycletxt_settings[0];
			$wpcytxt_sname = $settings->wpcytxt_sname; 
			$wpcytxt_slink = $settings->wpcytxt_slink; 
			$wpcytxt_sdirection = $settings->wpcytxt_sdirection; 
			$wpcytxt_sspeed = $settings->wpcytxt_sspeed; 
			$wpcytxt_stimeout = $settings->wpcytxt_stimeout; 
			$wpcytxt_srandom = $settings->wpcytxt_srandom; 
	}
	?>
	<!-- begin WP-CYCLE -->
	<div id="WP-CYCLE-<?php echo $wpcytxt_sname; ?>">
	<?php
	$sSql = "select wpcytxt_cid, wpcytxt_ctitle, wpcytxt_clink from ". WP_WPCYTXT_CONTENT ." where 1=1";
	$sSql = $sSql . " and (`wpcytxt_cstartdate` <= NOW() and `wpcytxt_cenddate` >= NOW())";
	$sSql = $sSql . " and wpcytxt_csetting='".strtoupper($setting)."'";
	$wpcycletxt = $wpdb->get_results($sSql);
	if ( ! empty($wpcycletxt) ) 
	{
		foreach ( $wpcycletxt as $text ) 
		{
			$wpcytxt_ctitle = stripslashes($text->wpcytxt_ctitle);
			$wpcytxt_clink = $text->wpcytxt_clink;
			?>
            <p><a target="<?php echo $wpcytxt_slink; ?>" href="<?php echo $wpcytxt_clink; ?>"><?php echo $wpcytxt_ctitle; ?></a></p>
			<?php 
		}
	}
	?>
	</div>
    <script type="text/javascript">
    $(function() {
	$('#WP-CYCLE-<?php echo strtoupper($setting); ?>').cycle({
		fx: '<?php echo @$wpcytxt_sdirection; ?>',
		speed: <?php echo @$wpcytxt_sspeed; ?>,
		timeout: <?php echo @$wpcytxt_stimeout; ?>
	});
	});
	</script>
    <!-- end WP-CYCLE -->
	<?php
}

function wpcytxt_install() 
{
	global $wpdb;
	if($wpdb->get_var("show tables like '". WP_WPCYTXT_SETTINGS . "'") != WP_WPCYTXT_SETTINGS) 
	{
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `". WP_WPCYTXT_SETTINGS . "` (
			  `wpcytxt_sid` int(11) NOT NULL auto_increment,
			  `wpcytxt_sname` VARCHAR( 10 ) NOT NULL,
			  `wpcytxt_slink` VARCHAR( 10 ) NOT NULL default '_blank',
			  `wpcytxt_sdirection` VARCHAR( 12 ) NOT NULL default 'scrollLeft',
			  `wpcytxt_sspeed` int(11) NOT NULL default '700',
			  `wpcytxt_stimeout` int(11) NOT NULL default '5000',
			  `wpcytxt_srandom` VARCHAR( 3 ) NOT NULL default 'YES',
			  `wpcytxt_sextra` VARCHAR( 100 ) NOT NULL,
			  PRIMARY KEY  (`wpcytxt_sid`) )
			");
		$iIns = "INSERT INTO `". WP_WPCYTXT_SETTINGS . "` (`wpcytxt_sname`)"; 
		
		for($i=1; $i<=10; $i++)
		{
			$sSql = $iIns . " VALUES ('SETTING".$i."')";
			$wpdb->query($sSql);
		}
	}
	if($wpdb->get_var("show tables like '". WP_WPCYTXT_CONTENT . "'") != WP_WPCYTXT_CONTENT) 
	{
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `". WP_WPCYTXT_CONTENT . "` (
			  `wpcytxt_cid` int(11) NOT NULL auto_increment,
			  `wpcytxt_ctitle` VARCHAR( 1024 ) NOT NULL,
			  `wpcytxt_clink` VARCHAR( 1024 ) NOT NULL default '#',
			  `wpcytxt_cstartdate` datetime NOT NULL default '2012-01-01 00:00:00',
			  `wpcytxt_cenddate` datetime NOT NULL default '2020-12-30 00:00:00',
			  `wpcytxt_csetting` VARCHAR( 12 ) NOT NULL,
			  PRIMARY KEY  (`wpcytxt_cid`) )
			");
		$iIns = "INSERT INTO `". WP_WPCYTXT_CONTENT . "` (`wpcytxt_ctitle`, `wpcytxt_csetting`)"; 
		
		for($i=1; $i<=6; $i++)
		{
			if($i >= 1 and $i<=2) { $j = 1; } elseif ($i >= 3 and $i<=4) { $j = 2; } else { $j = 3; }
			$sSql = $iIns . " VALUES ('Lorem Ipsum is simply dummy text of the printing industry ".$i.".', 'SETTING".$j."')";
			$wpdb->query($sSql);
		}
	}
	add_option('wpcytxt_title', "Announcement");
}

function wpcytxt_control() 
{
	echo '<p>Wp cycle text announcement</p>';
}

function wpcytxt_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('wpcytxt_title');
	echo $after_title;
	wpcytxt('setting1');
	echo $after_widget;
}

function wpcytxt_admin_options() 
{
	global $wpdb;
	include_once("content-management.php");
}

function wpcytxt_shortcode( $atts ) 
{
	global $wpdb;

	// [cycle-text setting="SETTING1"]	
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	$setting = $atts['setting'];
	
	$wpcycle = "";
	$sSql = "select wpcytxt_sid, wpcytxt_sname, wpcytxt_slink, wpcytxt_sdirection,";
	$sSql = $sSql . " wpcytxt_sspeed, wpcytxt_stimeout, wpcytxt_srandom from ". WP_WPCYTXT_SETTINGS ." where 1=1";
	$sSql = $sSql . " and wpcytxt_sname='".strtoupper($setting)."'";
	$wpcycletxt_settings = $wpdb->get_results($sSql);
	if ( ! empty($wpcycletxt_settings) ) 
	{
			$settings = $wpcycletxt_settings[0];
			$wpcytxt_sname = $settings->wpcytxt_sname; 
			$wpcytxt_slink = $settings->wpcytxt_slink; 
			$wpcytxt_sdirection = $settings->wpcytxt_sdirection; 
			$wpcytxt_sspeed = $settings->wpcytxt_sspeed; 
			$wpcytxt_stimeout = $settings->wpcytxt_stimeout; 
			$wpcytxt_srandom = $settings->wpcytxt_srandom; 
	}
	$wpcycle = $wpcycle . '<div id="WP-CYCLE-'.$wpcytxt_sname.'">';
	$sSql = "select wpcytxt_cid, wpcytxt_ctitle, wpcytxt_clink from ". WP_WPCYTXT_CONTENT ." where 1=1";
	$sSql = $sSql . " and (`wpcytxt_cstartdate` <= NOW() and `wpcytxt_cenddate` >= NOW())";
	$sSql = $sSql . " and wpcytxt_csetting='".strtoupper($setting)."'";
	$wpcycletxt = $wpdb->get_results($sSql);
	if ( ! empty($wpcycletxt) ) 
	{
		foreach ( $wpcycletxt as $text ) 
		{
			$wpcytxt_ctitle = stripslashes($text->wpcytxt_ctitle);
			$wpcytxt_clink = $text->wpcytxt_clink;
            $wpcycle = $wpcycle . '<p><a target="' . $wpcytxt_slink . '" href="' . $wpcytxt_clink . '">' . $wpcytxt_ctitle . '</a></p>';
		}
	}

	$wpcycle = $wpcycle . '</div>';
	$wpcycle = $wpcycle . '<script type="text/javascript">';
    $wpcycle = $wpcycle . '$(function() {';
	$wpcycle = $wpcycle . "$('#WP-CYCLE-".strtoupper($setting)."').cycle({fx: '".$wpcytxt_sdirection."',speed: " . $wpcytxt_sspeed . ",timeout: " . $wpcytxt_stimeout . "";
	$wpcycle = $wpcycle . '});';
	$wpcycle = $wpcycle . '});';
	$wpcycle = $wpcycle . '</script>';
	
	return $wpcycle;
}

function wpcytxt_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('wp-cycle-text', 'wp cycle text', 'wpcytxt_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('wp-cycle-text', array('wp cycle text', 'widgets'), 'wpcytxt_control');
	} 
}

function wpcytxt_add_to_menu() 
{
	if (is_admin()) 
	{
		add_options_page('Wp cycle text', 'Wp cycle text', 'manage_options', __FILE__, 'wpcytxt_admin_options' );
		add_options_page('Wp cycle text', '', 'manage_options', "wp-cycle-text-announcement/cycle-setting.php",'' );
	}
}

function wpcytxt_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'jquery-1.3.2.min', get_option('siteurl').'/wp-content/plugins/wp-cycle-text-announcement/js/jquery-1.3.2.min.js');
		wp_enqueue_script( 'jquery.cycle.all.min', get_option('siteurl').'/wp-content/plugins/wp-cycle-text-announcement/js/jquery.cycle.all.min.js');
		wp_enqueue_style( 'wp-cycle-text', get_option('siteurl').'/wp-content/plugins/wp-cycle-text-announcement/wp-cycle-text-style.css');
	}	
}

function wpcytxt_deactivation() 
{

}

add_shortcode( 'cycle-text', 'wpcytxt_shortcode' );
add_action('admin_menu', 'wpcytxt_add_to_menu');
add_action('wp_enqueue_scripts', 'wpcytxt_add_javascript_files');
add_action("plugins_loaded", "wpcytxt_init");
register_activation_hook(__FILE__, 'wpcytxt_install');
register_deactivation_hook(__FILE__, 'wpcytxt_deactivation');
?>