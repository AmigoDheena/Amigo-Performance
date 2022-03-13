=== Amigo Performance ===
Contributors: AmigoDheena
Tags: performance, optimization, page speed, Speed Optimization, GTmetrix, Google Page Speed
Requires at least: 4.0
Tested up to: 5.8.3
Stable tag: trunk
Requires PHP: 5.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Amigo Performance is used to Optimize Website Performance and improve Site Score in services like Google Page Speed Insight, GTmetrix.

== Description ==

Amigo Performance is used to Optimize Website Performance and improve Site Score in services like Google Page Speed Insight, GTmetrix.

Very simple user interface to optimize selected option, Currently available optimization features are,

* Remove Query String
* Remove Emoji
* Defer Parsing of Javascript
* Iframe lazyload
* Dequeue JS
* Dequeue CSS

1. Remove Query String

     Your CSS and JavaScript files usually have the file version on the end of their URLs, such as **domain.com/style.css?ver=4.6**. Some servers and proxy servers are unable to cache query strings, even if a **cache-control:public** header is present. By removing them, you can sometimes improve your caching.


2. Remove Emoji

      From **WordPress 4.2**, they added support for **emojis** into the core, by default WordPress load the wp-emoji-release.min.js file, if you are not using emojis you can remove the file to save 13.5Kb. 


3. Defer Parsing of Javascript
     
     In order to load a page, the browser must parse the contents of all **Script** tags, which adds additional time to the page load. By minimizing the amount of JavaScript needed to render the page, and deferring parsing of unneeded JavaScript until it needs to be executed, you can reduce the initial load time of your page.


4. Iframe Lazyload

     Mostly iFrames are load data from an external site, so its contain lot of resources form external sites, we are unable to control that resources but we can prevent by lazyloading iFrame.

5. Dequeue JS

     In this option you can remove unwanterd JS file from Front Page

6. Dequeue JS

     In this option you can remove unwanterd CSS file from Front Page

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the **Amigo Perf** screen to configure the plugin
1. That’s it!

== Frequently Asked Questions ==

= How can I use this Plugin to Optimize? =

By simply select (Tick) the option to Activate and deselect to Deactivate the operation.

= How to Check My Site Performance? =

You can check in an popular services like GTmetrix and Google Page Speed Insight.


== Screenshots ==

1. Basic Settings
1. Dequeue JS
1. Dequeue CSS

== Changelog ==
= 1.0 =
* Introduced Dequeue JavaScript(Front Page Only)
* Introduced Dequeue Stylesheet(Front Page Only)
* UI upated.

= 0.1 =
* Initial release.

== Upgrade Notice ==
= 1.0 =
* Dequeue resources an UI upgrade

= 0.1 =
* Initial release.