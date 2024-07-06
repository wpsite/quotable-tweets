=== Quotable Tweets by DraftPress ===
Contributor: 99robots, charliepatel, DraftPress
Donate link:
Tags: quotable tweet, tweet, tweet button, twitter, tweet ad, tweet cards
Requires at least: 4.9
Tested up to: 6.5.5
Stable tag: 1.1.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Quotable Tweets plugin gives you an easy way to add a beautiful actionable tweet link to your sidebar.

== Description ==

The Quotable Tweets plugin by https://draftpress.com gives you an easy way to add a beautiful actionable tweet link to your sidebar.

Please check out our other [plugins](https://draftpress.com/products/?utm_source=wprepo&utm_medium=quotable-tweets&utm_campaign=desc) :)

Contribute to the plugin and submit pull requests through the [Quotable Tweets plugin for WordPress GitHub repository](https://github.com/wpsite/quotable-tweets).

== Screenshots ==

1. The widget output
2. The widget settings

== Installation ==

1. Upload `quotable-tweets` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.1.8 = 2024-07-02
* Made compatible with WordPress 6.5.5
* Transitioned the widget into blocks for improved flexibility and usability.
* Updated theme to align with the rebranding from Twitter to X:
  * Primary Color: Black (#000000)
  * Secondary Color: Dark Gray (#333333)
  * Accent Color: Light Gray (#AAAAAA)

= 1.1.7 = 2021-09-16
* Made compatible with WordPress 5.8.1

= 1.1.6 = 2021-05-17
* Made compatible with WordPress 5.7.2

= 1.1.5 = 2020-09-14
* Made compatible with WordPress 5.5.1

= 1.1.4 = 2020-05-27
* Made compatible with WordPress 5.4.1
* Added DraftPress as a Contributor

= 1.1.3 = 2019-09-11
* Made compatible with WordPress 5.2.3

= 1.1.2 = 2017-06-11
* Made compatible with WordPress 4.8

= 1.1.1 = 2015-10-16
* UPDATED: Banners and Icons

= 1.1.0 = 2015-10-16
* REMOVED: Widget quotable link removed
* UPDATED: Added a better description to the readme

= 1.0.0 =
* Initial release

== Extending or Upgrading the Plugin ==

If you're a developer looking to extend or upgrade the Quotable Tweets plugin, follow these steps:

### Extending Functionality
1. **Hooks and Filters**: Utilize WordPress hooks and filters provided by the plugin to modify or extend its functionality. Check out the plugin's source code and documentation for available hooks.

2. **Custom Blocks**: To add new features or modify existing ones, edit the block files (`edit.js`, `save.js`, `index.js`, `style.scss`, `editor.scss`) in the `block` directory. Customize the behavior in `edit.js` and `save.js`, and style changes in `style.scss` and `editor.scss`.

### Upgrading the Plugin
1. **Updating Dependencies**: If updating npm packages or dependencies, ensure compatibility with existing code and WordPress standards. Update the `package.json` and `package-lock.json` files accordingly.

2. **Building Assets**: After making changes, run `npm install` to update dependencies and `npm run build` to compile assets (`editor.css`, `editor.js`, `style.css`, `index.js`) into the `block/build` directory.

3. **Testing**: Test thoroughly in a local WordPress environment before deploying changes. Check for compatibility with different WordPress versions and configurations.

4. **Documentation**: Update the `readme.txt` file with new features, changes, and instructions for users and developers.

By following these steps, you can effectively extend or upgrade the Quotable Tweets plugin while maintaining compatibility and functionality across various WordPress setups.
