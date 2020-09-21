<?php

######## EDNS WP CUSTOM ########
$_SERVER['HTTPS']='on';
define('FORCE_SSL_ADMIN', true);
define('FORCE_SSL', true);
define('WP_HOME', 'https://servent.lu');
define('WP_SITEURL', 'https://servent.lu');

######## END EDNS WP CUSTOM ########

define('WP_AUTO_UPDATE_CORE', 'minor');// This setting is required to make sure that WordPress updates can be properly managed in WordPress Toolkit. Remove this line if this WordPress website is not managed by WordPress Toolkit anymore.
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_st7un' );

/** MySQL database username */
define( 'DB_USER', 'wp_snkk9' );

/** MySQL database password */
define( 'DB_PASSWORD', 'c9I2P#Lhf0' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '0*xG!y~m2@L:*lvnDh!XOFB0]J//7[32*OP6Q|ZmSm6hSI9+k_q6_k@]R*s:66I8');
define('SECURE_AUTH_KEY', '([QH2PS-@hVN:N#aV|Z!GA(3RVOP5t2hq4mJU5vpL64%fl8:5Y9g*-M*nS)73J6v');
define('LOGGED_IN_KEY', 'O~31G6Ik;9Y/IE5+G1It9&8GPYZ+U6G:3Qd8GT21#5k2*7Oy3(x3([18r%n3PY03');
define('NONCE_KEY', '7WVP[+Jw0m17C~0L&e-2hPiMkSp!~(B@6Egu&|LO1K#u/iu[)817jTg1QB609wfo');
define('AUTH_SALT', 'Gn9(FQ/572*mo84h_4|6F3OowKyWK2emYU|OO0oO7372piQY+@n_#s5z~PeVFfe3');
define('SECURE_AUTH_SALT', ':9R810):5xfAdJs1IPyO7%6HAYfC+;15A|5%7*EW7c3rH@*[A;@*KU6[5*3pcoo4');
define('LOGGED_IN_SALT', 'spYQ9L]b22xv8e[0TsvtOJl[66cB3n1)[4%jG~5JI]1h@0Fm|5q(5RC:rBWtgk1S');
define('NONCE_SALT', 'ZU(Y2]lR_I]Nm(e*~*aFndSIHVh7A1(]6@|97o_i939DJWD1Ve(R-;876xhR[0;b');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'Bug0h_';


define('WP_ALLOW_MULTISITE', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';