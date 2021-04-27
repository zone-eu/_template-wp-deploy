<?php
/** be-gone.php must-lose plugin
 * @author: Peeter Marvet (peeter@zone.ee)
 * Date: 30.07.2020
 * @version 1.1
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL
 *
 * This is a must-use plugin to be placed in wp-content/mu-plugins to:
 * - cleanup <head> (feeds, pingback etc)
 * - disable xml-rpc
 * - disable REST api
 * - disable emoticons
 * - disable comments
 * - allow updates
 *
 * v1.1
 * - initial version
 * v1.0
 * - initial version
 */

// vulnerabilities kill, updates may hurt - but make you stronger

add_filter( 'auto_update_plugin', '__return_true' );
add_filter( 'auto_update_theme', '__return_true' );

// disable file editing in WP (set to false in wp-config to override)

if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

// disable also plugin/theme/core update and install (set to false in wp-config to override)
// IMORTANT: make sure your updates are working using wp-cli (Elementor PRO seems to check it )!

if ( ! defined( 'DISALLOW_FILE_MODS' ) && php_sapi_name() !== 'cli') {
	define( 'DISALLOW_FILE_MODS', true );
}

// be clarified, not just woke - if file modifications are not allowed credentials may be required ... but shall not be provided
// (disables WP_Upgrader and similar functionality using Filesystem API, media library is not affected)

if ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS === true && php_sapi_name() !== 'cli' ) {
	add_filter( 'request_filesystem_credentials', '__return_false', 10, 1 );
}

// cron should be run using crontab, not on every request (set to false in wp-config to override)

if ( ! defined( 'DISABLE_WP_CRON' ) ) {
	define( 'DISABLE_WP_CRON', true );
}

// no comments / ej kommentaari!

add_filter( 'comments_open', '__return_false', 20, 2 );
add_filter( 'pings_open', '__return_false', 20, 2 );

// why publish the fact that we have Stream active?

add_filter( 'wp_stream_frontend_indicator', '__return_false', 20, 2 );

// xml's dead baby, xml's is dead

add_filter( 'xmlrpc_enabled', '__return_false' );

add_filter( 'wp_headers', 'krt_disable_x_pingback' );
function krt_disable_x_pingback( $headers ) {
	unset( $headers['X-Pingback'] );

	return $headers;
}

add_action( 'after_setup_theme', 'krt_head_cleanup' );

if ( ! function_exists( 'krt_head_cleanup' ) ) {
	function krt_head_cleanup() {
		add_theme_support( 'automatic-feed-links' );
		add_filter( 'feed_links_show_comments_feed', '__return_false' );

		remove_action( 'wp_head', 'feed_links', 2 ); // post and comment feeds
		remove_action( 'wp_head', 'feed_links_extra', 3 ); // category, author, and other extra feeds

		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );

		remove_action( 'wp_head', 'index_rel_link' ); // index link
		remove_action( 'wp_head', 'parent_post_rel_link', 10 ); // prev link
		remove_action( 'wp_head', 'start_post_rel_link', 10 ); // start link
		remove_action( 'wp_head', 'adjacent_posts_rel_link', 10 ); // adjacent post links


		// Turn off oEmbed auto discovery.
		add_filter( 'embed_oembed_discover', '__return_false' );

		// Don't filter oEmbed results.
		remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

		// Remove oEmbed discovery links.
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

		// Remove oEmbed-specific JavaScript from the front-end and back-end.
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );

	}
}

// rest in pieces

remove_action( 'template_redirect', 'rest_output_link_header', 11 );

remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );

add_filter( 'rest_authentication_errors', 'krt_disable_wp_rest_api' );

function krt_disable_wp_rest_api( $access ) {
	if ( ! is_user_logged_in() ) {
		$message = apply_filters( 'disable_wp_rest_api_error', __( 'REST API restricted to authenticated users.', 'disable-wp-rest-api' ) );

		return new WP_Error( 'rest_login_required', $message, array( 'status' => rest_authorization_required_code() ) );
	}

	return $access;
}

// we didn't have emotions; now we don't have emoticons either

add_action( 'init', 'krt_disable_emojis' );

function krt_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}

// disable user enumeration & author archives
// based on https://wp-mix.com/wordpress-disable-author-archives/ example

remove_filter( 'template_redirect', 'redirect_canonical' );
add_action( 'template_redirect', 'krt_disable_author_archives' );

function krt_disable_author_archives() {

	if (
		is_author() || isset( $_GET['author'] )
		// use && isset(...) to prevent enumeration but keep archives
	) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
	} else {
		redirect_canonical();
	}
}

// for multisite make sure missing pages are not redirected due to NOBLOGREDIRECT
// remove_action( 'template_redirect', 'maybe_redirect_404' ); if

// customize cookie names

	if ( ! defined( 'COOKIEHASH' ) ) {
		$siteurl = get_site_option( 'siteurl' );
		if ( $siteurl ) {
			define( 'COOKIEHASH', md5( $siteurl ) );
		} else {
			define( 'COOKIEHASH', '' );
		}
	}

	if ( ! defined( 'USER_COOKIE' ) ) {
		define( 'USER_COOKIE', '__Host-wordpressuser_' . COOKIEHASH );
	}

	if ( ! defined( 'PASS_COOKIE' ) ) {
		define( 'PASS_COOKIE', '__Host-wordpresspass_' . COOKIEHASH );
	}

	if ( ! defined( 'AUTH_COOKIE' ) ) {
		define( 'AUTH_COOKIE', '__Host-wordpress_' . COOKIEHASH );
	}

	if ( ! defined( 'SECURE_AUTH_COOKIE' ) ) {
		// this is set with path, can't have __Host
		define( 'SECURE_AUTH_COOKIE', '__Secure-wordpress_sec_' . COOKIEHASH );
	}

	if ( ! defined( 'LOGGED_IN_COOKIE' ) ) {
		define( 'LOGGED_IN_COOKIE', '__Host-wordpress_logged_in_' . COOKIEHASH );
	}

	if ( ! defined( 'TEST_COOKIE' ) ) {
		define( 'TEST_COOKIE', '__Host-wordpress_test_cookie' );
	}

	if ( ! defined( 'RECOVERY_MODE_COOKIE' ) ) {
		/**
		 * @since 5.2.0
		 */
		define( 'RECOVERY_MODE_COOKIE', '__Host-wordpress_rec_' . COOKIEHASH );
	}
