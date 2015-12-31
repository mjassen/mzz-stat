=== mzz-stat ===
Contributors: mjjojo
Requires at least: 4.4
Tested up to: 4.4
License: GPLv2

Shows the WP site administrator how many visits per page per day to their WP site.

== Description ==

<strong>The Purpose of the plugin</strong>
The purpose of the plugin is to show the WP site administrator how many visits per page per day to their WP site.

<strong>What the Plugin Does</strong>
The plugin records a timestamp and uri name for each page request on the WordPress site. It starts recording these for any time for which the plugin is activated. Then in the WordPress admin area, in the Dashboard menu under Tools > Mzz-stat, a page shows a report of these statistics, including a count of how many URI (page/post) views per day. Thus by looking at the statistics page the WordPress administrator can know whether anyone is visiting their site, and, how many uri requests were made on which page on which day.

<strong>How the Mzz-stat plugin works</strong>
Upon installing and activating the Mzz-stat plugin, it installs its own table in the WordPress database alongside the other database tables. The plugin logic hooks into the wp_footer action hook, and each time someone requests any page on the site, the plugin inserts a record of that visit into the database table. Then, at any time the WordPress Administrator can go to the Admin page and see the report in the Dashboard menu under Tools > Mzz-stat menu. There will be a list of each page visited (for a time period) along with a count of how many visits for that page per day. If one deactivates the plugin, it will no longer insert records of uri requests unless/until the plugin is again activated. If one deletes the plugin then that will completely uninstall the plugin and remove the files, database table, and data.

= Credits =

Thanks to CrazyStat ( http://www.christosoft.de/CrazyStat ) for inspiring me and thanks to this discussion: ( https://wordpress.org/support/topic/stats-plugin-8 ) for helping steer the direction of the plugin.

==Changelog==

= 20151230.2238 =
* 30 December 2015 by mjjojo(mjassen)
* Changed what data is seen in the Admin page. Now it is All-time hits, last-5-days hits, and then a big table/matrix of hits per page per day for past 5 days.
* Added more clear, complete description of the plugin and what it does, and FAQ, and credits, to the readme file.

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

== Frequently Asked Questions ==

= What is the purpose of the plugin, what does the plugin do, and how does the plugin work? =

For for a detailed description of what the plugin does and how it works, please see the Plugin Description tab/page of this readme.

= Does the plugin support multisite? =

Not currently. 

= Is the plugin internationalized or localized? =

Not currently.

= How to install the plugin? = 

To install the plugin manually, unzip the plugin and upload the entire "mzz-stat" folder into the "wp-content/plugins" directory of your WordPress website. 
Then log into the WordPress dashboard, and under Plugins, click Activate. 
The plugin follows the same install procedure as described at http://codex.wordpress.org/Managing_Plugins#Installing_Plugins 

= How to uninstall the plugin? =

To completely and permanently uninstall the plugin including its files and data, browse to the WordPress dashboard and under Plugins, under the Mzz-stat plugin, click Deactivate, then click Delete.

= How to get support for the plugin? =

Right now the primary support should be through the plugin's github issues page here:https://github.com/mjassen/mzz-stat/issues , or, if accepted to the .org plugins repository, then the primary support will instead be through the .org plugins support forum page.

= How to get/download the plugin? =

The current version of the plugin can be downloaded from its Github page here: https://github.com/mjassen/mzz-stat/archive/master.zip
