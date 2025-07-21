=== Enpii Base ===
Contributors: npbtrac
Donate link: https://enpii.com/
Tags: laravel, framework, development, blade, templates
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 0.9.1
Requires PHP: 7.3
License: MIT
License URI: https://mit-license.org/

Base plugin for WordPress development using Laravel framework capabilities.

== Description ==

Enpii Base plugin brings the powerful features of Laravel framework to WordPress development, enabling developers to use modern development patterns and tools within the WordPress ecosystem.

**Key Features:**

* **Laravel Framework Integration** - Full Laravel 8 capabilities within WordPress
* **Blade Templates** - Use Laravel's Blade templating engine for WordPress themes  
* **Service Providers** - Leverage Laravel's dependency injection container
* **Eloquent ORM** - Advanced database operations with Laravel's ORM
* **Queue System** - Background job processing
* **Routing System** - Advanced routing capabilities
* **Artisan Commands** - Laravel's command-line interface via WP-CLI

**Three Operating Modes:**

1. **Normal WordPress** - Maintains all WordPress behaviors while adding Laravel features
2. **WP App Mode** - Laravel handles requests and responses (yoursite.com/wp-app)
3. **WP API Mode** - Stateless API handling (yoursite.com/wp-api)

**Example Usage:**

Create template files using Blade syntax:

`@extends('layouts/main')

@section('content')
    <h1>{{ 'Welcome to WP App from Enpii Base' }}</h1>
@endsection`

**Developer Tools Included:**

* Laravel Telescope for debugging
* Laravel Tinker for interactive shell
* PHPUnit testing framework
* Code quality tools (PHPCS, PHPStan)

**Documentation:**

Visit our [documentation](https://enpii.com/wp-plugin-enpii-base/) for detailed guides and examples.

== Installation ==

**Automatic Installation:**

1. Go to Plugins > Add New in your WordPress admin
2. Search for "Enpii Base"
3. Click Install Now and then Activate

**Manual Installation:**

1. Download the plugin files
2. Upload to `/wp-content/plugins/enpii-base/` directory
3. Activate the plugin through the 'Plugins' screen in WordPress

**Setup (Required):**

After activation, run these WP-CLI commands:

`wp enpii-base prepare`
`wp enpii-base wp-app:setup`

Or use the admin setup page if WP-CLI is not available.

== Frequently Asked Questions ==

= Does this work with all WordPress themes? =

Yes, Enpii Base maintains full backward compatibility with all WordPress themes and plugins.

= What PHP version is required? =

PHP 7.3 or higher is required. PHP 8.0+ is recommended for best performance.

= Can I use this on shared hosting? =

Yes, but ensure your hosting provider supports the required PHP version and allows the necessary file permissions.

= Does this affect WordPress performance? =

The plugin is designed to be efficient, but as it includes the Laravel framework, there is some overhead. Performance impact is minimal in normal WordPress mode.

= Can I use Laravel packages? =

Yes, most Laravel packages that don't conflict with WordPress core functionality can be used.

= Is this compatible with multisite? =

Basic multisite compatibility is included, but some advanced features may need additional configuration.

== Screenshots ==

1. Plugin activation and setup screen
2. Blade template example in WordPress theme
3. Laravel Telescope debugging interface
4. WP-CLI commands available

== Changelog ==

= 0.9.1 =
* Laravel 8.83.0 integration
* PHP 8.0 support
* Improved WordPress compatibility
* Bug fixes and performance improvements
* Updated documentation

= 0.9.0 =
* Major rewrite with Laravel 8
* Simplified installation process
* Added Telescope and Tinker support
* Improved error handling
* Enhanced security measures

== Upgrade Notice ==

= 0.9.1 =
Recommended update with Laravel 8 support and improved WordPress compatibility. Please backup your site before updating.

== Additional Info ==

**System Requirements:**
* WordPress 5.0+
* PHP 7.3+
* MySQL 5.6+ or MariaDB 10.0+

**Support:**
* Documentation: https://enpii.com/wp-plugin-enpii-base/
* GitHub: https://github.com/enpii/enpii-base
* WordPress.org Support Forum

**Contributing:**
This plugin is open source. Contributions are welcome on GitHub.