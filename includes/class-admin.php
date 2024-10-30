<?php
if( ! defined( 'HNP::VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

class HNP_Admin {

	public static function setup_hooks() {
		add_action( 'init', array( 'HNP_Admin', 'load_textdomain' ) );
	}

	/**
	 * Load the plugin textdomain
	 */
	public static function load_textdomain() {
		load_plugin_textdomain( 'highlight-new-posts', false, dirname( HNP::PLUGIN_FILE ) . '/languages/' );
	}

}
