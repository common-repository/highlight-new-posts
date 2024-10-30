<?php
/*
Plugin Name: Highlight New Posts
Version: 1.0
Plugin URI: http://dannyvankooten.com/
Description: Highlights new posts for returning visitors.
Author: Danny van Kooten (12notions)
Author URI: http://dannyvankooten.com/
Text Domain: highlight-new-posts
Domain Path: /languages/
License: GPL v3

Highlight New Posts
Copyright (C) 2013-2014, Danny van Kooten, hi@dannyvankooten.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

class HNP {
	/**
	 * @const Current version number of the plugin
	 */
	const VERSION = '1.0';

	/**
	 * @const The plugin main file
	 */
	const PLUGIN_FILE = __FILE__;

	/**
	 * Initializes the Highlight New Posts plugin
	 * - Loads area specific code
	 */
	public static function bootstrap() {

		if( ! is_admin() ) {
			// load public code
			require_once dirname( __FILE__ ) . '/includes/class-public.php';
			HNP_Public::setup_hooks();
		} /*elseif( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
			// load admin code
			require_once dirname( __FILE__ ) . '/includes/class-admin.php';
			HNP_Admin::setup_hooks();
		} */

	}
}

// run bootstrap on `plugins_loaded` hook
add_action( 'plugins_loaded', array( 'HNP', 'bootstrap' ) );