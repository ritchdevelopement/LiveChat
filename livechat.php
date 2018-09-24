<?php
/*
Plugin Name: LiveChat
Description: Super duber hyper uber LiveChat
Version: 1.0
Author: Rami El Kudr
*/

if ( !function_exists( 'add_action' ) ) {
	echo 'NANANANA do not call me directly plsss';
	exit;
}

define( 'LIVECHAT_PLUGIN_ADMIN', plugin_dir_path( __FILE__ ) . 'admin/' );
define( 'LIVECHAT_PLUGIN_FRONTEND', plugin_dir_path( __FILE__ ) . 'frontend/' );
define( 'LIVECHAT_PLUGIN_INCLUDE', plugin_dir_path( __FILE__ ) . 'include/' );

register_activation_hook( __FILE__ , array( 'Livechat', 'plugin_activation' ) );
register_deactivation_hook( __FILE__ , array( 'Livechat', 'plugin_deactivation' ) );

include_once( LIVECHAT_PLUGIN_INCLUDE . 'class.livechat.php' );
include_once( LIVECHAT_PLUGIN_INCLUDE . 'class.livechat.dp_helper.php' );
include_once( LIVECHAT_PLUGIN_INCLUDE . 'livechat.functions.php' );
 
add_action( 'init', array( 'Livechat', 'init' ) );

/* Ajax hooks */
add_action( 'wp_ajax_nopriv_writeMessage', array('Livechat', 'writeMessage' ) );
add_action( 'wp_ajax_writeMessage', array('Livechat', 'writeMessage' ) );
add_action( 'wp_ajax_nopriv_readMessages',  array('Livechat', 'readMessages' ) );
add_action( 'wp_ajax_readMessages',  array('Livechat', 'readMessages' ) );
add_action( 'wp_ajax_clearDatabase',  array('Livechat', 'clearDatabase' ) );
add_action( 'wp_ajax_checkUserOnline',  array('Livechat', 'checkUserOnline' ) );
