<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'park103_db' );

/** Database username */
define( 'DB_USER', 'park103user' );

/** Database password */
define( 'DB_PASSWORD', 'Password' );

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
define( 'AUTH_KEY',         ';g!0Yc5f<06F/:#6uJhOueZJVaO&Lg*][qPo}M^$}p5r-c!,gyd`>O u}l-?K4*x' );
define( 'SECURE_AUTH_KEY',  'y3fVKc=wv^p@wU>dohK7kA?SQlPI~=Io|<*tgtP&c%P]ReBB&cc$XqJB#IR6UX9-' );
define( 'LOGGED_IN_KEY',    '&=`/^Tk<3a|$Z[Y?EC$XRA%YAVy)F6HOv%1a/sr680JydT{Dj^Fj8%~=A:`O#CI>' );
define( 'NONCE_KEY',        '$t)F5]/TL|H=HBnq*YoIK1QPrEk#=re%RXoqDE[qoK[C:K7 v~Li)qj</j[*I[yN' );
define( 'AUTH_SALT',        'jOo*;@J0!44q=},#Y&gB-b{E_<bfsG]T@&{YnjB$`eOez3,pfNr=5:$!H}MB78]R' );
define( 'SECURE_AUTH_SALT', 'OOHfMQ(IC!)ki>rIGquTnu)ttWh~nHCxsTg(i+59=x4a7Q ReC|efbp+JzpWW*F#' );
define( 'LOGGED_IN_SALT',   'n^%{hEPZH!>8872H 6d9~4*l8&jU;l4u%2s?az(5KW`[+S^Hi*grn3u#RXg9!&81' );
define( 'NONCE_SALT',       '{8;av!Z6v8H_QPYv5q`0YfQf[[W.<^XEg1h(/Gi]@yCO#PusaH99j&an4%2wJ-B0' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
