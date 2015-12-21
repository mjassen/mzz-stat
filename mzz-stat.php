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

	//select all records older than 24-hours-from-now
	$mzz_total_tally = $wpdb->get_var( "SELECT COUNT(id) FROM $mzz_table_name WHERE mzzstat_date < DATE_ADD(NOW(), INTERVAL 86400 SECOND)" );

	echo 'Total website hits (All time): ' . $mzz_total_tally . '<br/><br/>';




	echo '-------------------------------------------<br/><br/>';

		//select all records between one-month-before-now and 24-hours-from-now. Group by uri so that there will be one distinct row returned each uri from that time period, and the aggregate function count() will aggregate the number of hits for each uri for us.
	$mj_mzz_results = $wpdb->get_results("SELECT COUNT(id) AS monthly_hit_count, mzzstat_uri FROM $mzz_table_name WHERE mzzstat_date BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND DATE_ADD(NOW(), INTERVAL 86400 SECOND) GROUP BY mzzstat_uri ORDER BY COUNT(id) DESC");


	echo 'Hit count per page, for the last month:<br/>';
	echo 'Hits | URI<br/>';

	//loop through each 
	foreach ( $mj_mzz_results as $mj_mzz_result ) 
	{
		echo $mj_mzz_result->monthly_hit_count . ' | ' . $mj_mzz_result->mzzstat_uri . '<br/>';
	}

	$mzz_monthly_tally = $wpdb->get_var( "SELECT COUNT(id) FROM $mzz_table_name WHERE mzzstat_date BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND DATE_ADD(NOW(), INTERVAL 86400 SECOND)" );

	echo $mzz_monthly_tally . ' <-Total hits this month<br/><br/>';


	?>
    </div>
    <?php

}

?>
