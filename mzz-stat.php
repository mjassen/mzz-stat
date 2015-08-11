<?php
/**
* Plugin Name: Mzz-stat
* Plugin URI: https://github.com/mjassen/mzz-stat
* Description: A plugin that records statistics for a WordPress site.
* Version: 0.0.1
* Author: mjassen
* Author URI: http://wieldlinux.com/
* License: MIT
*/



/*

CREATE TABLE `mzzstat` (
 `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
 `mzzstat_uri` text NOT NULL,
 `mzzstat_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci




*/

add_action( 'wp_footer', 'mzz_include_mzzstat', 99 );
function mzz_include_mzzstat() {

$mzz_server_request_uri = $_SERVER['REQUEST_URI'];

$mzz_mysqli_object = mysqli_connect("localhost", "boston", "redsox", "werdpresdb");

$mzz_query_statement = "INSERT INTO mzzstat (mzzstat_uri, mzzstat_date) VALUES ('$mzz_server_request_uri', now())";

$mzz_finished_query = mysqli_query($mzz_mysqli_object, $mzz_query_statement) or die(mysqli_error($mzz_mysqli_object));

mysqli_close($mzz_mysqli_object);
	
}
?>
