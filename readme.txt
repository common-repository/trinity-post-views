=== Trinity Post Views ===
Contributors: Thiago Valls, André Bertolino
Tags: views, postviews, mostviews, count post views, most viewed
Requires at least: 4.3
Tested up to: 4.4
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

It is a simple plugin for count views of posts. There is a widget that show the most views to with or without thumbnails.

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. If you wish use the Appearence/Widgets TW Post Vieweds screen to configure the widget .

== Frequently Asked Questions ==
It is so new that there aren't questions yet.

== Screenshots ==
1. -

== Changelog ==
= Version 1.0 =
* First version only

= Version 1.1 =
* Correcting widget title.

== Upgrade Notice ==
1. This is the first version.

== Usage ==

1. For show/print the post views in the loop wordpress:
<code>
<?php tw_the_postview($id); ?>
</code>

2. For get the post views in the loop wordpress:
<code>
<?php tw_get_postview($post->id); ?>
</code>

3. For show the most viewed post you must to activate the widget and to configure:

= Credits =

Plugin by Trinity Web Company and Inspiration and code from the other plugin. (wp-postviews) from Lester 'GaMerZ' Chan (http://lesterchan.net/).