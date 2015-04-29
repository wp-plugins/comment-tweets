=== Comment Tweets ===
Contributors: tommcfarlin
Donate link: https://tommcfarlin.com/donate/
Tags: comments, tweets
Requires at least: 3.4.1
Tested up to: 4.2.1
Stable tag: 2.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Comment Tweets gives you the ability to take the URL of a tweet and add it to the conversation on your blog.

== Description ==

Occasionally, readers will respond to your posts via tweets which is awesome, but when this happens, it's tough log the conversation between post comments and  the various tweets. This plugin aims to mitigate that problem!

Comment Tweets...

* Adds a set of fields below the post editor that allow you to paste the URL of a tweet
* Supports adding all of the tweets that you receive
* Allows you to remove tweets by simply removing the URL from the post editor
* Renders tweets in the order that they are listed
* Styles the Tweets using the Twitter API

For more information or to follow the project, check out the [project page](http://tommcfarlin.com/projects/wordpress-comment-tweets).

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' Plugin Dashboard
1. Select `comment-tweets.zip` from your computer
1. Upload
1. Activate the plugin on the WordPress Plugin Dashboard

= Using FTP =

1. Extract `comment-tweets.zip` to your computer
1. Upload the `comment-tweets` directory to your `wp-content/plugins` directory
1. Activate the plugin on the WordPress Plugins dashboard

== Frequently Asked Questions ==

= What happens to my tweets when I delete this plugin? =

In order to keep the database clean, all Tweets will be deleted if this plugin is deactivated. If you'd like to see a feature for maintaining the tweets or keeping the tweets around, feel free to [contact me](http://tommcfarlin.com/contact/).

== Screenshots ==

1. The default tweet form in the Post Editor Dashboard
2. Adding a single tweet to a post
3. Displaying multiple tweets to readers

== Changelog ==

= 2.3.0 =

* WordPress 4.2.1 compatibility
* Updating copyright dates

= 2.2.0 =
* Verifying WordPress 4.1 compatibility

= 2.1.0 =
* Verified WordPress 3.9 compatibility

= 2.0.0 =
* Renamed the core plugin file
* Implemented the singleton pattern
* Introduced a file responsible for invoking an instance of the plugin
* Verified WordPress 3.8 compatibility

= 1.2 =
* Minor style update to the 'Add New Tweet' button
* Verifying compatibility with WordPress 3.5
* No longer deleting tweets on deactivation

= 1.1 =
* Updating plugin URL
* Removing a TODO in the code
* Updating the README version and FAQ

= 1.0 =
* Initial release

== Development Information ==

Comment Tweets was built using...

* [WordPress Coding Standards](http://codex.wordpress.org/WordPress_Coding_Standards)
* Native WordPress API's (specifically the [Plugin API](http://codex.wordpress.org/Plugin_API))
* [CodeKit](http://incident57.com/codekit/) using [LESS](http://lesscss.org/), [JSLint](http://www.jslint.com/lint.html), and [jQuery](http://jquery.com/)
* As during a screencast as a working plugin for a premium [Envato](http://envato.com) tutorial
* Respect for WordPress bloggers everywhere :)