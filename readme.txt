=== mzz-stat ===
Contributors: mjjojo
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

==Changelog==

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
