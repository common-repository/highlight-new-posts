<?php
if( ! defined( 'HNP::VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

class HNP_Public {

	/**
	 * Registers all hooks for public requests
	 */
	public static function setup_hooks() {
		add_action( 'wp', array( __CLASS__, 'init' ) );
	}

	/**
	 * Runs on the `init` action hook
	 */
	public static function init() {

		// Bail if on singular page
		if( is_singular() ) {
			return;
		}

		// set cookie with last visit time
		self::set_cookie();

		// register scripts & styles
		self::register_scripts();

		// Bail if cookie is not set (first time visitor)
		if( ! isset( $_COOKIE['hnp_last_visit'] ) || $_COOKIE['hnp_last_visit'] === '' ) {
			return;
		}

		// Load styles
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_styles' ) );

		// For every post, check if it's new
		add_action( 'the_post', array( __CLASS__, 'determine_if_post_is_new' ) );
	}

	/**
	 * Determines if a given post in the loop is "new"
	 * If so, adds two filter hooks
	 *
	 * @param $post
	 */
	public static function determine_if_post_is_new( $post ) {

		if( ! in_the_loop() || $post->post_type !== 'post' ) {
			return;
		}

		$last_visit = $_COOKIE['hnp_last_visit'];
		$publish_date = get_post_time( 'U', true, $post->ID );

		// If publish date is newer than last visit, add class 'highlight'
		if ( $publish_date > $last_visit ) {
			add_filter( 'post_class', array( __CLASS__, 'add_post_class' ) );
			add_filter( 'the_title', array( __CLASS__, 'wrap_title' ) );
		}

	}

	/**
	 * Add `new` label to the title of new posts
	 *
	 * @param $title
	 *
	 * @return string
	 */
	public static function wrap_title( $title ) {

		// remove filter as we only want to call it on the current post
		remove_filter( 'the_title', array( __CLASS__, 'wrap_title' ) );

		return $title . '<span class="highlight-new">' . __( 'New' ) . '</span>';
	}

	/**
	 * Add a 'highlight' class to new posts
	 *
	 * @param $classes
	 * @param $class
	 * @param $post_id
	 *
	 * @return array
	 */
	public static function add_post_class( $classes ) {

		// remove filter as we only want to call it on the current post
		remove_filter( 'post_class', array( __CLASS__, 'add_post_class' ) );

		$classes[] = 'highlight';
		return $classes;
	}

	/**
	 * Sets a cookie with the latest time a visitor has visited the website
	 */
	private static function set_cookie() {
		$current_time = current_time( 'timestamp', 1 );
		setcookie( 'hnp_last_visit', $current_time, time() + WEEK_IN_SECONDS * 13, '/' );
	}

	/**
	* Register plugin scripts
	*/
	private static function register_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$url = plugins_url( '/' , HNP::PLUGIN_FILE );
		// stylesheets
		wp_register_style( 'highlight-new-posts', $url . 'assets/css/styles' . $suffix . '.css', array(), HNP::VERSION );
	}

	/**
	* Load plugin styles
	*/
	public static function load_styles() {
		wp_enqueue_style( 'highlight-new-posts' );
	}

}
