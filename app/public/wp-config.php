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
define( 'AUTH_KEY',          'Q&X.I1_(@}kl%ck`e5#0gwb&.0Y*FtbXiIc9fZY^LYmOf3u>HF5t2,-:(&8E^k!e' );
define( 'SECURE_AUTH_KEY',   'l1wisd$d#}Bkz{C$d@uHOrw8ONn7A#y|{+p?CA)[I<jz*^-DF}ktl*v{]pCL 10H' );
define( 'LOGGED_IN_KEY',     'C)T)S&<=1;mE75-,yT-pr[kH^A~h#8[J~pJ7;Cujt~.u4F*t}*0uBz_B-+rWse(K' );
define( 'NONCE_KEY',         'L~WTv,Unz$O/sF[9aP].D7?s&.[s fY(8x_$`IIX;.H,y_a{RIbpb:d#oW,}D1Pt' );
define( 'AUTH_SALT',         '{M1QC>$(.qZMmW3Rk#epU5S?CR^ hNvFmta9:cTC*}A|@bNtX/X~fWg~tTqO?mb)' );
define( 'SECURE_AUTH_SALT',  ']OLr~(l `T?)=d)z-jV=:k^h(YQ]<,?IT *?Q9PLn7+[;r4VsT^mS]z=7kOfm#7I' );
define( 'LOGGED_IN_SALT',    '9U#<h%2KdT.Q0v5Z- .b~<Hyhedj`.3}nw2*A+k$O}HI}$JGgJFG3)tq lOxw:S>' );
define( 'NONCE_SALT',        '|ZUeL`Lo<EIKB[{HqEORTVP5|!nLgb>n=nn_Ex$OYQ~2n|B:+=4lm|(!P?j4b1py' );
define( 'WP_CACHE_KEY_SALT', '8%2NRJHBdJ|<O-OE![Y0&G>DWQa_}q[D|N$hZ=&rplL6ivdMq=s,@EX1p12q?XR2' );


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
