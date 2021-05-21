<?php
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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define('AUTH_KEY',         'OZomwkPySvfMfq7Cd8XfgZFRrVPUQfNWE1tWJ46qAkiPPuRIzyrqw5aaq2XW3OJKoO1PToZALF1Nhro3O4RGTQ==');
define('SECURE_AUTH_KEY',  '0DdSSMffkA+AAkPpA4HVajwqLLb2gVFd30FvosX7vqJocQ88b4iCOJestTLFjro4tqBw/HtUBlJoAWWCxxvbzg==');
define('LOGGED_IN_KEY',    'aVIme1YFWn4T0Rr/LiuiWwuQPa/ArGrkIUK2zu4uPocFhytHSTjJiex6YIV9HbEZ/kCMwJKdnqDSuKYvSOJoXw==');
define('NONCE_KEY',        'ePv+cxw2CK6lNwWXrhXVXhNdjazJDKa+cM0tkROSvAS8KBk7UpRMas2rbfrHNdDlazJxfSt2VgyOlF/dxgUOXw==');
define('AUTH_SALT',        'hpHjJqpjTMpAIKoX57+/gUOBFttO9Luo0/BBrqbc5ld0qaCfrMnWDa/j9yR1v3X45vFev7E1UZOslSFJHOX8BQ==');
define('SECURE_AUTH_SALT', 'Tx7K5zb4k4ribITftS03TWnJPhmehojKz89YX77Ww4T+Hfp1aVdv3VG0hCq3ZqXbcsk9pMMDfMg9/VhaUIMriw==');
define('LOGGED_IN_SALT',   'UMA2iLh6gm2DyALx0ueFumQ7wPJY3oIXtuEyuJz5CyXRA9I6UlsiZsVjL4yzto4Z4x/uQjqo/lHLPt+g+6wlzg==');
define('NONCE_SALT',       'GF6lnYktaY52h28Iq1y9POZBtc5zsSAE/JoGJCqJi/5PzCNQ2PVRk/XYRtnMdkEMwG1Zg8Odu4HVedXI0PXSEA==');



define('JWT_AUTH_SECRET_KEY', 'M!1g925l|OBABn/O}j5&9y+I(8wF*)k`_9E~2@:km#D76,F$Z:fp7Lh+cSy2uzG==');
define('JWT_AUTH_CORS_ENABLE', true);

define('URL_FRONT','https://justb-coachingbar.web.app' );
define('DAYS_JWT_EXPIRATION','2');//testing now
define('URL_GAS_CALENDAR', 'https://script.google.com/macros/s/AKfycbz58HmH6CEJJRkIzm72aobTeyS9WscMcMPZLvQHYSohYFRjK_hKLE0CkldlTWWrB5qC/exec');
define('URL_GAS_CONTACT', 'https://script.google.com/macros/s/AKfycbyOT2JgOScsCagTb2ss2PbHJL_B5COcNSSxvga8mFMwrFckcdwSRiXm6FGCIdh_0RG-8Q/exec');
define('ID_GOOGLE_CALENDAR_RECEIVING_APPDATA', '3v2ajjvitr191q0bavbvqjje1c@group.calendar.google.com' );
define('ID_GOOGLE_CALENDAR_SAISIE_MANUELLE', 'justbapplication@gmail.com');







/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
