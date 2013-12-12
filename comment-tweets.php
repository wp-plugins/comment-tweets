<?php
/**
 * Comment Tweets
 *
 * Allow your readers easily to attach an image to their comments on posts and pages.
 *
 * @package   Comment_Tweets
 * @author    Tom McFarlin <tom@tommcfarlin.com>
 * @license   GPL-2.0+
 * @link      http://tommcfarlin.com
 * @copyright 2012 - 2013 Tom McFarlin
 *
 * @wordpress-plugin
 * Plugin Name: Comment Tweets
 * Plugin URI:  http://tommcfarlin.com/comment-tweets-for-wordpress/
 * Description: Comment Tweets gives you the ability to take the URL of a tweet and add it to the conversation on your blog.
 * Version:     2.0.0
 * Author:      Tom McFarlin
 * Author URI:  http://tommcfarlin.com
 * Text Domain: comment-tweets
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // end if

require_once( plugin_dir_path( __FILE__ ) . 'class-comment-tweets.php' );
Comment_Tweets::get_instance();