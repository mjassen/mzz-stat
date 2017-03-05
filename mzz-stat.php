<?php
/**
* Plugin Name: Mzz-stat
* Plugin URI: https://wordpress.org/plugins/mzz-stat/
* Description: A plugin that records statistics for a WordPress site.
* Version: 20170304.1423
* Author: Morgan Jassen
* Author URI: http://wieldlinux.com/
* License: GPLv2
*/

/*  Copyright 2017  Morgan Jassen  (email : morgan.jassen@gmail.com)

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

//你好 <- Utf-8 test -- two Utf-8 Chinese characters should appear at the beginning of this line.

/* Install the database table if it doesn't already exist.*/
register_activation_hook( __FILE__, 'mzz_mzzstat_install' );

function mzz_mzzstat_install() {
global $wpdb;


	$table_name = $wpdb->prefix . 'mzzstat_v2';

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		mzzstat_YYYY int(4) NOT NULL,
		mzzstat_month int(2) NOT NULL,
		uri varchar(255) NOT NULL,
		monthly_hits int(8) NOT NULL,
		hits_day_01 int(8) NOT NULL,
		hits_day_02 int(8) NOT NULL,
		hits_day_03 int(8) NOT NULL,
		hits_day_04 int(8) NOT NULL,
		hits_day_05 int(8) NOT NULL,
		hits_day_06 int(8) NOT NULL,
		hits_day_07 int(8) NOT NULL,
		hits_day_08 int(8) NOT NULL,
		hits_day_09 int(8) NOT NULL,
		hits_day_10 int(8) NOT NULL,
		hits_day_11 int(8) NOT NULL,
		hits_day_12 int(8) NOT NULL,
		hits_day_13 int(8) NOT NULL,
		hits_day_14 int(8) NOT NULL,
		hits_day_15 int(8) NOT NULL,
		hits_day_16 int(8) NOT NULL,
		hits_day_17 int(8) NOT NULL,
		hits_day_18 int(8) NOT NULL,
		hits_day_19 int(8) NOT NULL,
		hits_day_20 int(8) NOT NULL,
		hits_day_21 int(8) NOT NULL,
		hits_day_22 int(8) NOT NULL,
		hits_day_23 int(8) NOT NULL,
		hits_day_24 int(8) NOT NULL,
		hits_day_25 int(8) NOT NULL,
		hits_day_26 int(8) NOT NULL,
		hits_day_27 int(8) NOT NULL,
		hits_day_28 int(8) NOT NULL,
		hits_day_29 int(8) NOT NULL,
		hits_day_30 int(8) NOT NULL,
		hits_day_31 int(8) NOT NULL,
		UNIQUE KEY id (id),
		KEY mzzstat_YYYY (mzzstat_YYYY),
		KEY mzzstat_month (mzzstat_month),
		KEY uri (uri)
	);";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
	
}


//hook the function into the WordPress footer so it triggers every page load. 
add_action( 'wp_footer', 'mzz_include_mzzstat', 99 );
 
function mzz_include_mzzstat() {


	
	global $wpdb;


		$mzz_table_name = $wpdb->prefix . "mzzstat_v2";

		// Now, in format of YYYY-MM-DD HH:MM:SS
		$mj_mzz_base_date_time = current_time( 'mysql' );

		//Convert to a format of YYYY.
		//$mj_mzz_YYYYMMDD = date('Ymd', strtotime($mj_mzz_base_date_time)); 
		
		//Convert to a format of YYYY.
		$mj_mzz_YYYY = date('Y', strtotime($mj_mzz_base_date_time)); 
		
		//Convert to a format of [M]M.
		$mj_mzz_month = date('m', strtotime($mj_mzz_base_date_time)); 

		//Convert to a format of DD.
		$mj_mzz_DD = date('d', strtotime($mj_mzz_base_date_time)); 

		//Truncate uri to less than 255 chars and add ellipsis before saving it to the database.
		$mzz_server_request_uri = (strlen($_SERVER['REQUEST_URI']) > 255) ? substr($_SERVER['REQUEST_URI'],0,254).'…' : $_SERVER['REQUEST_URI'];


		//Is there already a record in the [xx_]mzzstat_v2 db table where the date is this YYYY & month and the uri is the currently requested uri?
		$mj_mzz_thismonth_uri_id = $wpdb->get_var('SELECT id FROM ' . $mzz_table_name . ' WHERE mzzstat_YYYY = ' . $mj_mzz_YYYY . ' AND mzzstat_month = ' . $mj_mzz_month . ' AND uri = \'' . $mzz_server_request_uri . '\'');

		if( $wpdb->num_rows == 0 ){ //There isn't already a record. Insert one.
			$wpdb->insert(
				$mzz_table_name, 
				array( 
					'mzzstat_YYYY' => $mj_mzz_YYYY,
					'mzzstat_month' => $mj_mzz_month,					
					'uri' => $mzz_server_request_uri,
					'monthly_hits' => 1,
					'hits_day_' . $mj_mzz_DD => 1
				)
			);
		}else{ //There is already a record. Update to increment its hit count.

			$wpdb->query('UPDATE ' . $mzz_table_name . ' SET monthly_hits = (monthly_hits + 1), hits_day_' . $mj_mzz_DD . ' = (hits_day_' . $mj_mzz_DD . ' + 1) WHERE id = ' . $mj_mzz_thismonth_uri_id);
		} //end else
	
} //end function mzz_include_mzzstat


// In the WordPress Dashboard under the Tools menu > Mzz-stat, add an Admin page. 
add_action('admin_menu', 'mzz_mzzstat_dashboard');

function mzz_mzzstat_dashboard() {
    add_management_page( 'Mzz-stat Admin Page', 'Mzz-stat', 'manage_options',
        'mzz_mzzstat_admin', 'mzz_mzzstat_admin_page' );
}


// Draw the Admin page. This page displays the stats.
function mzz_mzzstat_admin_page() {
    ?>
    <div class="wrap">
        <h2>Mzz-stat</h2>
    	<?php
	

	// Tell the function about the database object
	global $wpdb;
	
	// Tell the function the name of the mzzstat_v2 table
	$mzz_table_name = $wpdb->prefix . "mzzstat_v2";
	
	$mj_mzz_base_date = current_time( 'mysql' );

	//this string represents the html for the table showing the stats. It is build as we go, then at the end it is echoed to the Admin dashboard page.
	$mj_mzz_base_table_string = "";

	//select daily hit count for each day for each page. There will be one distinct row returned for each uri for each month.
	$mj_mzz_month_results = $wpdb->get_results("SELECT mzzstat_YYYY, mzzstat_month, uri, monthly_hits, hits_day_01, hits_day_02, hits_day_03, hits_day_04, hits_day_05, hits_day_06, hits_day_07, hits_day_08, hits_day_09, hits_day_10, hits_day_11, hits_day_12, hits_day_13, hits_day_14, hits_day_15, hits_day_16, hits_day_17, hits_day_18, hits_day_19, hits_day_20, hits_day_21, hits_day_22, hits_day_23, hits_day_24, hits_day_25, hits_day_26, hits_day_27, hits_day_28, hits_day_29, hits_day_30, hits_day_31 FROM $mzz_table_name ORDER BY mzzstat_YYYY DESC, mzzstat_month DESC, monthly_hits DESC");

	//select sum of monthly_hits for all months which is tally of all hits ever.
	$mzz_total_tally = $wpdb->get_var( "SELECT SUM(monthly_hits) FROM $mzz_table_name" );
	
	echo 'All-time total (Uri (page) Requests) hits: ' . $mzz_total_tally . '<br/><br/>';

	//start building the table
	$mj_mzz_base_table_string .= '<div style="font-size:10px;"><table border=1">';

	//continue to build the table - the table header row.
	$mj_mzz_base_table_string .= '<tr><th>YYYY-[M]M</th><th>uri</th><th>monthly<br/>hits</th><th>day<br/>01</th><th>day<br/>02</th><th>day<br/>03</th><th>day<br/>04</th><th>day<br/>05</th><th>day<br/>06</th><th>day<br/>07</th><th>day<br/>08</th><th>day<br/>09</th><th>day<br/>10</th><th>day<br/>11</th><th>day<br/>12</th><th>day<br/>13</th><th>day<br/>14</th><th>day<br/>15</th><th>day<br/>16</th><th>day<br/>17</th><th>day<br/>18</th><th>day<br/>19</th><th>day<br/>20</th><th>day<br/>21</th><th>day<br/>22</th><th>day<br/>23</th><th>day<br/>24</th><th>day<br/>25</th><th>day<br/>26</th><th>day<br/>27</th><th>day<br/>28</th><th>day<br/>29</th><th>day<br/>30</th><th>day<br/>31</th></tr>';
	

	
	
	
	foreach ( $mj_mzz_month_results as $mj_mzz_month_result ) //outer loop - loops through all the rows.
{
	
	$mj_mzz_base_table_string .=  '<tr>' . '<td>' . $mj_mzz_month_result->mzzstat_YYYY . '-' . $mj_mzz_month_result->mzzstat_month . '</td><td><div style="width:100px; word-wrap:break-word;">' . $mj_mzz_month_result->uri . '</div></td><td>' . $mj_mzz_month_result->monthly_hits . '</td>' ;

	
		for ( $counter = 1; $counter <= 31; $counter ++ ){ //inner loop -- loops through the current row's field values

		$temp = 'hits_day_' . str_pad($counter, 2, "0", STR_PAD_LEFT);
		
		//output the count of how many hits on that page for that day
		$mj_mzz_base_table_string .=  '<td>' . $mj_mzz_month_result->$temp . '</td>';
		} // end for ( $counter = 0; $counter <= 30; $counter ++ ){

	$mj_mzz_base_table_string .= '</tr>';



} //end foreach ( $mj_mzz_month_results as $mj_mzz_month_result )





	

	//finish building the table
	$mj_mzz_base_table_string .= '</table></div>';

	//echo the string that represents the html for the table. We built it as we went, now here at the end it is echoed to the Admin dashboard page. 
	echo $mj_mzz_base_table_string;


	?>
    </div>
    <?php

} // end function mzz_mzzstat_admin_page



?>
