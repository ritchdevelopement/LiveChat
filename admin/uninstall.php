<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
$option_name = 'livechat_options';
 
delete_option($option_name);

global $wpdb;
$table_name = $wpdb->prefix . "livechat"; 
$wpdb->query("DROP TABLE IF EXISTS $table_name");