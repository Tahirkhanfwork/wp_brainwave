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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_brainwave' );

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
define( 'AUTH_KEY',         ')j:OvZ; X)PU-h^EDd_IUa$Ya<`/RQ+!Ui8~qBlzhI}/X;A`=$@]%w(&v67fs8Wz' );
define( 'SECURE_AUTH_KEY',  '`$ibhb3.N,<z,ERD#;Ke{35^ykioc6-[&bZ*zxenRx(6`byF:)QeujhO2I7#aniA' );
define( 'LOGGED_IN_KEY',    '%/4w@S.y^&O+.dc}?Me-QD&R z^GU%h1NYfq=>o;:=>,+F0*G5JEiD)(x($Gl5-V' );
define( 'NONCE_KEY',        'Q/N;*PnpzihlLib3cY,bi`y4%eN|0#5[VMf(9ZNV[uTt]_K,KR/v P9HX/tVXAe2' );
define( 'AUTH_SALT',        'YPY/i-/sa>=%&]kGw>j&JpROv:MTUi>|OYidx_DT8jD{,d}lV9.cmVXEq^V?s:NM' );
define( 'SECURE_AUTH_SALT', 'IWtTO_P6;V.tf_QI}V[_E_SAM4Ny,7bHP78&a<0ZB5Sr+7Ap{75eoC(W3k%EoMj$' );
define( 'LOGGED_IN_SALT',   '{VIZ4I942d*J>(HI6W=M3=T_lS2`hu]CIm.cN>RHO6-@!0:FAV2P+_-NX mhl=S8' );
define( 'NONCE_SALT',       'I{w5wd>Q=DybVQaA[UT2hIHsXD#AqP29b5&w[Zj2Y2ervs[nmwmNK+!gF-=|RFCi' );

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
