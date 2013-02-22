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
/** The name of the database for WordPress */
define('DB_NAME', 'pingwest');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'shbufxABg6uPUJ3z7TBmHQvDGPu4PQQtB9nqyMfGBEuGiO1nSycvj3htHOoqpIoK');
define('SECURE_AUTH_KEY',  'nXClNZZsmNKn9JnC8KepHXaVnUDzITTaYTaalRpYpGpbQJ3B9DBzZMTyE5CGRWAR');
define('LOGGED_IN_KEY',    'jXAGi9l287BQi7OXP6puMdMGltgn3DjvuJPCIefJKTPOQgkzJCCLbkYcIC3mNer8');
define('NONCE_KEY',        'm82i7CqPcj7oPhE0ieO7PvyaNYaaevq2HsPzLryCmWrxce8fsIQpNOvfPwnfI90X');
define('AUTH_SALT',        '56RsEK3601iM7T18mMA53acL5VWdJOXeBhTeJSOpVbX3DRFYrdFKU6ssZy6iUF8P');
define('SECURE_AUTH_SALT', 'U4B0Z1fuqUy38BGRDtwuWcHngeHi0JvYKs39LM5EysRoMGnkv2rTetmiRSdVn2am');
define('LOGGED_IN_SALT',   'O1JWSGRcY6tjAb2qeR7faUVjvzdHARrf2nk6G48oq4yhDpic8UdQ5IJEoXLkEy0s');
define('NONCE_SALT',       'O78k9k9aK9lshXmEvp4PRdWysotqufqqenCOtQvgaYF5YTNOwy1tG2TwZzbTayZN');

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
define('WPLANG', 'zh_CN');

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
