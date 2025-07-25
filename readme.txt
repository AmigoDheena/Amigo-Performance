=== Amigo Performance ===
Contributors: AmigoDheena
Tags: performance, optimization, page speed, caching, lazy loading
Requires at least: 6.0
Tested up to: 6.9
Stable tag: 3.2
Requires PHP: 8.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Boost your website speed and improve Core Web Vitals scores with this comprehensive WordPress performance optimization plugin.

== Description ==

**Amigo Performance** is a powerful yet lightweight WordPress optimization plugin designed to significantly improve your website's loading speed and performance scores. Whether you're a developer, agency, or website owner, this plugin provides essential tools to enhance your site's Core Web Vitals and user experience.

### 🚀 Key Features

**🎯 Advanced Asset Manager (NEW in v3.2):**
* **Page-wise Asset Grouping** - Assets organized by page URL in an accordion interface for easy management
* **Compact View Design** - Handle large numbers of assets efficiently with streamlined UI
* **Site-Wide Asset Control** - Manage CSS/JS files on every page, not just the homepage
* **Visual Asset Discovery** - See all loaded assets with real-time detection on any page
* **One-Click Optimization** - Remove unnecessary files instantly without coding
* **Smart Restore System** - Quickly restore assets if something breaks
* **Per-Page Precision** - Different optimization settings for different pages
* **Developer-Level Power** - Professional optimization tools with beginner-friendly interface
* **Asset Statistics** - Track performance improvements and asset usage across your site

**✨ Minification Features (NEW in v3.2):**
* **CSS Minification** - Compress CSS files by removing whitespace, comments, and unnecessary characters
* **JavaScript Minification** - Optimize JavaScript files for faster loading and execution

**Core Performance Optimizations:**
* **Remove Query Strings** - Eliminate version parameters from CSS/JS files for better caching
* **Remove WordPress Emoji Scripts** - Save 13.5KB by removing unnecessary emoji assets
* **Defer JavaScript Parsing** - Improve initial page load times by deferring non-critical scripts
* **Advanced Lazy Loading** - Boost performance with iframe and image lazy loading

**Legacy Features (Deprecated in v3.0):**
* ~~**Selective Script Removal**~~ - Replaced by Advanced Asset Manager
* ~~**Selective Stylesheet Removal**~~ - Replaced by Advanced Asset Manager
* *Note: Old CSS/JS removal features only worked on homepage - new Asset Manager works site-wide*

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

**🎯 Revolutionary Asset Manager (Updated in v3.2)**
The Advanced Asset Manager is now even better with page-wise asset grouping in a convenient accordion interface. This major UI improvement makes it much easier to manage large numbers of assets across your entire website.

**How It Works:**
- Visit any page on your website while logged in as admin
- Click the "Asset Manager" button in the admin bar
- See all CSS/JS files organized by page URL in an accordion interface
- Expand/collapse page sections to focus on specific areas of your site
- Toggle files on/off with simple switches
- Changes apply instantly - test immediately
- If something breaks, restore it with one click

**Key Advantages:**
- **Page-wise Organization:** Assets are grouped by URL for easier management
- **Compact Interface:** Efficiently handle large numbers of resources
- **Site-Wide Control:** Unlike the old system (homepage only), manage assets on every page
- **Visual Interface:** See exactly which files are loading in real-time
- **Safe Testing:** All changes are reversible instantly
- **No Coding Required:** Point, click, optimize - that's it!
- **Professional Results:** Achieve developer-level optimization without technical knowledge

**✨ CSS & JS Minification (NEW Features)**
The new minification features help reduce file sizes by removing unnecessary characters, whitespace, and comments from your CSS and JavaScript files.

**How Minification Works:**
- Enable CSS and/or JS minification in the core settings
- The plugin automatically processes files to reduce their size
- Minified versions are cached for optimal performance
- Original files remain untouched for safety
- File size reductions of 20-40% are common

**Key Benefits:**
- **Smaller File Sizes:** Reduces download time and bandwidth usage
- **Faster Page Loading:** Smaller files load and execute more quickly
- **Improved Performance Scores:** Better PageSpeed and Core Web Vitals metrics
- **No Visual Changes:** Functionality and appearance remain identical
- **Smart Caching:** Minified files are cached for optimal performance

**1. Query String Removal**
Removes version parameters (e.g., `?ver=4.6`) from CSS and JavaScript URLs. Some servers and proxy caches cannot cache files with query strings, even with proper cache headers. This optimization can improve caching efficiency significantly.

**2. WordPress Emoji Removal**
Since WordPress 4.2, emoji support loads additional JavaScript (`wp-emoji-release.min.js`) on every page. If you don't use WordPress emojis, removing this saves 13.5KB and reduces HTTP requests.

**3. JavaScript Defer Parsing**
Defers the parsing of non-critical JavaScript until it's actually needed. This reduces the initial page load time by allowing the browser to render the page content first before processing JavaScript files.

**4. Advanced Iframe Lazy Loading**
Prevents iframes from loading until they're visible in the viewport. Since iframes often load external resources beyond your control, lazy loading can dramatically improve initial page performance.

**5. Modern Image Lazy Loading**
Implements efficient image lazy loading using modern techniques to improve Core Web Vitals, particularly Largest Contentful Paint (LCP) scores.

**6. CSS Minification (NEW in v3.2)**
Reduces CSS file sizes by automatically removing unnecessary whitespace, comments, and redundant code. Minified CSS files load faster and consume less bandwidth, improving overall page performance.

**7. JavaScript Minification (NEW in v3.2)**
Optimizes JavaScript files by removing comments, whitespace, and unnecessary characters without changing functionality. Smaller JS files parse and execute faster, leading to improved page speed scores.ques to improve Core Web Vitals, particularly Largest Contentful Paint (LCP) scores.

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
2. **Asset Manager**: Use the revolutionary new tool to optimize CSS/JS files across your entire site:
   - Visit any page on your website (frontend)
   - Click "Asset Manager" in the admin bar (visible to administrators only)
   - Toggle CSS/JS files on/off for that specific page
   - Test immediately - changes apply instantly
   - If something breaks, restore it with one click
3. **Legacy Tabs** (Deprecated): The old "Remove JS" and "Remove CSS" tabs that only worked on the homepage have been replaced by the superior Asset Manager
4. **Save Changes**: Click save for basic settings and test your website performance

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

Absolutely! The plugin works with all major page builders including Elementor, Divi, Beaver Builder, and Gutenberg. The new Asset Manager in v3.0 is especially powerful for page builders - you can optimize each page individually without affecting others. If you accidentally disable a required asset, simply restore it with one click.

= How does the new Asset Manager work? =

The Asset Manager is the flagship feature of v3.0. Visit any page on your website while logged in as admin, click "Asset Manager" in the admin bar, and you'll see all CSS/JS files loading on that page. Toggle them on/off with simple switches. Unlike the old system that only worked on the homepage, this works site-wide. If something breaks, restore it instantly.

= What happened to the old JS/CSS removal features? =

The old "Remove JS" and "Remove CSS" tabs that only worked on the homepage have been replaced by the far superior Asset Manager that works on every page of your site. The Asset Manager gives you the same functionality but with much more power and flexibility.

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

1. Basic Settings - Core performance optimizations
2. Asset Manager - Revolutionary site-wide CSS/JS control

== Changelog ==

= 3.2 =
*Release Date: July 25, 2025*

**🚀 New Premium Features & Improvements:**
* ⚡ **CSS & JS Minification** - New premium features to compress and optimize CSS and JavaScript files
* 🎯 **Page-wise Asset Management** - Redesigned Asset Manager with accordion interface to group assets by page for easier management
* 🎨 **Compact UI Design** - More compact styling for Asset Manager to handle large numbers of resources efficiently
* 💎 **Visual Improvements** - Enhanced icon styling and visual indicators for premium features

**🔧 Technical Enhancements:**
* 🛡️ **WordPress Coding Standards Compliance** - Complete code quality improvements:
  * Improved uninstall process with WP_Filesystem API
  * Fixed database queries with proper escaping and prepared statements
  * Replaced error_log with WordPress logging methods
  * Enhanced file handling operations with proper security checks
* 🔄 **Performance Optimization** - Improved asset processing and caching mechanisms

= 3.1 =
*Release Date: July 22, 2025*

**🛠️ Maintenance & Security Update:**
* 🔒 **Enhanced Security** - Fixed SQL injection vulnerabilities in database queries
* 🐞 **Bug Fix** - Improved URL normalization and matching for more reliable asset dequeuing
* 💻 **Admin Bar Improvements** - Enhanced asset manager UI in admin bar
* 📝 **Debug Logging Improvements** - Implemented production-safe logging system
* 🔄 **Code Optimization** - Replaced direct database calls with properly documented alternatives

= 3.0 =
*Release Date: July 21, 2025*

**🚀 Major Release - Complete Plugin Restructure:**
* ✨ **Complete Plugin Architecture Redesign** - Modular class-based structure with 6 separate component files
* 🔧 **Enhanced Performance Optimizations** - Significantly improved core functionality and resource management
* 🛡️ **Comprehensive Security Hardening** - Complete security framework with nonce verification, input sanitization, and capability checks
* 📊 **Advanced Caching Implementation** - WordPress object cache integration with strategic cache invalidation
* 🎯 **WordPress Coding Standards Compliance** - Full PHPCS compliance with proper ignore comments for legitimate operations

**🔒 Security & Code Quality Enhancements:**
* 🔐 **Enhanced Nonce Verification** - Proper security checks across all AJAX operations and form submissions
* 🧹 **Input Sanitization & Validation** - All user input properly sanitized using WordPress functions
* 🛠️ **Database Query Optimization** - Prepared statements and proper caching for all database operations
* ⚡ **Performance Monitoring** - Asset-specific caching with intelligent cache invalidation
* 🎨 **Code Structure Improvements** - Clean separation of concerns with dedicated class files

**🚀 New Features & Improvements:**
* 🎯 **Revolutionary Asset Manager** - Site-wide CSS/JS control with visual interface replacing homepage-only legacy tools
* 📱 **Per-Page Asset Optimization** - Different optimization settings for different pages across your entire website
* 🔍 **Real-Time Asset Detection** - See exactly which files load on each page with live discovery
* 🔄 **One-Click Restore System** - Instantly restore assets if something breaks during optimization
* 🌐 **Enhanced Translation Support** - Improved internationalization with proper text domain loading
* 📈 **Comprehensive Statistics** - Track asset usage, performance improvements, and optimization impact
* 🎨 **Modern Admin Interface** - Maintained professional design with significantly enhanced functionality

**🐛 Critical Bug Fixes:**
* ✅ **Fixed Toggle Save Issues** - Resolved backend toggle persistence problems
* ✅ **Corrected Version Reporting** - Fixed WordPress.org statistics showing incorrect version numbers
* ✅ **Plugin File Structure** - Proper main file naming convention for WordPress.org compliance
* ✅ **Database Performance** - Optimized queries with comprehensive caching layer
* ✅ **Error Handling** - Improved error detection and user feedback mechanisms

**📦 Technical Improvements:**
* 🏗️ **Modular Architecture** - Separated functionality into specialized classes (Core, LazyLoad, AssetManager, Admin, Settings)
* 🗄️ **Database Optimization** - Custom table with proper indexing and caching for asset management
* 🔧 **WordPress Integration** - Better compatibility with WordPress core functions and multisite support
* 📚 **Documentation** - Comprehensive inline documentation and code comments
* 🎨 **Admin Interface** - Maintained modern professional design with improved functionality

= 2.7 =
*Release Date: July 18, 2025*

**🚀 Performance Enhancements:**
* ⚡ **Improved iframe lazy loading** - Enhanced handling of iframe attributes and better error detection
* 🔄 **External JavaScript Management** - Moved inline scripts to external files for better caching
* 🧠 **Memory Usage Optimization** - Reduced plugin memory footprint
* 🌐 **WordPress 6.9 Compatibility** - Full testing and optimization for latest WordPress version
* 📱 **Mobile Performance** - Specific enhancements for mobile browsing experience

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

= 3.0 =
**MAJOR UPDATE!** Complete plugin restructure with modular architecture, enhanced security, advanced caching, and WordPress coding standards compliance. This version fixes WordPress.org statistics issues and includes comprehensive performance improvements. Recommended upgrade for all users.

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