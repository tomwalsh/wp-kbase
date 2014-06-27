<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

class WPKBASE_Admin {
	
	function __construct() {
		require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'base.php';
		add_action( 'plugins_loaded', array( $this, 'update_db_check' ), 1 );
		add_action( 'admin_menu', array( $this, 'register_menu' ), 5 );
	}
	
	function register_menu() {
		add_menu_page( __( 'WP Knowledge Base', 'wpkbase' ), __( 'Knowledge Base', 'wpkbase' ), 'manage_options', 'wpkbase_manage', array( $this, 'manage_page' ), WPKBASE_URL . 'images/express-icon.png', 58 );
		add_submenu_page( 'wpkbase_manage', __( 'Categories', 'wpkbase' ), __( 'Categories', 'wpkbase' ), 'manage_options', 'wpkbase_categories', array( $this, 'categories_page' ) );
		add_submenu_page( 'wpkbase_manage', __( 'Articles', 'wpkbase' ), __( 'Articles', 'wpkbase' ), 'manage_options', 'wpkbase_articles', array( $this, 'articles_page' ) );
		add_submenu_page( 'wpkbase_manage', __( 'Settings', 'wpkbase' ), __( 'Settings', 'wpkbase' ), 'manage_options', 'wpkbase_settings', array( $this, 'settings_page' ) );
		
		global $submenu;
		if ( isset( $submenu['wpkbase_manage'] ) )
			$submenu['wpkbase_manage'][0][0] = __( 'Manage', 'wpkbase' );
	}
	
	function manage_page() {
		require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'manage.php';
	}

	function settings_page() {
		require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'settings.php';
	}
	
	function categories_page() {
		require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'categories.php';
	}
	
	function articles_page() {
		require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'articles.php';
	}
	
	function update_db_check() {
		if( get_site_option( 'kbase_db_version' ) != WPKBASE_DB_VERSION ) {
			$this->db_install();
		}
	}
	
	function db_install() {
		global $wpdb;
		$prefix = $wpdb->prefix;
		require_once ABSPATH . 'wp-admin' . DS . 'includes' . DS . 'upgrade.php';
		
		$sql = "CREATE TABLE IF NOT EXISTS {$prefix}kbasecats (
		`id` int(10) NOT NULL AUTO_INCREMENT,
		`parentid` int(10) NOT NULL DEFAULT '0',
  		`name` text COLLATE utf8_bin NOT NULL,
 		`description` text COLLATE utf8_bin NOT NULL,
 		`order` int(3) NOT NULL DEFAULT '0',
  		PRIMARY KEY (`id`),
  		KEY `parentid` (`parentid`),
  		KEY `name` (`name`(64))
  		);";
		
		dbDelta( $sql );
		
		$sql = "CREATE TABLE IF NOT EXISTS {$prefix}kbasearticles (
		`id` int(10) NOT NULL AUTO_INCREMENT,
  		`title` text COLLATE utf8_bin NOT NULL,
  		`article` text COLLATE utf8_bin NOT NULL,
  		`views` int(10) NOT NULL DEFAULT '0',
  		`useful` int(10) NOT NULL DEFAULT '0',
  		`votes` int(10) NOT NULL DEFAULT '0',
  		`order` int(3) NOT NULL,
  		`parentid` int(10) NOT NULL,
  		PRIMARY KEY (`id`)
		);";
		
		dbDelta( $sql );
		
		$sql = "CREATE TABLE IF NOT EXISTS {$prefix}kbaselinks (
  		`categoryid` int(10) NOT NULL,
  		`articleid` int(10) NOT NULL,
		`order` int(3) NOT NULL DEFAULT '0'
		);";
		
		dbDelta( $sql );
		
		add_option( 'kbase_db_version', WPKBASE_DB_VERSION );
		
	}
}

global $wpkbase_admin;
$wpkbase = new WPKBASE_Admin();