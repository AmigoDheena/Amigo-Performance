=== Amigo Performance ===
Contributors: AmigoDheena
Tags: performance, optimization, page speed, caching, lazy loading
Requires at least: 4.0
Tested up to: 6.8
Stable tag: 2.5
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Boost your website speed and improve Core Web Vitals scores with this comprehensive WordPress performance optimization plugin.

== Description ==

**Amigo Performance** is a powerful yet lightweight WordPress optimization plugin designed to significantly improve your website's loading speed and performance scores. Whether you're a developer, agency, or website owner, this plugin provides essential tools to enhance your site's Core Web Vitals and user experience.

### 🎨 What's New in Version 2.5

**Modern Professional Interface:** Complete redesign with a sleek, modern admin panel that follows WordPress design standards. The new interface features intuitive tabbed navigation, professional color schemes, and enhanced user experience.

**Enhanced Security Framework:** Comprehensive security improvements with proper nonce validation, user capability checks, and input sanitization to ensure your website remains secure.

**Improved Performance:** Better code optimization, enhanced lazy loading algorithms, and improved compatibility with popular themes and plugins.

### 🚀 Key Features

**Core Optimizations:**
* **Remove Query Strings** - Eliminate version parameters from CSS/JS files for better caching
* **Remove WordPress Emoji Scripts** - Save 13.5KB by removing unnecessary emoji assets
* **Defer JavaScript Parsing** - Improve initial page load times by deferring non-critical scripts
* **Advanced Lazy Loading** - Boost performance with iframe and image lazy loading

**Resource Management:**
* **Selective Script Removal** - Remove unnecessary JavaScript files from your front page
* **Selective Stylesheet Removal** - Eliminate unused CSS files to reduce HTTP requests
* **Smart Asset Loading** - Load resources only when needed

### 🎯 Performance Benefits

✅ **Faster Page Load Times** - Reduce initial loading time by up to 40%
✅ **Improved Core Web Vitals** - Better LCP, FID, and CLS scores
✅ **Enhanced User Experience** - Smoother browsing and reduced bounce rates
✅ **Better SEO Rankings** - Google favors fast-loading websites
✅ **Reduced Server Load** - Fewer HTTP requests and optimized resource delivery
✅ **Professional Admin Interface** - Beautiful, modern interface for easy management
✅ **Enhanced Security** - Comprehensive security framework with proper validation
✅ **Mobile Optimized** - Perfect performance across all devices and screen sizes

### 🛠️ Feature Details

**1. Query String Removal**
Removes version parameters (e.g., `?ver=4.6`) from CSS and JavaScript URLs. Some servers and proxy caches cannot cache files with query strings, even with proper cache headers. This optimization can improve caching efficiency significantly.

**2. WordPress Emoji Removal**
Since WordPress 4.2, emoji support loads additional JavaScript (`wp-emoji-release.min.js`) on every page. If you don't use WordPress emojis, removing this saves 13.5KB and reduces HTTP requests.

**3. JavaScript Defer Parsing**
Defers the parsing of non-critical JavaScript until it's actually needed. This reduces the initial page load time by allowing the browser to render the page content first before processing JavaScript files.

**4. Advanced Iframe Lazy Loading**
Prevents iframes from loading until they're visible in the viewport. Since iframes often load external resources beyond your control, lazy loading can dramatically improve initial page performance.

**5. Intelligent Script Management**
Provides granular control over which JavaScript files load on your front page. Remove unnecessary scripts from plugins or themes that aren't needed for the user-facing experience.

**6. Smart Stylesheet Management**
Selectively remove CSS files that aren't required on your front page, reducing the total page size and improving load times.

**7. Modern Image Lazy Loading**
Implements efficient image lazy loading using modern techniques to improve Core Web Vitals, particularly Largest Contentful Paint (LCP) scores.

### 🎯 Perfect For

* **E-commerce Websites** - Improve conversion rates with faster loading
* **Blogs & Content Sites** - Better user engagement and SEO performance
* **Business Websites** - Professional performance that reflects quality
* **Developers & Agencies** - Essential tool for client website optimization

### 🏆 Tested With Popular Services

* Google PageSpeed Insights
* GTmetrix
* WebPageTest
* Pingdom Tools
* Core Web Vitals Assessment

== Installation ==

### Automatic Installation

1. Log in to your WordPress admin dashboard
2. Navigate to **Plugins → Add New**
3. Search for "Amigo Performance"
4. Click **Install Now** and then **Activate**
5. Go to **Performance** in your admin menu to configure settings

### Manual Installation

1. Download the plugin zip file
2. Upload the `amigo-performance` folder to `/wp-content/plugins/` directory
3. Activate the plugin through the **Plugins** screen in WordPress
4. Navigate to **Performance** in your admin menu
5. Configure your optimization settings

### Configuration

1. **Basic Tab**: Enable core optimizations (Query String Removal, Emoji Removal, JavaScript Defer, Lazy Loading)
2. **Remove JS Tab**: Select unnecessary JavaScript files to remove from front page
3. **Remove CSS Tab**: Choose unused CSS files to remove from front page
4. **Save Changes**: Click save and test your website performance

== Frequently Asked Questions ==

= Will this plugin break my website? =

Amigo Performance is designed with safety in mind. However, we recommend testing changes on a staging site first. The plugin allows you to selectively enable/disable features, so you can find the perfect balance for your site.

= How much performance improvement can I expect? =

Results vary depending on your current setup, but users typically see:
- 20-40% improvement in page load times
- 10-30 point increase in Google PageSpeed scores
- Better Core Web Vitals metrics

= Is this plugin compatible with caching plugins? =

Yes! Amigo Performance works excellently alongside caching plugins like WP Rocket, W3 Total Cache, and WP Super Cache. It focuses on different optimization areas that complement caching solutions.

= Can I use this with page builders? =

Absolutely. The plugin works with all major page builders including Elementor, Divi, Beaver Builder, and Gutenberg. Use the selective script/style removal features carefully with page builders.

= How do I test my website performance? =

We recommend these free tools:
- Google PageSpeed Insights (pagespeed.web.dev)
- GTmetrix (gtmetrix.com)
- WebPageTest (webpagetest.org)
- Google Search Console (Core Web Vitals report)

= What if I need to revert changes? =

Simply deactivate the features you don't want, or deactivate the entire plugin. All changes are reversible, and your website will return to its previous state.

= Does this work with WooCommerce? =

Yes! The plugin is fully compatible with WooCommerce and can significantly improve your store's performance, leading to better conversion rates.

== Screenshots ==

1. Basic Settings
1. Dequeue JS
1. Dequeue CSS

== Changelog ==

= 2.5 =
*Release Date: July 2025*

**🎨 Major UI/UX Enhancements:**
* ✨ **Complete Admin Interface Redesign** - Modern, professional design with improved user experience
* 🎯 **Enhanced Color Scheme** - Professional blue gradient (#2271b1 to #135e96) matching WordPress standards
* 📱 **Fully Responsive Design** - Optimized for all screen sizes and devices
* 🎨 **Improved Visual Hierarchy** - Better organization of settings and options
* 🔘 **Modern Form Elements** - Enhanced checkboxes, buttons, and form styling
* 📋 **Tabbed Interface** - Clean, organized tabs for better navigation
* 🎪 **Professional Card Layout** - Structured content with proper spacing and shadows
* 🖱️ **Enhanced Hover Effects** - Smooth transitions and interactive elements

**🛡️ Security & Code Quality Improvements:**
* 🔐 **Enhanced Security Framework** - Comprehensive nonce verification for all forms
* 🔒 **Proper User Capability Checks** - Only administrators can modify settings
* 🧹 **Code Sanitization** - All input data properly sanitized and validated
* 🛠️ **WordPress Coding Standards** - Fully compliant with WordPress development standards
* 🔧 **Error Handling** - Improved error handling and user feedback
* 📝 **Security Admin Notices** - Success/failure notifications for user actions

**🌍 Internationalization & Accessibility:**
* 🗣️ **Translation Ready** - Proper text domain loading and language support
* ♿ **Accessibility Improvements** - Better screen reader support and keyboard navigation
* 🌐 **Internationalization** - Full i18n support for global users
* 📚 **Documentation Enhancement** - Comprehensive inline help and tooltips

**⚡ Performance & Technical Upgrades:**
* 🚀 **Optimized Code Performance** - Faster loading and better resource management
* 🔄 **Enhanced Lazy Loading** - Improved image and iframe lazy loading algorithms
* 📦 **Better Asset Management** - Optimized CSS and JavaScript loading
* 🎯 **Core Web Vitals Optimization** - Better LCP, FID, and CLS scores
* 🔧 **Plugin Compatibility** - Enhanced compatibility with popular themes and plugins

**🐛 Critical Bug Fixes:**
* ✅ **Text Domain Loading** - Fixed early text domain loading issues
* ✅ **Nonce Validation** - Resolved security check failures
* ✅ **Form Processing** - Fixed form submission and data handling
* ✅ **JavaScript Defer** - Improved defer functionality for better performance
* ✅ **CSS/JS Removal** - Enhanced selective asset removal features
* ✅ **WordPress Compliance** - Fixed all WordPress repository validation warnings

**📖 Documentation & Support:**
* 📚 **Professional README** - Comprehensive documentation with feature details
* 💡 **Better User Guidance** - Improved help text and feature explanations
* 🔍 **Troubleshooting Guide** - Common issues and solutions
* 🎓 **Best Practices** - Performance optimization recommendations

= 2.0 =
*Release Date: 2025*

**New Features:**
* ✨ Advanced Image Lazy Loading implementation
* 🎯 Enhanced iframe lazy loading with customizable delays
* 🔧 Improved user interface and user experience
* 🛡️ Enhanced security with proper nonce validation
* 🌍 Translation-ready with proper text domain support

**Improvements:**
* 🚀 Significantly improved plugin performance and efficiency
* 📱 Better mobile device compatibility
* 🎨 Modern, intuitive admin interface
* 🔍 Better compatibility with popular themes and plugins

**Bug Fixes:**
* 🐛 Fixed text domain loading issues
* 🔧 Resolved nonce validation problems
* 🎯 Improved JavaScript defer functionality
* 🛠️ Enhanced error handling and debugging

= 1.0 =
*Release Date: 2024*

**Initial Release Features:**
* 🎯 Selective JavaScript removal (Front page only)
* 🎨 Selective CSS removal (Front page only)  
* 🖥️ Updated user interface design
* ⚡ Core performance optimization features

= 0.1 =
*Release Date: 2023*

* 🎉 Initial plugin release
* ⚡ Basic performance optimization features
* 🛠️ Foundation functionality established

== Upgrade Notice ==

= 2.5 =
**Major Update!** Complete UI redesign, enhanced security with nonce validation, improved performance, and critical bug fixes. Recommended upgrade for better performance and security.

= 2.0 =
**Major Update!** Added advanced image lazy loading, enhanced security, improved UI, and numerous bug fixes. This is a recommended update for all users to ensure optimal performance and security.

= 1.0 =
Significant improvements to JavaScript and CSS management features. Upgrade recommended for better performance control.

== Support ==

Need help? Here's how to get support:

* **Documentation**: Check our detailed setup guides
* **GitHub Issues**: Report bugs or request features
* **WordPress Support Forum**: Community support
* **Performance Testing**: We recommend testing changes on staging sites first

**Pro Tip**: Always backup your website before making optimization changes, and test performance improvements using tools like Google PageSpeed Insights.

---

Made with ❤️ for the WordPress community. Help us improve by leaving a review!