<?php

function wpkbase_init() {
	if( !session_id() ) {
		session_start();
	}
}

function wpkbase_session_end() {
	session_destroy();
}

function wpkbase_frontend_init() {
	// Basic Frontend Init
	require WPKBASE_PATH . 'includes' . DS  . 'wpkbase-frontend.php';
}

function wpkbase_admin_init() {
	// Basic Admin Init
	require WPKBASE_PATH . 'includes' . DS . 'wpkbase-admin.php';
}

function wpkbase_enqueue_styles() {
	wp_enqueue_style( 'basic-style', WPKBASE_URL . '/css/style.css' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-droppable' );
	wp_enqueue_script( 'jquery-effects-core' );
	wp_enqueue_script( 'jquery-effects-slide' );
}

function wpkbase_setmsg( $type = 'message', $text = '' ) {
	$messages = (array)@$_SESSION[ 'messages' ];
	$array = array( 'type' => $type, 'text' => $text );
	$messages[] = $array;
	$_SESSION[ 'messages' ] = $messages;
}

function wpkbase_getmsg( ) {
	$messages = (array)@$_SESSION[ 'messages' ];
	@$_SESSION[ 'messages' ] = array();
	return $messages;
}