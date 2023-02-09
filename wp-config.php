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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'Enfold' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         '/wo$9gr8{.bc/Z/S$90Y5ik<RQU:Ry@OVc9=A%)f:k%rCy!)YhL>Moq-~88le6%?' );
define( 'SECURE_AUTH_KEY',  '|9eBCa=zaYY?i$,u(&NZ]<#}z6EC^j==sr(uYfggGr^aY:NGn3UqiFt0<_0N3o|}' );
define( 'LOGGED_IN_KEY',    'WEbtXV00|h:K-,L@nhE:48g=qa:=<:]?s1f1uJ/ScYb`/5<kd)74HxEb~ -lnP>I' );
define( 'NONCE_KEY',        'C]_L6;?9*ml*[e]T`b(@b*-IK7s2bP0#T3rzx~e+3&ZZM0wVjAoZ@q5$]3qP6m0b' );
define( 'AUTH_SALT',        'p0PV@?O;m>}|sK2s]NaL:fl cP{;YFJpf}8*l%n]?A<={6+[C%x22E;h16e7Lw?>' );
define( 'SECURE_AUTH_SALT', 'i8Q_M&5z!stNNB93?$ONhIZ]diN($FQ[Wm6D8HNW?zo*Qi^w1#)g6UlW?Pr%ZJY5' );
define( 'LOGGED_IN_SALT',   'c&&.lcYf=K!QGr|(D[]JKv?bX9W</hb}T;vC,;c,UkqQa<4/Y=BO&q,|>bQEtpZ-' );
define( 'NONCE_SALT',       'x#h)11JPo EFkhKeti0jU`4O?)h9>s^WZ{)p.J7NN<B3&(Vt_W[(UBe$Y;&~rpc+' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_Enfold';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
