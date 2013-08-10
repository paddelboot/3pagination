=== Plugin Name ===
Contributors: paddelboot
Tags: pagination
Requires at least: 3.3.2
Tested up to: 3.4.1
<<<<<<< HEAD
Stable tag: 1.3.2b
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Navigate to any page between 1 and 999 with no more than 3 clicks - no need to modify theme files.
=======
Stable tag: 1.2b
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Navigate to any page between 1 and 999 with no more than 3 clicks.
>>>>>>> 4cb81d38e0a5066b7c278026a64fce79cd5e3ca8

== Description ==

Give your visitors the ability to easily access all your site's content. Any page between 1 and 999 can be accessed with a maximum of 3 clicks (see screenshots).
<ul>
<li>Easy access to all your website's content</li>
<li>Make searchengines happy by creating a more thorough internal linking structure</li>
<li>No need to modify theme files! (see installation instructions)</li>
<li>Modify labels and CSS and preview pagination in backend before using it in your frontend pages!</li>
<li>Both default and pretty permalink structure are supported</li>
<li>Custom queries are supported</li>
<li>So far, this code has been tested on index, archive, custom post type archive, category and search pages of some of the most popular WordPress themes.</li>
</ul>
Please leave feedback, bug reports or comments at https://github.com/paddelboot/3pagination/issues

== Installation ==

<h4>Installation</h4>
1. Upload `3pagination.php` to the `/wp-content/plugins/` directory OR choose `Plugins->Add New` and type "3pagination".
2. Activate the plugin through the `Plugins` menu in WordPress
3. Go to `Settings->3pagination` to set labels, placement and CSS.
4. Done!

<h4>How to use</h4>
All options can be set in an options page, the pagination container can be injected or appended to the existing DOM. 
If you are unsure about the HTML structure of your Website, you can do the following (in Firefox):
<ol>
<li>Right click the spot where you wish the pagination to appear and choose `Inspect Element (Q)` from the menu list</li>
<li>Copy the id of the container, i.e. #wrapper.someclass</li>
<li>To append or prepend the pagination to this container, paste it's id into the appropriate field of the Placement section under `Settings->3pagination`</li>
<li>Choose on which pages (archives, search etc.) the pagination should appear. Done!</li>
</ol>


<h4>How to use - old school</h4>
You can, if you want (or to have your website degrade gracefully), call the class methods in your theme files.

draw() : Display the pagination
`draw ( $pretty = TRUE, $max_num_pages = FALSE, $labels = TRUE, $css = 'classic', $wp_query = FALSE )`
<ul>
<li>$pretty (optional) | Are you using pretty permalinks?</li>
<li>$max_num_pages (optional) | Limit to a maximum number of pages</li>
<li>$labels (optional) | Display or not the navigation arrows</li>
<li>$css (optional) | CSS class name that will be appended to the main div container</li>
<li>$wp_query (optional) | custom WP query object
</ul>

get() : Return the pagination as a HTML string
`get ( $pretty = TRUE, $num_items = FALSE, $per_page = FALSE, $labels = TRUE, $css = 'classic', $wp_query = FALSE )`

<h4>Example usages</h4>
`threepagination::draw ( TRUE, FALSE, FALSE, 'my_custom_class', FALSE )`
Displays the pagination on a website that uses pretty urls, takes the standard page count, hides the navigation arrows, attributes a CSS class 'my_custom_class' to the pagination container and uses the global query object.

`$string = threepagination::get ()`
Save the pagination in the `$string` var.

== Screenshots ==

1. Settings page: preview, label, placement, CSS and other settings
2. Injected below header

== Changelog ==

= 0.1a =
- Initial version

= 0.2a =
- Improved arrow navigation
- replaced $num_items and $per_page with $max_num_pages
- Slightly changed CSS colors

= 1.0 =
- Stable version
- Fixed support of custom css styles via function parameter

= 1.2b =
- Added backend settings page
- Inject pagination into DOM
- Support for search templates

= 1.3b =
- Added checkbox for pretty URLs;
- Added option for max no. of pages
- Fixed link URL building;
- German localization;
- Plugin loading enqueued;
- Fixed bug with pagination link;
- Contextual help tab
- Fixed bug with default URLs pagination link

= 1.3.1b =
- New function parameter $wp_query : support for custom query object

= 1.3.2b =
- Fixed error message in options screen after fresh install

== A brief Markdown Example ==
`<?php if ( class_exists( 'threepagination' ) ) : threepagination::draw(); endif; ?>`
`<?php if ( class_exists( 'threepagination' ) ) : threepagination::get(); endif; ?>`

== Translations ==
<ul>
<li>English</li>
<li>German</li>
</ul>
