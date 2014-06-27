<?php
/*
	Plugin Name: Express Knowledge Base
	Plugin URI: http://expresshosting.net/products/worpdress-kbase/
	Description: Plugin that allows you to create and manage a knowledge base of articles via the Wordpress CMS. You can create categories, assign articles to those categories, and order the articles to help guide your customers to the information they need.
	Author: Tom Walsh
	Version: 1.0
	Author URI: http://expresshosting.net/
 */

if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

if( !defined( 'DS' ) ) {
	define( 'DS', DIRECTORY_SEPARATOR );
}

if( !defined( 'WPKBASE_PATH' ) ) {
	define( 'WPKBASE_PATH', plugin_dir_path( __FILE__ ) );
}
if( !defined( 'WPKBASE_URL' ) ) {
	define( 'WPKBASE_URL', plugin_dir_url( __FILE__ ) );
}

if ( version_compare( PHP_VERSION, '5.2', '<' ) ) {
	if ( is_admin() ) {
		require_once ABSPATH . DS . 'wp-admin' . DS . 'includes' . DS . 'plugin.php';
		deactivate_plugins( __FILE__ );
		wp_die( __( 'WordPress KBase requires PHP 5.2 or higher and WordPress 3.2 and higher. The plugin has now been disabled.', 'wp-kbase' ) );
	} else {
		return;
	}
}

define( 'WPKBASE_DB_VERSION', '1.0' );
define( 'WPKBASE_VERSION', '1.0.0' );

require WPKBASE_PATH . 'includes' . DS . 'functions.php';

function wpkbase_frontend_init() {
	// Basic Frontend Init
	require WPKBASE_PATH . 'includes' . DS  . 'wpkbase-frontend.php';
}

function wpkbase_admin_init() {
	// Basic Admin Init
	require WPKBASE_PATH . 'includes' . DS . 'wpkbase-admin.php';
}


if( is_admin() ) {
	add_action( 'plugins_loaded', 'wpkbase_admin_init', 11 );
	add_action( 'admin_enqueue_scripts', 'wpkbase_enqueue_admin' );
} else {
	add_action( 'plugins_loaded', 'wpkbase_frontend_init', 11 );
}