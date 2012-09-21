<?php
/*
Plugin Name: Run Statistics
Plugin URI: http://dragly.org/source
Description: Stores run statistics for scientific analysis and creates beautiful plots
Version: 0.0.1
Author: Svenn-Arne Dragly
Author URI: http://dragly.org/
License: GNU GPLv3
*/

global $run_statistics_db_version;
$run_statistics_db_version = "0.0.1";

function run_statistics_install () {
    global $wpdb;
    global $run_statistics_db_version;

    $table_name = $wpdb->prefix . "run_statistics_runs";
    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
            `id` mediumint(9) NOT NULL AUTO_INCREMENT,
            `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            `runid` varchar(255) NOT NULL,
            `state` varchar(255) NOT NULL,
            `project` varchar(255) NOT NULL,
            UNIQUE KEY `id` (`id`)
            );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    add_option("run_statistics_db_version", $run_statistics_db_version);
}

register_activation_hook(__FILE__,'run_statistics_install');
?>