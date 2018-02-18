<?php
/*
Plugin Name: Recruitly Wordpress Plugin
Plugin URI: https://recruitly.io
Description: Recruitly job board integration.
Version: 1.0.0
Author: Recruit.cool
Author URI: https://recruitly.io
License: GNU GENERAL PUBLIC LICENSE
*/
register_activation_hook(__FILE__, 'activate_recruitly_wordpress_plugin');
register_deactivation_hook(__FILE__, 'deactivate_recruitly_wordpress_plugin');

/**
 * Include dependencies
 */
include( plugin_dir_path( __FILE__ ) . 'admin/includes/menus.php');
include( plugin_dir_path( __FILE__ ) . 'admin/includes/customposttypes.php');
include( plugin_dir_path( __FILE__ ) . 'admin/includes/filters.php');
include( plugin_dir_path( __FILE__ ) . 'admin/includes/shortcodes.php');
include( plugin_dir_path( __FILE__ ) . 'admin/includes/taxonomies.php');
include( plugin_dir_path( __FILE__ ) . 'admin/settings.php');
include( plugin_dir_path( __FILE__ ) . 'admin/dataloader.php');

function activate_recruitly_wordpress_plugin()
{
	delete_option('recruitly_apiserver');
	delete_option('recruitly_apikey');
	recruitly_wordpress_truncate_post_type();
}

function deactivate_recruitly_wordpress_plugin()
{
	delete_option('recruitly_apiserver');
	delete_option('recruitly_apikey');
	recruitly_wordpress_truncate_post_type();
	if ( isset( $wp_post_types[ 'recruitlyjobs' ] ) ) {
		unset( $wp_post_types[ 'recruitlyjobs'  ] );
	}
}