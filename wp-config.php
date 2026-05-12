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
define( 'DB_NAME', 'exercise-1' );

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
define( 'AUTH_KEY',         ';n<L%t4Gu|-R$07c<H1lr@:4b1lX0#%,LMZ`?ZJ%Fwv=s3Wv!kU^wy>x^x/!svq.' );
define( 'SECURE_AUTH_KEY',  'Cv^jCkx1K+L2[B!6en:d?lK#s)W#s?04Ig(`~cR!FRe}d_Hp-X5-J(Ca=jc*C4wE' );
define( 'LOGGED_IN_KEY',    '_:zUQ`[d6.JyLq:I 7a0)A(7q1u+E~N_3N}JR-PQ.{Xn<0EE|YvRM2ie+kZ;QgJD' );
define( 'NONCE_KEY',        '6l~_-nSL9AdfnsY-4&0LCegIY`O7}ZZC!= tDa?T=83O.I#[ dXoa3GVvG`1#rLu' );
define( 'AUTH_SALT',        '~=1;?ill=jR07|~T98~_>MDiU~?-s(ZAnu1h(tQkgN1C9vTrr8:]T]};2o6iC6-o' );
define( 'SECURE_AUTH_SALT', 'O6Fl,{nxvZZU|zw5UYgYHLFBPpw_KbFU,U/RU7sDt_sSwGb3i9`Xez!Z|#OBV7%u' );
define( 'LOGGED_IN_SALT',   'povC_jFR$N_q?v.yAdR>mKa@+VFJ]s^TFa-mv@APpM !ZBW$Cg%~|yz`vhzs<AI:' );
define( 'NONCE_SALT',       'i~+j=XRzOEX~0Awc15Y~JdvDcv9!y!jn}wA=|}&EYFyB`v>IM)soS(,{7u4j#2~e' );

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
$table_prefix = 'e1_';

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
