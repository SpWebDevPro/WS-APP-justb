<?php
/**
 * Plugin Name: LatePoint Addon - SMS Twilio
 * Plugin URI:  https://latepoint.com/
 * Description: LatePoint addon for sms notifications via Twilio
 * Version:     1.0.0
 * Author:      LatePoint
 * Author URI:  https://latepoint.com/
 * Text Domain: latepoint-sms-twilio
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

// If no LatePoint class exists - exit, because LatePoint plugin is required for this addon

if ( ! class_exists( 'LatePointSmsTwilio' ) ) :

/**
 * Main Addon Class.
 *
 */

class LatePointSmsTwilio {

  /**
   * Addon version.
   *
   */
  public $version = '1.0.0';
  public $db_version = '1.0.0';
  public $addon_name = 'latepoint-sms-twilio';




  /**
   * LatePoint Constructor.
   */
  public function __construct() {
    $this->define_constants();
    $this->init_hooks();
  }

  /**
   * Define LatePoint Constants.
   */
  public function define_constants() {
  }


  public static function public_stylesheets() {
    return plugin_dir_url( __FILE__ ) . 'public/stylesheets/';
  }

  public static function public_javascripts() {
    return plugin_dir_url( __FILE__ ) . 'public/javascripts/';
  }

  /**
   * Define constant if not already set.
   *
   */
  public function define( $name, $value ) {
    if ( ! defined( $name ) ) {
      define( $name, $value );
    }
  }

  /**
   * Include required core files used in admin and on the frontend.
   */
  public function includes() {

    // COMPOSER AUTOLOAD

    // CONTROLLERS

    // HELPERS

    // MODELS

  }


  public function init_hooks(){
    add_action('latepoint_includes', [$this, 'includes']);
    add_action('latepoint_wp_enqueue_scripts', [$this, 'load_front_scripts_and_styles']);
    add_action('latepoint_admin_enqueue_scripts', [$this, 'load_admin_scripts_and_styles']);

    add_action('latepoint_notifications_settings_sms',[$this, 'add_settings_fields']);
    add_filter('latepoint_has_sms_processors', [$this, 'register_sms_processor']);

    add_filter('latepoint_installed_addons', [$this, 'register_addon']);

    // addon specific filters

    add_action( 'init', array( $this, 'init' ), 0 );

    register_activation_hook(__FILE__, [$this, 'on_activate']);
    register_deactivation_hook(__FILE__, [$this, 'on_deactivate']);


  }

  public function register_sms_processor($has_sms_processors){
    $has_sms_processors = true;
    return $has_sms_processors;
  }

  public function add_settings_fields(){
    ?>
    <div class="lp-twilio-credentials">
      <h3><?php _e('Twilio API Credentials', 'latepoint-sms-twilio'); ?></h3>
      <?php echo OsFormHelper::text_field('settings[notifications_sms_twilio_phone]', __('Phone Number', 'latepoint-sms-twilio'), OsSettingsHelper::get_settings_value('notifications_sms_twilio_phone')); ?>
      <?php echo OsFormHelper::text_field('settings[notifications_sms_twilio_account_sid]', __('Account SID', 'latepoint-sms-twilio'), OsSettingsHelper::get_settings_value('notifications_sms_twilio_account_sid')); ?>
      <?php echo OsFormHelper::password_field('settings[notifications_sms_twilio_auth_token]', __('Auth Token', 'latepoint-sms-twilio'), OsSettingsHelper::get_settings_value('notifications_sms_twilio_auth_token')); ?>
    </div>
    <?php
  }

  /**
   * Init LatePoint when WordPress Initialises.
   */
  public function init() {
    // Set up localisation.
    $this->load_plugin_textdomain();
  }

  public function load_plugin_textdomain() {
    load_plugin_textdomain('latepoint-sms-twilio', false, dirname(plugin_basename(__FILE__)) . '/languages');
  }



  public function output_capacity_on_verification_step($booking){
    if($booking->service->capacity_max > 1){
      echo '<li>'. __('Number of Persons:', 'latepoint-sms-twilio').'<strong>'.$booking->total_attendies.'</strong></li>';
    }
  }

  public function output_total_attendies_on_quick_form($booking){
    $service = $booking->service;
    $capacity_min = empty($service->capacity_min) ? 1 : $service->capacity_min;
    $capacity_max = empty($service->capacity_max) ? 1 : $service->capacity_max;
    $capacity_options = [];
    for($i = $capacity_min; $i <= $capacity_max; $i++){
      $capacity_options[] = $i;
    }
    $hide = ($booking->service_id && $booking->service->capacity_max > 1) ? '' : 'display: none;';
    echo '<div class="booking-total-attendies-selector-w" style="'.$hide.'">';
      echo '<div class="os-row">';
        echo '<div class="os-col-6">';
          echo OsFormHelper::select_field('booking[total_attendies]', __('Total Attendies', 'latepoint-sms-twilio'), $capacity_options, $booking->total_attendies);
        echo '</div>';
        echo '<div class="os-col-6">';
          echo '<div class="capacity-info"><span>'.__('Max Capacity:', 'latepoint-sms-twilio').'</span><strong>'.$capacity_max.'</strong></div>';
        echo '</div>';
      echo '</div>';
    echo '</div>';
  }


  public function output_capacity_on_service_form($service){
    ?>
        <div class="white-box">
          <div class="white-box-header">
            <div class="os-form-sub-header"><h3><?php _e('Capacity Settings', 'latepoint-sms-twilio'); ?></h3></div>
          </div>
          <div class="white-box-content">
            <div class="os-row">
              <div class="os-col-lg-6">
                <?php echo OsFormHelper::text_field('service[capacity_min]', __('Minimum Capacity', 'latepoint-sms-twilio'), $service->capacity_min); ?>
              </div>
              <div class="os-col-lg-6">
                <?php echo OsFormHelper::text_field('service[capacity_max]', __('Maximum Capacity', 'latepoint-sms-twilio'), $service->capacity_max); ?>
              </div>
            </div>
          </div>
        </div>
    <?php
  }


  public function on_deactivate(){
  }

  public function on_activate(){
    if(class_exists('OsDatabaseHelper')) OsDatabaseHelper::check_db_version_for_addons();
  }

  public function register_addon($installed_addons){
    $installed_addons[] = ['name' => $this->addon_name, 'db_version' => $this->db_version, 'version' => $this->version];
    return $installed_addons;
  }




  public function load_front_scripts_and_styles(){
    // Stylesheets

    // Javascripts

  }

  public function load_admin_scripts_and_styles($localized_vars){

    // Stylesheets
  }


  public function localized_vars_for_admin($localized_vars){
    return $localized_vars;
  }

}

endif;

if ( in_array( 'latepoint/latepoint.php', get_option( 'active_plugins', array() ) )  || array_key_exists('latepoint/latepoint.php', get_site_option('active_sitewide_plugins', array())) ) {
  $LATEPOINT_ADDON_SMS_TWILIO = new LatePointSmsTwilio();
}
$latepoint_session_salt = 'YThlZjZhMGMtYzcyMC00M2EwLTgzZTEtZGNhM2IzN2MzODk1';
