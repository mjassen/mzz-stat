=== mzz-stat ===
Contributors: mjjojo
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

==Changelog==

= 20151224.2249 =
* 24 December 2015 by mjjojo(mjassen)
* Changed what data is seen in the Admin page. Now it is All-time hits, last-30-days hits, and then a big table/matrix of hits per page per day for past 30 days.
* added more thorough code comments.

= 20151220.2130 =
* 20 December 2015 by mjjojo(mjassen)
* Put a patch/workaround in the MySQL query code to allow for apparent time zone discrepancy.

= 20151220.0953 =
* 20 December 2015 by mjjojo(mjassen)
* Changed what info is shown in the dashboard. Now shows page hits count in the last month for each page that was requested.

= 20151215.2133 =
* 15 December 2015 by mjjojo(mjassen)
* Added more info to the dashboard. added top 20 latest hits data displayed.

= 20151207.2118 =
* 07 December 2015 by mjjojo(mjassen)
* Streamlined database install logic to run not with every plugin call but rather only during activation on install hook.
* Under the WP dashboard > Tools admin menu, added a menu item to a dashboard page for site administrators. The Mzz-stat admin page displays the Mzz-stat statistics like total site visitor count.
* As the statistics are now shown in the new admin page, removed the shortcode logic so now the stats can't be viewed via a shortcode. 

= 20151206.2043 =
* 06 December 2015 by mjassen
* added database install code script that installs the mzzstat database table if it doesn't already exist.

= 20151205.1008 =
* 05 December 2015 by mjassen
* added uninstall.php script that properly removes the mzzstat database table. Upon clicking Delete for the plugin. (In the plugins area of the WP dashboard, after having deactivated the plugin, upon clicking the Delete link.)

= 20151204.2108 =
* 04 December 2015 by mjassen
* Changed the footer stat insert code to use native WordPress database call.

= 20151124.1 = 
* 24 November 2015 by mjassen
* Changed the shortcode display code to use native WordPress database call.

= 20150903.1 = 
* 3 September 2015 by mjassen
* added a readme.txt changelog file to track changes.
* changed versioning to have datestamp.

= 0.0.1 =
* 10 August 2015 by mjassen
* initial release
