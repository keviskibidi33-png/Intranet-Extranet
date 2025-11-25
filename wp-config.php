<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'grersced_wp588' );

/** Database username */
define( 'DB_USER', 'grersced_wp588' );

/** Database password */
define( 'DB_PASSWORD', 'r23S@82@pd' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'aewqrtdxycik2hmpbvuphoazjsy4aa8a06yqbaf2i54cv9uiexowimitd6hki9xi' );
define( 'SECURE_AUTH_KEY',  'ubq5v1n4holemdclz6iri9s7vosa9yqapledjmcodr9o9gytxizgvznrmea5ha00' );
define( 'LOGGED_IN_KEY',    'dfhm4ux5fznpcb2ohxgguq17tzcvxahuhnxircsjkpevou9fzujwqys559dqvsjw' );
define( 'NONCE_KEY',        'y7absn9ydkzublxmqvxwy53exzqxfjuzd4kgy7b2prqt5y7jl4ljv5jwkov8bspv' );
define( 'AUTH_SALT',        'e0elzmu5vpf8oioz4pwbosgg3s9l0ztlvbvop0ruwbzprainezmgvnqfhebjy0nf' );
define( 'SECURE_AUTH_SALT', 'k2maginly0uobxqghaaqbsswswh6ebitmnci8em320k74csxeru6yw3wqfxp2lul' );
define( 'LOGGED_IN_SALT',   '9qwqe7mh5rv8atva7umbgfpq2daviomfsoz5xpviuedymsap49tw4gj8ds57lr1l' );
define( 'NONCE_SALT',       'riudtigoczevivwwzgsvnsihfxerb8zflbi8ef8f8d8m5xa7lv1opai9f8oobmoc' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wpss_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
