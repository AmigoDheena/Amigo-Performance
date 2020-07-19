# Amigo-Performance
Amigo Performance is a WordPress Plugin that is used to Optimize Website Performance and improve Site Score in services like Google Page Speed Insight, GTmetrix.

[![License](https://img.shields.io/badge/license-GPL--2.0%2B-red.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

Very simple user interface to optimize selected option, Currently available optimization features are,

- Remove Query String
- Remove Emoji
- Defer Parsing of Javascript
- Iframe lazyload

**1. Remove Query String**

Your CSS and JavaScript files usually have the file version on the end of their URLs, such as **example.com/style.css?ver=4.6**. Some servers and proxy servers are unable to cache query strings, even if a **cache-control:public** header is present. By removing them, you can sometimes improve your caching.


**2. Remove Emoji**

From **WordPress 4.2**, they added support for **emojis** into the core, by default WordPress load the wp-emoji-release.min.js file, if you are not using emojis you can remove the file to save 13.5Kb. 


**3. Defer Parsing of Javascript**

In order to load a page, the browser must parse the contents of all **Script** tags, which adds additional time to the page load. By minimizing the amount of JavaScript needed to render the page, and deferring parsing of unneeded JavaScript until it needs to be executed, you can reduce the initial load time of your page.


**4. Iframe Lazyload**

Mostly iFrames are load data from an external site, so its contain lot of resources form external sites, we are unable to control that resources but we can prevent by lazyloading iFrame.
