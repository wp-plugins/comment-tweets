<?php
/**
 * Comment Tweets
 *
 * @package   Comment_Tweets
 * @author    Tom McFarlin <tom@tommcfarlin.com>
 * @license   GPL-2.0+
 * @link      http://tommcfarlin.com
 * @copyright 2011 - 2015 Tom McFarlin
 */

class Comment_Tweets {

	/*--------------------------------------------*
	 * Attributes
	 *--------------------------------------------*/

	/**
	 * Instance of this class.
	 *
	 * @since    2.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Return an instance of this class.
	 *
	 * @since     2.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		} // end if

		return self::$instance;

	} // end get_instance

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 *
	 * @since	1.0
	 */
	private function __construct() {

		// Setup Localization
		load_plugin_textdomain( 'comment-tweets', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

		// Register admin stylesheets and JavaScript
		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Register plugin stylesheets
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );

		// Setup the Tweet URL meta box
		add_action( 'add_meta_boxes', array( $this, 'add_tweet_url_metabox' ) );
		add_action( 'save_post', array( $this, 'save_tweet_url' ) );

		// Display the tweets
		add_action( 'comment_form_before', array( $this, 'display_tweets' ) ) ;

	} // end constructor

	/*--------------------------------------------*
	 * Deactivation
	 *--------------------------------------------*/

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @params	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if Multisite is disabled or plugin is activated on an individual blog
	 * @since	1.0
	 */
	public function deactivate( $network_wide ) {

		// Loop through all of the published posts
		$arguments = array (
			'post_type'		=>	'post',
			'post_status'	=>	'publish',
			'numberposts'	=>	-1
		);
		$posts_query = new WP_Query( $arguments );

		// If any posts are found...
		if( $posts_query->have_posts() ) {

			// ...loop through them all
			while( $posts_query->have_posts() ) {

				$posts_query->the_post();

				// Look through the meta data for the current post
				foreach( get_post_meta( get_the_ID() ) as $meta_key => $meta_value ) {

					// If any Tweet URL's are found, remove them.
					if( false != strstr( $meta_key, 'tweet_url' ) ) {
						delete_post_meta( get_the_ID(), $meta_key );
					} // end if

				} // end foreach

			} // end while

		} // end if

		// Reset the post query
		wp_reset_postdata();

	} // end deactivate

	/*--------------------------------------------*
	 * Enqueue Stylesheets
	 *--------------------------------------------*/

	/**
	 * Registers and enqueues plugin-specific styles.
	 *
	 * @since	1.0
	 */
	public function register_admin_styles() {
		wp_enqueue_style( 'comment-tweets-admin', plugins_url( 'comment-tweets/css/admin.css' ) );
	} // end register_plugin_styles

	/**
	 * Registers and enqueues plugin-specific JavaScript.
	 *
	 * @since	1.0
	 */
	public function register_admin_scripts() {
		wp_enqueue_script( 'comment-tweets-admin', plugins_url( 'comment-tweets/js/admin.min.js' ) );
	} // end register_admin_scripts

	/**
	 * Registers and enqueues plugin-specific styles.
	 *
	 * @since	1.0
	 */
	public function register_plugin_styles() {
		wp_enqueue_style( 'comment-tweets', plugins_url( 'comment-tweets/css/plugin.css' ) );
	} // end register_widget_styles

	/*--------------------------------------------*
	 * Tweet URL Post Meta Box
	 *--------------------------------------------*/

	/**
	 * Initializes the Meta Box used to display the option for including the Tweet URL
	 * for a given post.
	 *
	 * @since	1.0
	 */
	function add_tweet_url_metabox() {

		add_meta_box(
			'tweet_url',							// The ID attribute of the 'Edit' screen
			__( 'Tweet URL', 'comment-tweets' ),	// The localized versiokn of the title of the meta box
			array( $this, 'tweet_url_display' ),	// A reference to the function for rendering the meta box
			'post',									// Where to display the meta box in the dashboard
			'normal',								// The priority of the meta box (or where it should be displayed)
			'high'									// Where the box should be displayed. Here, directly under the Post Editor
		);

	} // end add_tweet_url_metabox

	/**
	 * Initializes the Tweet URL display panel in the Post Edit dashboard.
	 *
	 * @param	$post	The post on which this should be displayed
	 * @since	1.0
	 */
	function tweet_url_display( $post ) {

		wp_nonce_field( plugin_basename( __FILE__ ), 'tweet_url_nonce' );

		// Open the Tweet URL container
		$html = '<div id="tweet-url-container">';
		$html .= '<p class="description">'  . __( 'Paste Tweet URLs to display below the comments feed on your blog. Delete a Tweet URL to remove it.', 'comment-tweets' ) . '</p>';

		// Initialize a tweet counter so we know if the user has saved any
		$tweet_url_counter = 0;

		// Loop through all of this post's meta data looking for Tweet URL's
		foreach( get_post_meta( $post->ID ) as $meta_key => $meta_value ) {

			// If we find a Tweet URL...
			if( false != strstr( $meta_key, 'tweet_url' ) ) {

				// ...render the Tweet URL input elemenet...
				$html .= '<input id="' . $meta_key . '" name="tweet_url[]" class="tweet_url" placeholder="' . __( 'Enter the URL of the Tweet you want to display', 'comment-tweets' ) . '" value="' . $meta_value[0] . '" />';

				// Incremenet the tweet counter
				$tweet_url_counter++;

			} // end if

		} // end foreach

		// If the tweet count is zero, there are no tweets so we need to provide a default input element.
		if( 0 == $tweet_url_counter ) {
			$html .= '<input id="tweet_url_0" name="tweet_url[]" class="tweet_url" placeholder="' . __( 'Enter the URL of the Tweet you want to display', 'comment-tweets' ) . '" value="" />';
		} // end if

		// Close the Tweet URL container
		$html .= '</div><!-- /#tweet-url-container -->';

		// Add the 'Add New Tweet' button
		$html .= '<a href="javascript:;" id="add-new-tweet" class="button">' . __( 'Add New Tweet', 'comments-tweet' ) . '</a>';

		// Render everything to the screen
		echo $html;

	} // end tweet_url_display

	/**
	 * Saves the Tweet URL for the given post.
	 *
	 * @params	$post_id	The ID of the post that we're serializing
	 * @return	If the seccurity checks fail
	 * @since	1.0
	 */
	function save_tweet_url( $post_id ) {

		if( isset( $_POST['tweet_url_nonce'] ) && isset( $_POST['post_type'] ) ) {

			/* -- Serialization Security -- */

			// Don't save if the user hasn't submitted the changes
			if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			} // end if

			// Verify that the input is coming from the proper form
			if( ! wp_verify_nonce( $_POST['tweet_url_nonce'], plugin_basename( __FILE__ ) ) ) {
				return;
			} // end if

			// Make sure the user has permissions to post
			if( 'post' == $_POST['post_type']) {
				if( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				} // end if
			} // end if/else

			/* -- /Serialization Security -- */

			// Set the ID of the current Tweet URL
			$tweet_url_counter = 0;

			// If the first index of the Tweet URL is empty, then there are no tweets...
			if( '' == $_POST['tweet_url'][0] ) {

				// ...so we need to delete all of the post meta for this post
				foreach( get_post_meta( $post_id ) as $meta_key => $meta_value ) {

					if( false != strstr( $meta_key, 'tweet_url' ) ) {
						delete_post_meta( $post_id, $meta_key );
					} // end if

				} // end foreach

			// Otherwise, there are Tweet URL's...
			} else {

				// ...so loop through the list of Tweet URL's
				foreach( $_POST['tweet_url'] as $tweet_url ) {

					// Create an ID for the HTML element
					$tweet_url_id = 'tweet_url_' . $tweet_url_counter;

					// Store the tweet URL (or set an empty string if it doesn't exist
					$tweet_url = isset( $tweet_url ) ? esc_url ( $tweet_url ) : '';

					// First, check to see if the value exists. If so, go ahead and delete it - we don't want to write extra rows into the database
					if( '' != trim( get_post_meta( $post_id, $tweet_url_id, true ) ) ) {
						delete_post_meta( $post_id, $tweet_url_id );
					} // end if

					// Next, update the post meta
					if( '' != trim ( $tweet_url ) ) {
						update_post_meta( $post_id, $tweet_url_id, $tweet_url );
					} // end if

					// Increment the Tweet URL counter
					$tweet_url_counter++;

				} // end foreach

			} // end if

		} // end if

	} // end save_notice

	/*--------------------------------------------*
	 * Display The Tweets
	 *--------------------------------------------*/

	/**
	 * Append the tweets after the actual comment form.
	 *
	 * @since	1.0
	 */
	function display_tweets() {

		// Initialize the Tweet HTML string
		$tweet_html = '';

		// Loop through all of the post meta looking for any Tweet URLs
		foreach( get_post_meta( get_the_ID() ) as $meta_key => $meta_value ) {

			// If we find a URL...
			if( false != strstr( $meta_key, 'tweet_url' ) ) {

				// ...append it to the Tweet HTML string.
				$tweet_html .= '<blockquote class="twitter-tweet">';
					$tweet_html .= '<p>Search API will now always return "real" Twitter user IDs. The with_twitter_user_id parameter is no longer necessary. An era has ended. ^TS</p>&mdash; Twitter API (@twitterapi)';
					$tweet_html .= '<a href="' . $meta_value[0] . '" data-datetime="2011-11-07T20:21:07+00:00">November7, 2011</a>';
				$tweet_html .= '</blockquote>';

			} // end if

		} // end foreach

		// If there are actual tweets to render...
		if( '' != $tweet_html ) {

			// ...then create the HTML container...
			$html = '<div id="comment-tweets">';

				// ...append the Tweet HTML...
				$html .= $tweet_html;
				$html .= '<script src="//platform.twitter.com/widgets.js" charset="utf-8"></script>';

			// ... close the comment tweet elements...
			$html .= '</div><!-- /#comment-tweets -->';

			// ...and render it to the screen.
			echo $html;

		} // end if

	} // end display_tweets

} // end class