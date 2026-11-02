=== Amigo Performance ===
Contributors: AmigoDheena
Tags: performance, optimization, page speed, caching, lazy loading
Requires at least: 6.0
Tested up to: 7.1
Stable tag: 3.4
Requires PHP: 8.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Amigo Performance delivers professional-grade optimization controls for WordPress sites that need consistent Core Web Vitals improvements without adding bloat or complicated workflows.

== Description ==

Amigo Performance combines modern UI design with proven performance techniques. The plugin introduces an advanced per-page asset manager, configurable minification, and streamlined lazy loading controls so that agencies, developers, and site owners can fine-tune front-end delivery with confidence.

### Key capabilities

**Advanced Asset Manager (updated in v3.4)**
- Page-level grouping with redesigned accordions for faster navigation
- Compact card layout that highlights enabled versus disabled assets
- Bulk enable/disable controls with detailed statistics, progress rings, and empty-state guidance
- One-click restore workflow to undo problematic dequeues

**Minification controls (introduced in v3.3)**
- CSS and JavaScript minification pipelines with cached outputs
- Automatic fallbacks to original files to ensure safe rollbacks

**Foundational performance switches**
- Remove query strings from static assets to improve cache hit rates
- Remove WordPress emoji scripts and styles to eliminate unnecessary payloads
- Defer non-critical JavaScript execution
- Modern image and iframe lazy loading modules

**Security and quality focus**
- Capability checks, nonce validation, and fully escaped output for all admin pages
- Modular architecture with dedicated service classes and logging utilities

### Benefits

- Lower time-to-first-byte and faster visual completion thanks to smaller payloads
- Improved PageSpeed Insights and Core Web Vitals scores across desktop and mobile
- Reduced troubleshooting time through visual asset discovery and per-page isolation
- Predictable workflows that fit agency handoffs and enterprise maintenance plans

== Installation ==

### Automatic installation
1. Sign in to the WordPress admin area.
2. Navigate to Plugins → Add New and search for “Amigo Performance”.
3. Click Install Now, then Activate.
4. Open Performance in the admin menu to configure the plugin.

### Manual installation
1. Download the plugin package and extract it.
2. Upload the `amigo-performance` folder to `/wp-content/plugins/`.
3. Activate “Amigo Performance” from the Plugins screen.
4. Configure the available modules from Performance → Amigo Performance.

### Initial configuration checklist
1. In Core Settings, enable the foundational toggles (query string removal, emoji removal, defer, lazy loading) that match your caching stack.
2. Use the Asset Manager from the admin menu or admin bar to inspect per-page assets. Disable unnecessary CSS or JavaScript and test the page immediately.
3. Enable CSS and/or JS minification if you want automatic compression handled by the plugin. Amigo Performance caches minified versions while keeping original files untouched.
4. Review the “How to Use” guidance from the dashboard whenever no assets are managed yet—this on-screen documentation is designed for onboarding additional team members.

== Frequently Asked Questions ==

**Does Amigo Performance work with caching plugins?**
Yes. It complements caching and CDN layers by focusing on payload reduction, dequeues, and lazy loading. Common combinations include WP Rocket, W3 Total Cache, and object caching stacks.

**Can I use it with page builders and WooCommerce?**
Absolutely. The asset manager works on any front-end route, including builder-generated layouts and WooCommerce templates. Each asset change is scoped to the specific page URL you manage.

**How do I revert changes if a dequeue causes issues?**
Every managed asset can be re-enabled from the Asset Manager or deleted entirely. Deleting an asset entry removes only the record in the Amigo Performance table—it does not delete files from disk.

**How much improvement should I expect?**
Sites typically see 20–40 percent faster initial load times and double-digit gains in Google PageSpeed Insights, but results depend on the existing code base and hosting provider.

== Screenshots ==

1. Core Settings panel with grouped optimization toggles
2. Asset Manager interface with accordion-based per-page controls

== Changelog ==

= 3.4 =
Release date: November 2, 2026
- Refreshed Core Settings and Asset Manager with compact layouts, restrained green accents, clearer typography, and consistent controls
- Reduced spacing in headers, settings rows, and statistics while retaining existing optimization settings and asset actions
- Redesigned the frontend admin-bar Asset Manager with a clean dropdown and grouped CSS and JavaScript sections
- Replaced sideways asset flyouts and nested scrollbars with a single scrollable panel that stays within the viewport
- Improved long filename wrapping, narrow-screen layouts, keyboard focus visibility, and reduced-motion styling
- Scoped plugin styles to prevent interference with other WordPress admin screens and consolidated toolbar styling
- Updated plugin version metadata and asset cache versions consistently for the 3.4 release
- Normalized the image lazy-loading initializer to UTF-8 without changing its logic
- Repaired the bundled toolbar script cache and excluded the toolbar script from automatic minification to preserve its JavaScript syntax

= 3.3 =
Release date: March 1, 2026
- Complete visual redesign of the Asset Manager, including progress overview, compact stats bar, and modernized accordion layout
- Core Settings page refreshed to match the new design language with consistent typography, toggles, and section labels
- Documentation rewritten in a professional tone with clearer installation and usage guidance
- WordPress Coding Standards fixes for newly introduced templates, ensuring all dynamic output is escaped
- General quality-of-life improvements: refined empty states, contextual action buttons, and improved accessibility labels

Earlier 3.3 changes (July 25, 2025):
- Added CSS and JavaScript minification controls with cached file handling
- Introduced page-wise asset grouping, compact view states, and updated iconography in the Asset Manager
- Improved uninstall routines, database access layers, and filesystem security checks
- Replaced legacy logging with WordPress-aware logging helpers

= 3.1 =
Release date: July 22, 2025
- Hardened database queries with parameterized statements
- Improved asset matching logic for more reliable dequeues
- Updated admin bar UI and logging to improve debugging workflows

= 3.0 =
Release date: July 21, 2025
- Migrated to a modular architecture (Core, LazyLoad, AssetManager, Admin, Settings)
- Implemented advanced caching, nonce verification, capability checks, and internationalization updates
- Introduced the new Asset Manager replacing homepage-only controls, along with live discovery, statistics, and restore options
- Added comprehensive documentation, statistics tracking, and refined admin UI patterns

= 2.7 =
Release date: July 18, 2025
- Updated iframe lazy loading, moved inline scripts to files, reduced memory usage, and confirmed WordPress 6.9 compatibility

= 2.5 =
Release date: July 2025
- Major admin UI overhaul, responsive layout, improved hierarchy, and tabbed navigation
- Added stronger nonce handling, capability checks, and inline guidance

= 2.0 =
Release date: 2025
- Advanced image lazy loading, enhanced iframe handling, improved UI, and expanded translation support

= 1.0 =
Release date: 2024
- Selective JavaScript and CSS removal for the front page, updated user interface, and foundational optimization tools

= 0.1 =
Release date: 2023
- Initial public release

== Upgrade Notice ==

= 3.4 =
Version 3.4 refreshes Core Settings, Asset Manager, and the frontend admin-bar menu with compact styling and improved dropdown scrolling. Existing optimization settings and saved asset rules are retained. Clear any page or CDN caches after upgrading so the updated styles and scripts are served.

== Support ==

- Documentation: refer to the in-plugin guidance and knowledge base articles
- GitHub Issues: report reproducible bugs or request improvements
- WordPress.org forums: community-driven assistance

Thank you for using Amigo Performance. Professional feedback and reviews help guide future releases.