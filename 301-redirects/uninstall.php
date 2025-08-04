<?php
//if uninstall not called from WordPress exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
  exit();
}

global $wpdb;
//phpcs:ignore because we are using a custom table for redirect rules
$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'ts_redirects'); //phpcs:ignore

delete_option('301_redirects_404_log');
