<?php
/**
 * Plugin Name: Alt Design Admin Branding
 * Description: Custom admin branding for Alt Design projects.
 * Author: Alt Design Ltd
 * Version: 1.0
 * Text Domain: alt
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! defined( 'ALT_PLUGIN_URL' ) )
	define( 'ALT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( ! defined( 'ALT_PLUGIN_PATH' ) )
	define( 'ALT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'ALT_PLUGIN_BASENAME' ) )
	define( 'ALT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

class alt_admin_branding {

	function __construct() {

		/**
		 * Login functionality
		 */
		add_action( 'login_enqueue_scripts', array( $this, 'alt_login_styles' ), 10 );
		add_action( 'login_head', array( $this, 'alt_remove_shake' ) );
		add_filter( 'login_headerurl', array( $this, 'alt_login_logo_url' ) );
		add_filter( 'login_errors', array( $this, 'alt_failed_login' ) );

		/**
		 * General functionality
		 */
		add_action( 'admin_init', array( $this, 'alt_add_color_scheme' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'alt_admin_styles' ) );

		/**
		 * Dashboard functionality
		 */
		add_filter( 'screen_layout_columns', array( $this, 'alt_screen_layout_columns' ) );
		add_filter( 'get_user_option_screen_layout_dashboard', array( $this, 'alt_screen_layout_dashboard' ) );

		/**
		 * Admin bar branding
		 */
		add_action( 'wp_before_admin_bar_render', array( $this, 'alt_remove_wp_logo' ), 0 );
		add_filter( 'admin_bar_menu', array( $this, 'alt_replace_howdy' ), 25 );

		/**
		 * Footer branding
		 */
		add_filter( 'admin_footer_text', array( $this, 'alt_custom_admin_footer' ) );
		add_filter( 'update_footer', array( $this, 'alt_change_footer_version' ), 9999 );

	}

	/**
	 * Custom login styles
	 */
	function alt_login_styles() {
		wp_enqueue_style( 'alt-admin-login', ALT_PLUGIN_URL . 'css/admin-login.css', false );
	}

	/**
	 * Remove login shake
	 */
	function alt_remove_shake() {
		remove_action( 'login_head', 'wp_shake_js', 12 );
	}

	/**
	 * Custom login logo link url
	 */
	function alt_login_logo_url( $url ) {
		return 'http://www.alt-design.net/';
	}

	/**
	 * Change login failed message
	 */
	function alt_failed_login() {
		return 'The login information you have entered is incorrect.';
	}

	/**
	 * Add Alt Design colour scheme
	 */
	function alt_add_color_scheme() {
		wp_admin_css_color(
			'alt-design',
			__( 'Alt Design', 'alt-design-color-scheme' ),
			ALT_PLUGIN_URL . 'css/admin-color-scheme.css',
			array( '#25282b', '#363b3f', '#ff6600', '#ff6600' )
		);
	}

	/**
	 * Register custom styles & javascripts
	 */
	function alt_admin_styles() {
		global $wp_styles;

		wp_enqueue_style( 'alt-admin-footer', ALT_PLUGIN_URL . 'css/admin-footer.css' );

		$color_scheme = get_user_option( 'admin_color' );

		if ( 'alt-design' === $color_scheme || in_array( get_current_screen()->base, array( 'profile', 'profile-network' ) ) ) {
			$wp_styles->registered[ 'colors' ]->deps[] = 'colors-fresh';
		}
	}

	/**
	 * Single column dashboard
	 */
	function alt_screen_layout_columns( $columns ) {
		$columns['dashboard'] = 1;
		return $columns;
	}

	function alt_screen_layout_dashboard() {
		return 1;
	}

	/**
	 * Remove WordPress logo from admin bar
	 */
	function alt_remove_wp_logo() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'wp-logo' );
	}

	/**
	 * Replace 'Howdy' message.
	 */
	function alt_replace_howdy( $wp_admin_bar ) {
		$my_account=$wp_admin_bar->get_node( 'my-account' );
		$newtitle = str_replace( 'Howdy,', 'Ayup,', $my_account->title );
		$wp_admin_bar->add_node( array(
			'id' => 'my-account',
			'title' => $newtitle,
		) );
	}

	/**
	 * Custom admin footer
	 */
	function alt_custom_admin_footer() {
		echo '<strong>' . get_bloginfo( 'name' ) . ';</strong> A site by <a href="http://www.alt-design.net/" title="Alt Design" target="_blank">Alt</a>.';
	}

	/**
	 * Custom admin footer version
	 */
	function alt_change_footer_version() {
		return '<span class="alt-footer-logo">Alt</span>';
	}

}

new alt_admin_branding();