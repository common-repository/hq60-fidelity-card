<?php

/*
Plugin Name: HQ60 - Plugin per software fidelity card
Description: Plugin per il software fidelity card
URI: http://www.tresrl.it
Author: TRe Technology And Research S.r.l
Author URI: http://www.tresrl.it
Version: 1.8
License: GPL-2.0+
*/

if ( ! defined( 'ABSPATH' ) ) exit;

/* AUTOLOADER */
/* Inspired by: https://www.smashingmagazine.com/2015/05/how-to-use-autoloading-and-a-plugin-container-in-wordpress-plugins/
 * 
 */

spl_autoload_register( 'hq60fidelitycard_autoloader' );

function hq60fidelitycard_autoloader( $class_name ) {
	
	/*
	 * Check if class has our prefix.
	 * Thank to Facebook PHP SDK for the hint.
	 * Need to be the SAME name of folder
	 */
	$prefix = 'Hq60fidelitycard\\';
	
    // does the class use the namespace prefix?
    $len = strlen($prefix);
	
	if (strncmp($prefix, $class_name, $len) !== 0) {
        	
        // no, move to the next registered autoloader
        return;
		
    } else {
		
		$classes_dir = realpath ( plugin_dir_path ( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
	
		$class_file = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name ) . '.php';
	
		require_once $classes_dir . $class_file;
		
	}
	
}


/**
 * The base dir of the plugin.
 * Returns the absolute path.
 * E.g. /var/docs/html/public/wp-content/plugins/pluginname
 * 
 * @var string
 * 
 * 
 */

$base_dir = plugin_dir_path( __FILE__ );
$plugin_base_name = plugin_basename(__FILE__);
$plugin_dir_url = plugin_dir_url(__FILE__);


require $base_dir.'src/Hq60/autoload.php';

if ( is_admin() ) {
	
		
	if ( ! isset ( $admin_hq60fidelitycardplugin ) ) {

		$admin_hq60fidelitycardplugin = new \Hq60fidelitycard\Admin\Admin($base_dir , $plugin_base_name , $plugin_dir_url);
		
	}
	
}

if ( ! isset ( $frontend_hq60fidelitycardplugin ) ) {
	
	$frontend_hq60fidelitycardplugin = new \Hq60fidelitycard\Frontend\Frontend($base_dir , $plugin_base_name , $plugin_dir_url);
	
}