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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '`$ Yb!}]-{~=1V|}y9aIf)F)<o/E5bFA-66Vgx^|%uC-;0lSFy.k/xe{#f2VMgd~' );
define( 'SECURE_AUTH_KEY',   '6:a)7]yDn%_fhF0eWq|$Ah2)#2zI]tZ_&bEA.XD1Sq7ws>?~!/E2 fo/.*CHTHYx' );
define( 'LOGGED_IN_KEY',     'GeQ9LJJG;hUEInm[*r`MyP5~>lL|9tA!M[VtVX>A[P3W/ro(*z.-*Y?v;ISuaLu|' );
define( 'NONCE_KEY',         'n|.dIf-WKK$yq[i( wV%$+[2x &H<#67?v@.8/(/}>?#77UOs<*rc*d.[F-DUA` ' );
define( 'AUTH_SALT',         'hq~B2]z5VbU(_sh%UC6I[W,{y,|B<VW?Xh[z8=Q$;$dXk&|JzOg|-ieg.Tpp[:)h' );
define( 'SECURE_AUTH_SALT',  'x0=D(x{<,[Yx2vo<pGOyAC|9THacC*N f0KIe;xr&z2ZmbHf;C]4,WYsTjKBzdCj' );
define( 'LOGGED_IN_SALT',    '974]gJ:nR&u}5BZO&da{ X1IMxh%_X,I.HS/x}8zipIlq>0_>{D`:J{#+CPWxEcY' );
define( 'NONCE_SALT',        '@p4@a&ue~b[!`$.y>od_J|-|Jq05$Xya4^yPntk9a...A:`wiMf[d~<jknx(1*pJ' );
define( 'WP_CACHE_KEY_SALT', 'MX)SSAD wgqQ*HLIV$O;oR[)_P$^K)^]6_xaw.G) {rA}JPjo:o>hUCyv<[mKv&V' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
