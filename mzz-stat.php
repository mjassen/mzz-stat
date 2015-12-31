<?php
/**
* Plugin Name: Mzz-stat
* Plugin URI: https://github.com/mjassen/mzz-stat
* Description: A plugin that records statistics for a WordPress site.
* Version: 20151230.2238
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

//你好 <- Utf-8 file encoding integrity test -- two Chinese characters for hello/"ni hao" should appear at the beginning of this line.

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

//how many days is the reporting set to. (the hits per day per page reporting)
//$mj_mzz_reporting_days = 5;

$mzz_table_name = $wpdb->prefix . "mzzstat";

$mzz_server_request_uri = $_SERVER['REQUEST_URI'];

//This fires each time someone visits any page in the WordPress website. (because it is added into the wp_footer action which is triggered any time someone visits any page on the website) It inserts the requested uri (page) and the date+time into the mzzstat table in the WordPress database.
$wpdb->insert( 
	$mzz_table_name, 
	array( 
		'mzzstat_uri' => $mzz_server_request_uri, 
		'mzzstat_date' => date("Y-m-d H:i:s")
	) 
);
	
}





// Add an entry for our Mzz-stat admin page to the tools menu. So It adds an entry in the WordPress Dashboard under the Tools menu > Mzz-stat
add_action('admin_menu', 'mzz_mzzstat_dashboard');
function mzz_mzzstat_dashboard() {
    add_management_page( 'Mzz-stat Admin Page', 'Mzz-stat', 'manage_options',
        'mzz_mzzstat_admin', 'mzz_mzzstat_admin_page' );
}


// Draw the Mzz-stat admin page. This page shows when one browses to it via the WP Admin Dashboard > Tools > Mzz-stat. The mzz_mzzstat_admin_page function contains the Mzz-stat Admin page, which is used to display the stats. One of the main stats is the total hits to any page on the website.
function mzz_mzzstat_admin_page() {
    ?>
    <div class="wrap">
        <h2>Mzz-stat</h2>
    	<?php
	

	// need to tell the plugin where to look for the database by calling global $wpdb here
	global $wpdb;

	//this $mzz_table_name variable will hold the reference to the plugin's table's name
	$mzz_table_name = $wpdb->prefix . "mzzstat";


	//$mj_mzz_base_date is essentially now(). But we only want to call it once at the beginning of each script load so that we can base all calculations off it and so the calculations will all be based off the same time of now() even if they are run in multiple queries.
	$mj_mzz_base_date = date("Y-m-d H:i:s");


	//select all records older than 24-hours-from-now (so that means all records ever recorded)
	//$mzz_total_tally = $wpdb->get_var( "SELECT COUNT(id) FROM $mzz_table_name WHERE mzzstat_date < DATE_ADD('" . $mj_mzz_base_date . "', INTERVAL 86400 SECOND)" );
	$mzz_total_tally = $wpdb->get_var( "SELECT COUNT(id) FROM $mzz_table_name WHERE mzzstat_date < DATE_ADD('" . $mj_mzz_base_date . "', INTERVAL 1 DAY)" );


	$mzz_oldest_hit = '';
	//select the date/time of the oldest hit in the database
	$mzz_oldest_hit = $wpdb->get_var( "SELECT mzzstat_date FROM $mzz_table_name ORDER BY mzzstat_date ASC LIMIT 0,1" );

	echo 'All-time (since ' . date("Y-m-d", strtotime($mzz_oldest_hit)) . ') total (Uri (page) Requests) hits:  <br/>' . $mzz_total_tally . '<br/><br/>';

	$mzz_monthly_tally = 0;

	//query to find total count of all pages (uris requested) hit in the last n days
	$mzz_monthly_tally = $wpdb->get_var( "SELECT COUNT(id) FROM $mzz_table_name WHERE mzzstat_date BETWEEN DATE_SUB(NOW(), INTERVAL 5 DAY) AND DATE_ADD(NOW(), INTERVAL 1 DAY)" );

	if ($mzz_monthly_tally == 0) {
	echo '<strong>Note: If no visitors have yet visited the site, then this statistics area would be 0 and/or blank! To put at least one visit, you could browse to any page on the website, such as the home page, at least once, then come back and refresh this page.</strong><br/><br/>';
	}

	echo 'Total (Uri (page) Requests) hits this past 5 days: <br/>' . $mzz_monthly_tally . '<br/><br/>';

	//start section where we calculate and output the table of (Uri (page) Requests) hits per Uri, per day, for the last n days (n was 30 but changed it to 5)
	echo 'Table of (Uri (page) Requests) hits per Uri, per day, for the last 5 days:<br/><br/>';


//start code to do an n-day array of all pages (uris requested) hit in those thirty days, and along with the count of how many hits per day during those thirty days. in a matrix with the date listed along the top and the page/uri listed along the top.


//this string represents the html for the table. It is build as we go, then at the end it is echoed to the Admin dashboard page. (In the WP Admin dashboard under Tools menu-> Mzz-stat
$mj_mzz_base_table_string = "";


//select all records between one-month-before-now and 24-hours-from-now (so essentially now allowing for up to 24 hours time zone difference). Group by uri so that there will be one distinct row returned each uri from that time period, and the aggregate function count() will aggregate the number of hits for each uri for us.
$mj_mzz_month_results = $wpdb->get_results("SELECT COUNT(id) AS monthly_hit_count, mzzstat_uri FROM $mzz_table_name WHERE mzzstat_date BETWEEN DATE_SUB('" . $mj_mzz_base_date . "', INTERVAL 5 DAY) AND DATE_ADD('" . $mj_mzz_base_date . "', INTERVAL 1 DAY) GROUP BY mzzstat_uri ORDER BY COUNT(id) DESC");


//start building the table
$mj_mzz_base_table_string .= '<table border=1">';

//continue to build the table - the table header row.
$mj_mzz_base_table_string .= '<tr><th>Uri (Page) requested</th><th>n-day Total</th>';


//for loop loops from 0 to n representing the past n days and outputs the month+day of each day in the table header. uses today's date as the base for the date arithmetic for the day, and then uses a mktime() on that so that it can be exploded and the day portion of the date can be subtracted from(by one each day), and then uses the date() function on that.
for ( $counter = 0; $counter <= 5; $counter ++ ){

//output today's date plus one day, minus $count days. (just the month and day of today's date actually)
$mj_mzz_base_table_string .= '<th>' . date("M d", mktime(date("H", strtotime ($mj_mzz_base_date)), date("i", strtotime ($mj_mzz_base_date)), date("s", strtotime ($mj_mzz_base_date)), date("m", strtotime ($mj_mzz_base_date)), (date("d", strtotime ($mj_mzz_base_date)) +1 -$counter), date("Y", strtotime ($mj_mzz_base_date)))) . '</th>';

} // end for ( $counter = 0; $counter <= 5; $counter ++ )


//end building the table header row
$mj_mzz_base_table_string .= '</tr>';



foreach ( $mj_mzz_month_results as $mj_mzz_month_result ) //loop through each distinct uri. as we go we will have another loop which loops through the n days for each uri.
{
	
	$mj_mzz_base_table_string .=  '<tr>' . '<td>' . $mj_mzz_month_result->mzzstat_uri . '</td><td>' . $mj_mzz_month_result->monthly_hit_count . '</td>' ;

		
		for ( $counter = 0; $counter <= 5; $counter ++ ){ // loops through the n days for each uri. For each day, output the count of how many hits on that page for that day

			$mj_mzz_uri_day_results = $wpdb->get_var("SELECT COUNT(id) AS daily_page_hit_count FROM $mzz_table_name WHERE ( mzzstat_date BETWEEN DATE_SUB('" . $mj_mzz_base_date . "', INTERVAL " . ($counter+1) . " DAY) AND DATE_SUB('" . $mj_mzz_base_date . "', INTERVAL " . $counter . " DAY) ) AND ( mzzstat_uri = '" . $mj_mzz_month_result->mzzstat_uri . "' )");

		//output the count of how many hits on that page for that day
		$mj_mzz_base_table_string .=  '<td>' . $mj_mzz_uri_day_results . '</td>';
		} // end for ( $counter = 0; $counter <= 5; $counter ++ ){ // loops through the n days for each uri.

	$mj_mzz_base_table_string .= '</tr>';



} //end foreach ( $mj_mzz_month_results as $mj_mzz_month_result ) //loop through each distinct uri.


//finish building the table
$mj_mzz_base_table_string .= '</table>';

//echo the string that represents the html for the table. We built it as we went, now here at the end it is echoed to the Admin dashboard page. 
echo $mj_mzz_base_table_string;



	?>
    </div>
    <?php

}

?>
