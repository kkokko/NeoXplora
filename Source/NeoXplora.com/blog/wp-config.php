<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
define("DB_HOST", "127.0.0.1");
define("DB_USER", "userneo123");
define("DB_PASSWORD", "edu3uvy4e");
define("DB_NAME", "zadmin_neo123");
/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '9we?;SXOh|m~g<N@;Ln1f: %KC+A}pyv7ZC&WRMCsgI[!xjukV:aMq+`+Hz|-A@+');
define('SECURE_AUTH_KEY',  'U)eW3fb(yx+fzB<znZ+s80REW8<lB.=9.wy,s xGDfqgYpc[Ze660Crf,4sOor$7');
define('LOGGED_IN_KEY',    'd:&.^_-j.x)NL%N]Rg dw4w!jFW]5m,TA}M%E~.A<mYE4mz[o ak]U/4/n<G8_lK');
define('NONCE_KEY',        '>=|/M6T61a-9UM%fs5/;2M$-lb;g|DGjV>d*VR2(4gfazz.#w?TnGyXhfN`jB1p-');
define('AUTH_SALT',        'l0_*WGebA>qZ0&$;09O62_<*W@gnlFqg(t5^CT> 1`dhUkP$Hi=HNj,?a+3et}Mt');
define('SECURE_AUTH_SALT', '1>OMtOg*^QcLH`)!9a.-U])>kZO!awDih~h-}pWsU|s/|)oDExOPT#2d1c5|-p]I');
define('LOGGED_IN_SALT',   'QP]DXMAt-V?yJph2uc$8EFS6[&(m<W6K^]0!e3t+&@5M($NdgPm+0D2Nb.T^QmkK');
define('NONCE_SALT',       '<_<dt-=-,[gy_*Tg-3(#9}6k2]5U&_<G`z^qranOVbK6fyh~<Z|-:+|+y.<bzl#k');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
