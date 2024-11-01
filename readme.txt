=== Active Users - Heartbeat version ===
Contributors: federico_jacobi
Donate link: http://example.com/
Tags: users, user, tracking, active users, current users
Requires at least: 3.6
Tested up to: 4.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Shows active users on the admin bar.

== Description ==

It shows on the admin bar which users are currently logged in AND active. So, if a user is logged in, but hasn't done anything, it won't show up.

Plugin is based on heartbeat, which explain the "active" users part.

Please send feedback if this is of any use to you!

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Done.

== Frequently Asked Questions ==

= Are there limitations to the users? =

Yes: In order to speed up processing time, i've used an option to store the active session. So, it is technically possible to have too many users going on at the same time. Options have a maximum size allocated. Also, the users show on the admin bar and at some point, your list will be too big to fit the screen.

= Can I get this information programmatically? =

SURE! ... use wiohb_get_active_users() and an array of the users slugs.

= Can I get other user info besides the slug? =

Yes and no: the function above only gives you slugs, however, you can use get_user_by( 'slug', $the_user_name ) to get the WP_User object


== Changelog ==

= 0.1 =
* First version.