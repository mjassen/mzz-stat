<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

function mzzstat_delete_plugin() {
	global $wpdb;

	$table_name = $wpdb->prefix . "mzzstat_v2";

	$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}

mzzstat_delete_plugin();

?>
