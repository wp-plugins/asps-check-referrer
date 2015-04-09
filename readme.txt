=== ASPS Check Referrer ===

Contributors: ArtistScope
Donate link: http://www.artistscope.com/asps-check-referer-wordpress.asp
Tags: protect, check, referrer
Tested up to: 4.1
Requires at least: 3.0.1 
Stable tag: trunk 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Restrict access to web pages by IP address and/or domain name.

== Description ==

This plugin restricts access from outside links (referrers) by IP address and/or domain name. It can be used to blacklist troublesome servers and data miners or it can be used to only allow access to nominated web pages from nominated services.

Although designed for general use by all WordPress sites, this plugin is recommended for use by ASPS Protected Hosting clients to restrict access to web pages intended for copy protection.

* Easy install.
* Restrict referrer access by IP address and/or domain name.
* Allow access to referrers by IP address and/or domain name.
* Specify which pages are to be protected using full link.
* Specify which sections to be protected by name or part thereof.
* Admin account is exempt from rules for proof reading and testing.

For more information visit the [ASPS Protected Hosting](http://www.artistscope.com/copy-protected-web-hosting.asp) website.


== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the "asps-check-referrer" folder and its contents to the "/wp-content/plugins/" directory.
2. Activate the plugin through the 'Plugins' menu in the WordPress dashboard.
3. Click "ASPS Check Referrer" to add the settings for your pages.


== Settings ==

After nominating an IP or domain address to allow/deny, you can then nominate which content on your website that the rules should be applied to. For example, you can nominate which pages are protected by nominating the whole link like:

* /gallery_showcase/
* example.com/?page_id=123

Or you can nominate sections by using only part of the link like:

* gallery
* showcase
* page_id

If your website uses several different domain names you can make nominations that mention the page/section only.

Domain names can server all aliases, for example using example.com will also allow news.example.com.


== Screenshots ==

1. Settings page
2. Customizable error message

== Changelog ==

= 0.1 =
* First release.


