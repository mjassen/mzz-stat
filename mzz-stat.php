<?php
/**
* Plugin Name: Mzz-stat
* Plugin URI: https://github.com/mjassen/mzz-stat
* Description: A plugin that records statistics for a WordPress site.
* Version: 20151215.2133
* Author: Morgan Jassen
* Author URI: http://wieldlinux.com/
* License: GPLv2
*/

/*  Copyright 2015  Morgan Jassen  (email : morgan.jassen@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, see <http://www.gnu.org/licenses/>
*/

/* Install database table if it doesn't already exist*/
register_activation_hook( __FILE__, 'mzz_mzzstat_install' );

function mzz_mzzstat_install() {
global $wpdb;


	$table_name = $wpdb->prefix . 'mzzstat';

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		mzzstat_uri text NOT NULL,
		mzzstat_date datetime NOT NULL,
		UNIQUE KEY id (id)
	);";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}




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





// Add an entry for our Mzz-stat admin page to the tools menu
add_action('admin_menu', 'mzz_mzzstat_dashboard');
function mzz_mzzstat_dashboard() {
    add_management_page( 'Mzz-stat Admin Page', 'Mzz-stat', 'manage_options',
        'mzz_mzzstat_admin', 'mzz_mzzstat_admin_page' );
}


// Draw the Mzz-stat admin page. The mzz_mzzstat_admin_page function contains the Mzz-stat Admin page, which is used to display the stats. One of the main stats is the total hits to any page on the website.
function mzz_mzzstat_admin_page() {
    ?>
    <div class="wrap">
        <h2>Mzz-stat</h2>
    	<?php
	// Find tally of total page hits for all WordPress site pages from the database
	global $wpdb;

	$mzz_table_name = $wpdb->prefix . "mzzstat";

	$mzz_total_tally = $wpdb->get_var( "SELECT COUNT(id) FROM $mzz_table_name" );

	echo 'Total website hits: ' . $mzz_total_tally . '<br/><br/>';





	$mj_mzz_results = $wpdb->get_results( 
	"SELECT mzzstat_date, mzzstat_uri FROM $mzz_table_name WHERE mzzstat_date > DATE_SUB(now(),INTERVAL 1 MONTH) ORDER BY mzzstat_date DESC LIMIT 0,20");

	echo 'Details of the 20 most recent hits:<br/>';

	foreach ( $mj_mzz_results as $mj_mzz_result ) 
	{
		echo $mj_mzz_result->mzzstat_date . ' | ' . $mj_mzz_result->mzzstat_uri . '<br/>';
	}


	?>
    </div>
    <?php

}

?>
