# [Amigo Performance](https://wordpress.org/plugins/amigo-performance/)
![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/amigo-performance?style=for-the-badge)
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/amigodheena/amigo-performance?style=for-the-badge)
![WordPress Plugin: Tested WP Version](https://img.shields.io/wordpress/plugin/tested/amigo-performance?style=for-the-badge)
![WordPress Plugin Downloads](https://img.shields.io/wordpress/plugin/dt/amigo-performance?style=for-the-badge)
![WordPress Plugin Rating](https://img.shields.io/wordpress/plugin/stars/amigo-performance?style=for-the-badge)

Amigo Performance is a WordPress Plugin that is used to Optimize Website Performance and improve Site Score in services like Google Page Speed Insight, GTmetrix.



Very simple user interface to optimize selected option, Currently available optimization features are,

- Remove Query String
- Remove Emoji
- Defer Parsing of Javascript
- Iframe lazyload
- Dequeue JS
- Dequeue CSS

**1. Remove Query String**

Your CSS and JavaScript files usually have the file version on the end of their URLs, such as **example.com/style.css?ver=4.6**. Some servers and proxy servers are unable to cache query strings, even if a **cache-control:public** header is present. By removing them, you can sometimes improve your caching.


**2. Remove Emoji**

From **WordPress 4.2**, they added support for **emojis** into the core, by default WordPress load the wp-emoji-release.min.js file, if you are not using emojis you can remove the file to save 13.5Kb. 


**3. Defer Parsing of Javascript**

In order to load a page, the browser must parse the contents of all **Script** tags, which adds additional time to the page load. By minimizing the amount of JavaScript needed to render the page, and deferring parsing of unneeded JavaScript until it needs to be executed, you can reduce the initial load time of your page.


**4. Iframe Lazyload**

Mostly iFrames are load data from an external site, so its contain lot of resources form external sites, we are unable to control that resources but we can prevent by lazyloading iFrame.

**5. Dequeue JS
**6. Dequeue CSS
