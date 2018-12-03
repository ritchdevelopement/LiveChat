<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
$option_name = 'livechat_options';
 
delete_option($option_name);

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}livechat");