<?php
/**
* Plugin Name: Mzz-stat
* Plugin URI: https://github.com/mjassen/mzz-stat
* Description: A plugin that records statistics for a WordPress site.
* Version: 
* Author: mjassen
* Author URI: http://wieldlinux.com/
* License: MIT
*/



/*

CREATE TABLE `wp_mzzstat` (
 `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
 `mzzstat_uri` text NOT NULL,
 `mzzstat_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
)

*/

add_action( 'wp_footer', 'mzz_include_mzzstat', 99 );


//the mzz_include_mzzstat() function includes this code in the WordPress footer. thus any time any page on the WordPress site is browsed, the function is executed. the mzz_include_mzzstat() function inserts into the mzzstat (or wp_mzzstat or xx_mzzstat) the uri visited, and the date and time.
 
function mzz_include_mzzstat() {

global $wpdb;

$mzz_table_name = $wpdb->prefix . "mzzstat";

$mzz_server_request_uri = $_SERVER['REQUEST_URI'];

$wpdb->insert( 
	$mzz_table_name, 
	array( 
		'mzzstat_uri' => $mzz_server_request_uri, 
		'mzzstat_date' => date("Y-m-d H:i:s")
	) 
);
	
}


//adds the mzz_shortcode_mzzstat function to WordPress
add_shortcode( 'mzz-stat', 'mzz_shortcode_mzzstat' );

//the mzz_shortcode_mzzstat function displays the total hits to any page on the website, on any post or page where the [mzz-stat] is.
function mzz_shortcode_mzzstat() {

global $wpdb;

$mzz_table_name = $wpdb->prefix . "mzzstat";

$mzz_total_tally = $wpdb->get_var( "SELECT COUNT(id) FROM $mzz_table_name" );

return 'Total page hits: ' . $mzz_total_tally;
        
}

?>
