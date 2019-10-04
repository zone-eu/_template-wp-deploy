<?php

// Multisite configuration

//define( 'MULTISITE', true );

//define( 'SUBDOMAIN_INSTALL', true );
//define( 'PATH_CURRENT_SITE', "/" );
//define( 'SITE_ID_CURRENT_SITE', 1 );
//define( 'BLOG_ID_CURRENT_SITE', 1 );

// Deployment-specific configuration

if ( file_exists( dirname( __FILE__ ) . '/local-config.php' ) ) {

	include( dirname( __FILE__ ) . '/local-config.php' );

} else {

	die( 'Local configuration missing.' );

}

define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/**#@-*/


/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
