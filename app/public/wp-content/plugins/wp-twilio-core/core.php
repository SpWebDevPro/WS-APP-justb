<?php
/**
 * Plugin Name: WP Twilio Core
 * Plugin URI: https://wpsms.io/
 * Description: Send SMS notifications to users using Twilio API.
 * Version: 1.2.6
 * Author: WPSMS.IO
 * Author URI: https://wpsms.io
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

define( 'TWL_CORE_VERSION', '1.2.6' );
define( 'TWL_CORE_OPTION', 'twl_option' );
define( 'TWL_CORE_OPTION_PAGE', 'twilio-options' );
define( 'TWL_CORE_SETTING', 'twilio-options' );
define( 'TWL_LOGS_OPTION', 'twl_logs' );

if( !defined( 'TWL_TD' ) ) {
	define( 'TWL_TD', 'twilio-core' );
}

if( !defined( 'TWL_PATH' ) ) {
	define( 'TWL_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! function_exists( 'twl_freemius' ) ) {
    // Create a helper function for easy SDK access.
    function twl_freemius() {
        global $twl_freemius;

        if ( ! isset( $twl_freemius ) ) {
            // Include Freemius SDK.
            require_once( TWL_PATH . 'freemius/start.php' );

            $twl_freemius = fs_dynamic_init( array(
                'id'                  => '2894',
                'slug'                => 'wp-twilio-core',
                'type'                => 'plugin',
                'public_key'          => 'pk_41d58e132e8e380880894f44eb5ca',
                'is_premium'          => false,
                'has_addons'          => true,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'slug'           => 'twilio-options',
                    'support'        => false,
                ),
            ) );
        }

        return $twl_freemius;
    }
}

require_once( TWL_PATH . 'twilio-php/src/Twilio/autoload.php' );
require_once( TWL_PATH . 'helpers.php' );
require_once( TWL_PATH . 'url-shorten.php' );
if ( is_admin() ) {
	require_once( TWL_PATH . 'admin-pages.php' );
}

class WP_Twilio_Core {
	private static $instance;
	private $page_url;

	private function __construct() {
		$this->set_page_url();
		// Init Freemius.
		twl_freemius();
		// Signal that SDK was initiated.
		do_action( 'twl_freemius_loaded' );
	}

	public function init() {
		$options = $this->get_options();

		load_plugin_textdomain( TWL_TD, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		if ( is_admin() ) {
			/** Settings Pages **/
			add_action( 'admin_init', array( $this, 'register_settings' ), 1000 );
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 1000 );

		}

		/** User Profile Settings **/
		if( isset( $options['mobile_field'] ) && $options['mobile_field'] ) {
			add_filter( 'user_contactmethods', 'twl_add_contact_item', 10 );
		}
	}

	/**
	 * Add the Twilio item to the Settings menu
	 * @return void
	 * @access public
	 */
	public function admin_menu() {
		add_menu_page( __( 'Twilio', TWL_TD ), __( 'Twilio', TWL_TD ), 'administrator', TWL_CORE_OPTION_PAGE, array( $this, 'display_tabs' ), 'dashicons-email-alt', 91 );
	}

	/**
	 * Determines what tab is being displayed, and executes the display of that tab
	 * @return void
	 * @access public
	 */
	public function display_tabs() {
		$options = $this->get_options();
		$tabs = $this->get_tabs();
		$current = ( !isset( $_GET['tab'] ) ) ? current( array_keys( $tabs ) ) : $_GET['tab'];
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div><h2><?php _e( 'Twilio for WordPress', TWL_TD ); ?></h2>
			<h2 class="nav-tab-wrapper"><?php
			foreach( $tabs as $tab => $name ) {
				$classes = array( 'nav-tab' );
				if( $tab == $current ) {
					$classes[] = 'nav-tab-active';
				}
				$href = esc_url( add_query_arg( 'tab', $tab, $this->page_url ) );
				echo '<a class="' . implode( ' ', $classes ) . '" href="' . $href . '">' . $name . '</a>';
			}
			?>
			</h2>

			<?php do_action( 'twl_display_tab', $current, $this->page_url ); ?>
		</div>
		<?php
	}

	/**
	 * Saves the URL of the plugin settings page into the class property
	 * @return void
	 * @access public
	 */
	public function set_page_url() {
		$base = admin_url( 'admin.php' );
		$this->page_url = add_query_arg( 'page',  TWL_CORE_OPTION_PAGE, $base );
	}

	/**
	 * Returns an array of settings tabs, extensible via a filter
	 * @return void
	 * @access public
	 */
	public function get_tabs() {
		$default_tabs = array(
			'general' => __( 'Settings', TWL_TD ),
			'logs' => __( 'Logs', TWL_TD ),
			'test' => __( 'Test', TWL_TD ),
			//'extensions' => __( 'Get Extensions', TWL_TD ),
		);
		return apply_filters( 'twl_settings_tabs', $default_tabs );
	}

	/**
	 * Register/Whitelist our settings on the settings page, allow extensions and other plugins to hook into this
	 * @return void
	 * @access public
	 */
	public function register_settings() {
		register_setting( TWL_CORE_SETTING, TWL_CORE_OPTION, 'twl_sanitize_option' );
		do_action( 'twl_register_additional_settings' );
	}

	/**
	 * Original get_options unifier
	 * @return array List of options
	 * @access public
	 */
	public function get_options() {
		return twl_get_options();
	}

	/**
	 * Get the singleton instance of our plugin
	 * @return class The Instance
	 * @access public
	 */
	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new WP_Twilio_Core();
		}

		return self::$instance;
	}

	/**
	 * Adds the options to the options table
	 * @return void
	 * @access public
	 */
	public static function plugin_activated() {
		add_option( TWL_CORE_OPTION, twl_get_defaults() );
		add_option( TWL_LOGS_OPTION, '' );
	}

	/**
	 * Deletes the options to the options table
	 * @return void
	 * @access public
	 */
	public static function plugin_uninstalled() {
		delete_option( TWL_CORE_OPTION );
		delete_option( TWL_LOGS_OPTION );
	}

}

$twl_instance = WP_Twilio_Core::get_instance();
add_action( 'plugins_loaded', array( $twl_instance, 'init' ) );
register_activation_hook( __FILE__, array( 'WP_Twilio_Core', 'plugin_activated' ) );
twl_freemius()->add_action( 'after_uninstall', array( 'WP_Twilio_Core', 'plugin_uninstalled' ) );




//Admin notices

// Load notice css
add_action('admin_enqueue_scripts', 'notice_admin_css');
 
function notice_admin_css() {
	
    wp_enqueue_style('admin_css', plugins_url('assets/css/admin.css',__FILE__ ));

}


// WooCommerce addon Admin notice
function wpsms_wc_notice() {
	
	$addonws_url = admin_url( 'admin.php?page=twilio-options-addons' ) ;
	
    $user_id = get_current_user_id();
    if ( !get_user_meta( $user_id, 'wpsmsdismissed' ) ) { ?>

	<div class="notice  wpsms-message">
			<div class="wpsms-message-inner">
				<div class="wpsms-message-icon">
				</div>
				<div class="wpsms-message-content">
				<h2><?php echo sprintf( esc_html__( 'WP SMS for WooCommerce') ); ?></h2>
					<p><?php echo __( 'Increase your store\'s engagement by sending SMS notifications to your customers as per the orders statuses ..', 'twilio-core' ); ?><a href="<?php echo $addonws_url; ?>"><?php echo __( 'Check it out.', 'twilio-core' ); ?></a></p>
					<p class="wpsms-message-actions">
						<a href="<?php echo $addonws_url; ?>" class="button button-primary"><?php echo __( 'Sure! I\'d love to see', 'twilio-core' ); ?></a>
				<a href="?wpsms-dismissed" class="button button-secondary"><?php echo __( 'Dismiss', 'twilio-core' ); ?></a>

					</p>
				</div>
			</div>
	</div>

		<?php
	}
	
}


// Update user data once dismissed
function wpsmsdismissed() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['wpsms-dismissed'] ) )
        add_user_meta( $user_id, 'wpsmsdismissed', 'true', true );
}
add_action( 'admin_init', 'wpsmsdismissed' );



//adforest admin notice

function wpsms_adforest_notice() {
	
	$addonws_url = admin_url( 'admin.php?page=twilio-options-addons' ) ;
	
    $user_id = get_current_user_id();
    if ( !get_user_meta( $user_id, 'wpsmsdismissedad' ) ) { ?>

	<div class="notice  wpsms-message">
			<div class="wpsms-message-inner">
				<div class="wpsms-message-icon">
				</div>
				<div class="wpsms-adforest-icon">
				</div>
				<div class="wpsms-message-content">
				<h2 class="wptwilioskin"><?php echo sprintf( esc_html__( 'WP SMS for AdForest Theme') ); ?></h2>
					<p><?php echo __( 'Using this addon, Your ad sellers will receive SMS as a notification when they are contacted on their listings\' contact forms.', 'twilio-core' ); ?> <a href="<?php echo $addonws_url; ?>"><?php echo __( 'Check it out.', 'twilio-core' ); ?></a></p>
					<p class="wpsms-message-actions">
						<a href="<?php echo $addonws_url; ?>" class="button button-primary"><?php echo __( 'Awesome,Let me to see', 'twilio-core' ); ?></a>
				<a href="?wpsms-dismissedad" class="button button-secondary"><?php echo __( 'Dismiss', 'twilio-core' ); ?></a>

					</p>
				</div>
			</div>
	</div>

		<?php
	}
	
}




// Update user data once dismissed
function wpsmsdismissedadforest() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['wpsms-dismissedad'] ) )
        add_user_meta( $user_id, 'wpsmsdismissedad', 'true', true );
}
add_action( 'admin_init', 'wpsmsdismissedadforest' );




//Check if adforest theme is activated, else show Woocommerce one
$theme = wp_get_theme(); // gets the current theme
if ( 'adforest' == $theme->name || 'adforest' == $theme->parent_theme ) {
add_action( 'admin_notices', 'wpsms_adforest_notice' );

} else {
	add_action( 'admin_notices', 'wpsms_wc_notice' );
	
}