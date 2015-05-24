<?php
/*
Plugin Name: Wp cycle text announcement
Plugin URI: http://www.gopiplus.com/work/2012/04/07/wp-cycle-text-announcement-wordpress-plugin/
Description: Wp cycle text plugin is to show the text news with cycle jQuery. Display one news at a time and cycle the remaining in the mentioned location.
Author: Gopi Ramasamy
Version: 6.5
Author URI: http://www.gopiplus.com/work/2012/04/07/wp-cycle-text-announcement-wordpress-plugin/
Donate link: http://www.gopiplus.com/work/2012/04/07/wp-cycle-text-announcement-wordpress-plugin/
Tags: Cycle, text, announcement, wordpress, plugin
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb, $wp_version;
define("WP_WPCYTXT_SETTINGS", $wpdb->prefix . "cycletext_settings");
define("WP_WPCYTXT_CONTENT", $wpdb->prefix . "cycletext_content");
define('Wp_wpcytxt_FAV', 'http://www.gopiplus.com/work/2012/04/07/wp-cycle-text-announcement-wordpress-plugin/');

if ( ! defined( 'WP_wpcytxt_BASENAME' ) )
	define( 'WP_wpcytxt_BASENAME', plugin_basename( __FILE__ ) );
	
if ( ! defined( 'WP_wpcytxt_PLUGIN_NAME' ) )
	define( 'WP_wpcytxt_PLUGIN_NAME', trim( dirname( WP_wpcytxt_BASENAME ), '/' ) );
	
if ( ! defined( 'WP_wpcytxt_PLUGIN_URL' ) )
	define( 'WP_wpcytxt_PLUGIN_URL', WP_PLUGIN_URL . '/' . WP_wpcytxt_PLUGIN_NAME );
	
if ( ! defined( 'WP_wpcytxt_ADMIN_URL' ) )
	define( 'WP_wpcytxt_ADMIN_URL', get_option('siteurl') . '/wp-admin/options-general.php?page=wp-cycle-text-announcement' );

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
		jQuery(function() {
		jQuery('#WP-CYCLE-<?php echo strtoupper($setting); ?>').cycle({
			fx: '<?php echo $wpcytxt_sdirection; ?>',
			speed: <?php echo $wpcytxt_sspeed; ?>,
			timeout: <?php echo $wpcytxt_stimeout; ?>
		});
		});
		</script>
		<!-- end WP-CYCLE -->
		<?php
	}
	else
	{
		_e('No records found', 'wp-cycle-text');
	}
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
			  PRIMARY KEY  (`wpcytxt_sid`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
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
			  PRIMARY KEY  (`wpcytxt_cid`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
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
	echo '<p><b>';
	_e('Wp cycle text announcement', 'wp-cycle-text');
	echo '.</b> ';
	_e('Check official website for more information', 'wp-cycle-text');
	?> <a target="_blank" href="<?php echo Wp_wpcytxt_FAV; ?>"><?php _e('click here', 'wp-cycle-text'); ?></a></p><?php
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
	//include_once("content-management.php");
	
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'add':
			include('pages/content-add.php');
			break;
		case 'edit':
			include('pages/content-edit.php');
			break;
		case 'addcycle':
			include('pages/cycle-setting-add.php');
			break;
		case 'editcycle':
			include('pages/cycle-setting-edit.php');
			break;
		case 'showcycle':
			include('pages/cycle-setting-show.php');
			break;
		default:
			include('pages/content-show.php');
			break;
	}
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
			$wpcycle = $wpcycle . 'jQuery(function() {';
			$wpcycle = $wpcycle . "jQuery('#WP-CYCLE-".strtoupper($setting)."').cycle({fx: '".$wpcytxt_sdirection."',speed: " . $wpcytxt_sspeed . ",timeout: " . $wpcytxt_stimeout . "";
			$wpcycle = $wpcycle . '});';
			$wpcycle = $wpcycle . '});';
			$wpcycle = $wpcycle . '</script>';
	}
	else
	{
		$wpcycle = __('No records found', 'wp-cycle-text');
	}
	return $wpcycle;
}

function wpcytxt_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('wp-cycle-text', __('Wp cycle text', 'wp-cycle-text'), 'wpcytxt_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('wp-cycle-text', array( __('Wp cycle text', 'wp-cycle-text'), 'widgets'), 'wpcytxt_control');
	} 
}

function wpcytxt_add_to_menu() 
{
	if (is_admin()) 
	{
		add_options_page( __('Wp cycle text', 'wp-cycle-text'), __('Wp cycle text', 'wp-cycle-text'), 
								'manage_options', 'wp-cycle-text-announcement', 'wpcytxt_admin_options' );
	}
}

function wpcytxt_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'jquery.cycle.all.latest', WP_wpcytxt_PLUGIN_URL.'/js/jquery.cycle.all.latest.js');
		wp_enqueue_style( 'wp-cycle-text', WP_wpcytxt_PLUGIN_URL.'/wp-cycle-text-style.css');
	}	
}

function wpcytxt_deactivation() 
{
	// No action required.
}

function wpcytxt_textdomain() 
{
	  load_plugin_textdomain( 'wp-cycle-text', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action('plugins_loaded', 'wpcytxt_textdomain');
add_shortcode( 'cycle-text', 'wpcytxt_shortcode' );
add_action('admin_menu', 'wpcytxt_add_to_menu');
add_action('wp_enqueue_scripts', 'wpcytxt_add_javascript_files');
add_action("plugins_loaded", "wpcytxt_init");
register_activation_hook(__FILE__, 'wpcytxt_install');
register_deactivation_hook(__FILE__, 'wpcytxt_deactivation');
?>