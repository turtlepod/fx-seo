=== f(x) SEO ===
Contributors: turtlepod
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TT23LVNKA3AU2
Tags: seo
Requires at least: 4.1
Tested up to: 4.7
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Very simple SEO plugin.

== Description ==

**[f(x) SEO](http://genbu.me/plugins/fx-seo/)** is a basic SEO Plugin. WordPress is SEO-friendly enough to get our site crawled and indexed properly but lack in some area to modify title tag and add proper meta description. And that is all this plugins do.

After installation of this plugin, you can configure title and meta description template in "Settings > SEO".

**Features:**

1. Super simple. Only the essentials.
1. Override Front Page Title and Meta Description. 
1. Title and Meta Description Template for other pages.
1. Extendable and fully documented (read the Other Notes).
1. The GPL v2.0 or later license. :) Use it to make something cool.
1. Support available at [Genbu Media](https://genbu.me/contact-us/).


== Installation ==

1. Navigate to "Plugins > Add New" Page from your Admin.
2. To install directly from WordPress.org repository, search the plugin name in the search box and click "Install Now" button to install the plugin.
3. To install from plugin .zip file, click "Upload Plugin" button in "Plugins > Add New" Screen. Browse the plugin .zip file, and click "Install Now" button.
4. Activate the plugin.
5. Navigate to "Settings > SEO" page in your admin panel to manage the plugin settings.

== Frequently Asked Questions ==

= Where is the settings ? =

The settings is in "Settings > SEO" in your admin panel.

= What is %current_title% template tag output ? =

Current title output depends on what page you are viewing in the front end. Here's the full list of the output (without the quote):

* **Posts Page** (if set): "{Page Title}"
* **Single Post/Page/Custom Post Type**: "{Title}"
* **Category, Tag, and Taxonomy Archive**: "{Term Name}"
* **Custom Post Type Archive**: "{Post Type Label Name}"
* **Author Archive**: "{Author Name}"
* **Month Archive**: "Archive for {Month Year}"
* **Year Archive**: "Archive for {Year}"
* **404 Error**: "404 Not Found"
* **Other Pages**: "Archives"

= What is %current_description% template tag output ? =

Current description output depends on what page you are viewing in the front end. If it's not available it will not output meta description for that page. Here's the full list of the output (without the quote):

* **Posts Page** (if set): "{Page Content}"
* **Single Post/Page/Custom Post Type**: "{Excerpt}"
* **Author Archive**: "{Author Bio}"
* **Category, Tag, and Taxonomy Archive**: "{Term Description}"
* **Custom Post Type Archive**: "{Post Type Description}"

== Screenshots ==

1. SEO Setting.

== Changelog ==

= 1.1.0 - 12 Jan 2015 =
* Use get_the_archive_title() in archive title tag.
* fix text domain string.

= 1.0.0 - 9 Jan 2015 =
* Init

== Upgrade Notice ==

= 1.1.0 =
Bug & Improvement.

= 1.0.0 =
initial relase.

== Other Notes ==

Notes for developer: 

= Github =

Development of this plugin is hosted at [GitHub](https://github.com/turtlepod/fx-seo). Pull request and bug reports are welcome.

= Options =

This plugin save the options in single option name: `fx-seo`.

= Hooks =

List of hooks available in this plugin:

**filter:** `fx_seo_render_title` (string)

Modify the title tag. This loaded after the plugin done replacing template tag with actual text. You use this filter to add your own template tag.

**filter:** `fx_seo_render_meta_desc` (string)

Modify the meta description text string. This loaded after the plugin done replacing template tag with actual text. You use this filter to add your own template tag. This output is only the text/content of meta description (without the `<meta>` tag).

**filter:** `fx_seo_template_title_explain` (array)

List of Template available. Filter this to add additinal template to add in the settings page. This is just for adding the description in the input field.

**filter:** `fx_seo_template_meta_desc_explain` (array)

List of Template available. Filter this to add additinal template to add in the settings page. This is just for adding the description in the input field.
