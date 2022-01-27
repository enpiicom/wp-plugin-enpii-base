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
define( 'DB_NAME', 'enpii_base' );

/** Database username */
define( 'DB_USER', 'root1' );

/** Database password */
define( 'DB_PASSWORD', 'root1' );

/** Database hostname */
define( 'DB_HOST', '172.17.0.1' );

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
define( 'AUTH_KEY',         'zUa~H|brC$K TxM:ZCZ[>p5iKQaB/*-6{5dt}dF,KyBD=F^59RC@J0gT{~9itQ/@' );
define( 'SECURE_AUTH_KEY',  '=fN}Ja*<#8i_;kxi|tB |:?F@~CpeQvnj6z67ZhIY;8{:]9$05HX[I;_GixYOyu|' );
define( 'LOGGED_IN_KEY',    'SF YsEuO{!P{SZ]jq9:c.`XX#A2htK0#ekIaB,nAr#DGBq];L?[]rseNv,~gq[f_' );
define( 'NONCE_KEY',        ')kgq}TA?zo|Y934-oY]H&OXX;/!UxoV[a,&mv8+<BVHk[Qru1O9CLy!N! HyY0~T' );
define( 'AUTH_SALT',        '1FE3^5KQ<3.kZb8S!Fi(Yd5RUxrLEQ//*pZqzzf$q`  ^,+c_fD&?E. dO|BIbEk' );
define( 'SECURE_AUTH_SALT', '@]_LO=zI~,}wCr?8(C5`_WUZg<cl-I,*o7y:|MV6_?Pa6O`?(4C<y4^Lye++L$Cx' );
define( 'LOGGED_IN_SALT',   '+8i{[tpGSG0b=+.R6}hrzulmUAw_b`*e:$L:v;~asWDJh(F,O<T.+m{S@9V7rAiB' );
define( 'NONCE_SALT',       'SX|(WvvgOsa~r-F:|H(o,fy2%+GMTGZM@VRea{$CmAFU`W;|4i7Xq2xR@VYEBes#' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
