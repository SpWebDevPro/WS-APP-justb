<?php


/*
Theme Name: Divi Enfant par Sandy Pradier
Description: Theme enfant permettant le set up de Resea. Le setup nécessite le chargement de plusieurs plugins.
            Pour les différentes étapes du setup, suivre les TODO below, et le trello
            Un upgrade du theme principal DIVI est possible sans perdre les données ci-dessous
            Un upgrade des plugin associés est aussi possible, sauf les plugins dont le code a été modifié directement:
              - latepoint
              - latepoint-google-calendar
            Ces plugins seront mis à jour directement du repo https://github.com/SpWebDevPro/
Author: Sandrine Pradier
Author URI:
Template: 
Version: 1.0
*/

//Resea-App : version 2
//compatible with BookApp : version 9


//TODO 1 >> defining globals for BOOKAPP : update wp config and ask flywheel to do so
//************************************************************************************

$url_front = defined('URL_FRONT') ? URL_FRONT : '';
$url_gas_calendar = defined('URL_GAS_CALENDAR') ? URL_GAS_CALENDAR : '';
$url_gas_contact = defined('URL_GAS_CONTACT') ? URL_GAS_CONTACT : '';
$id_google_calendar_receiving_appData = defined('ID_GOOGLE_CALENDAR_RECEIVING_APPDATA') ? ID_GOOGLE_CALENDAR_RECEIVING_APPDATA : '';
$id_google_calendar_saisie_manuelle = defined('ID_GOOGLE_CALENDAR_SAISIE_MANUELLE') ? ID_GOOGLE_CALENDAR_SAISIE_MANUELLE : '';
$day_jwt_expiration = defined('DAYS_JWT_EXPIRATION') ? (int) DAYS_JWT_EXPIRATION : (int) '1';

$GLOBALS['url_front'] = $url_front;
$GLOBALS['url_gas_calendar'] = $url_gas_calendar;
$GLOBALS['url_gas_contact'] = $url_gas_contact;
$GLOBALS['id_google_calendar_receiving_appData'] = $id_google_calendar_receiving_appData;
$GLOBALS['id_google_calendar_saisie_manuelle'] = $id_google_calendar_saisie_manuelle;
$GLOBALS['day_jwt_expiration'] = 2;


//Chargement de la feuille du style du theme parent
//***************************************************

add_action( 'wp_enqueue_scripts', 'wpchild_enqueue_styles' );

function wpchild_enqueue_styles(){
  wp_enqueue_style( 'wpm-Divi-style', get_template_directory_uri() . '/style.css' );
}



//prevent someone typing /wp-json/wp/V2/users to get the admin name
//***************************************************************** 

function redirect_to_home_if_author_parameter() {

	$is_author_set = get_query_var( 'author', '' );
	if ( $is_author_set != '' && !is_admin()) {
		wp_redirect( home_url(), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'redirect_to_home_if_author_parameter' );



//prevent users to see the rest api list of users and list of endpoints
    /* /wp-json/
    /wp-json/wp/V2 */
//*********************************************************************

function disable_rest_endpoints ( $endpoints ) {
  if ( isset( $endpoints['/wp/v2/users'] ) ) {
      unset( $endpoints['/wp/v2/users'] );
  }
  if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
      unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
  }
  if ( isset( $endpoints['/'] ) ) {
    unset( $endpoints['/'] );
  }
  if ( isset( $endpoints['/wp/v2'] ) ) {
    unset( $endpoints['/wp/v2'] );
  }
  return $endpoints;
}
add_filter( 'rest_endpoints', 'disable_rest_endpoints');



//ading new field phone in wordpress user profile
//*************************************************

add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) { ?>
    <h3>Extra profile information</h3>
        <table class="form-table">
    <tr>
                <th><label for="phone">Phone Number</label></th>
                <td>
                <input type="text" name="phone" id="phone" value="<?php echo esc_attr( get_the_author_meta( 'phone', $user->ID ) ); ?>" class="regular-text" /><br />
                    <span class="description">Please enter your phone number.</span>
                </td>
    </tr>
    </table>
<?php }


add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

function my_save_extra_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    update_user_meta( $user_id, 'phone', $_POST['phone'] );
}



//enable cors
//************

add_action('rest_api_init', function() {
  remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');   
  add_filter('rest_pre_serve_request', function( $value ) {
  $origin = get_http_origin();
  $my_sites = array( $origin ); /*add array of accepted sites if you prefer*/
  if ( in_array( $origin, $my_sites ) ) {
  header( 'Access-Control-Allow-Origin: ' . esc_url_raw( $origin ) );
  } else {
  header( 'Access-Control-Allow-Origin: ' . esc_url_raw( site_url() ) );
  }
  header( 'Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, PATCH, DELETE' );
  header( 'Access-Control-Allow-Credentials: true' );
  header('Access-Control-Allow-Headers: Authorization,DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Origin,Content-Type,X-Auth-Token,Content-Range,Range');
  header('Access-Control-Expose-Headers: Authorization,DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Origin,Content-Type,X-Auth-Token,Content-Range,Range');
  header( 'Vary: Origin' );
  return $value;
  });
  }, 15);


add_filter( 'allowed_http_origins', 'mytheme_add_origins' );

 function mytheme_add_origins($origin) {
    $origin[] = $GLOBALS['url_front'];
    $origin[] = "http://localhost:4200";
    $origin[] = "https://justb-coachingbar.web.app";
    $origin[] = 'https://script.google.com';
    $origin[] = 'https://script.googleusercontent.com';
    return $origin;
 }


//TODO 2 >> related to plugin 'jwt-authentication-for-wp-rest-api' - don't forget to update wp config and ask flywheel to do so
//*****************************************************************************************************************************/

// add expiriesIn property to response when Token is sent to frontEnd
add_filter('jwt_auth_token_before_dispatch', 'jwt_auth_token_before_dispatch_with_expiresIn', 10, 2);

function jwt_auth_token_before_dispatch_with_expiresIn ($data, $user){

  return $data = array(
    'token' => $data['token'],
    /* 'expiresIn' => time() + (DAY_IN_SECONDS * 1),
    'expiresIn' => time() + (DAY_IN_SECONDS * 180), */
    'expiresIn' => time() + (DAY_IN_SECONDS * $GLOBALS['day_jwt_expiration']),
    'user_email' => $user->data->user_email,
    'user_nicename' => $user->data->user_nicename,
    'user_display_name' => $user->data->display_name,
  );
}





//****************************************************************************************
//how to install BOOKAPP:
// place functions.php in child theme, and follow the TODO instruction throughout the file
// in case of any update of the plugin, you will need to recheck below TODO
// files to consider
//  - steps_controller.php
//  - steps_helpers.php
//  - booking_helper.php
//  - calendars_controllers.php
//  - auth_helpers.php
//  - customers_controller.php
//  - smser.php
//  - latepoint-google-calendar.php
//  - wp_date_time.php ( amend 2 functions) straight on the file
//******************************************************************************************









//related to plugin 'Latepoint'
//*****************************

//>>>TODO:


// adding cron every hours to check reminders
//-------------------------------------------
/* refer to this, // crons
add_action('latepoint_send_reminders', [$this, 'send_reminders']);
in latepoint.php */

/* register_activation_hook(__FILE__, 'my_activation');
 
function my_activation() {
    if (! wp_next_scheduled ( 'latepoint_send_reminders' )) {
    wp_schedule_event(time(), 'hourly', 'latepoint_send_reminders');
    }
} */



//create new endpoints
//--------------------

/* /test */
add_action( 'rest_api_init', 'BookApp_test' );
function BookApp_test() {
    register_rest_route( 'apiBookApp', 'test', array(
                    'methods' => 'POST',
                    'callback' =>'test',
                )
            );
}

function test(WP_REST_Request $request ) {
   
    $arr_request = json_decode( $request->get_body() );
    if(!empty( $arr_request->google_calendar_event_id)){
      /* var_dump($arr_request); */
      $agent_id = '1';
      $google_event_id = $arr_request->google_calendar_event_id;
      $data = OsGoogleCalendarHelper::get_record_by_google_event_id($google_event_id); 
     var_dump($data);
     
    } else {
      $data = $GLOBALS['day_jwt_expiration'];
    $response_code = wp_remote_retrieve_response_code($request);
    $response_message = wp_remote_retrieve_response_message($request);
    $response_body = wp_remote_retrieve_body($request);
    }

 if (!is_wp_error($request) ) {
  return $response = new WP_REST_Response(
     array(
       'status' => $response_code,
       'message' => $response_message,
       'data' => $data,
     )
   );
 } else {
   return new WP_Error($response_code, $response_message, $response_body);
 }
}


/* /customerAskResetPassword */
add_action( 'rest_api_init', 'BookApp_customerAskResetPassword' );
function BookApp_customerAskResetPassword() {
    register_rest_route( 'apiBookApp', 'customerAskResetPassword', array(
                    'methods' => 'POST',
                    'callback' =>'customerAskResetPassword',
                )
            );
}

function customerAskResetPassword(WP_REST_Request $request ) {
    $arr_request = json_decode( $request->get_body() );
    if(!empty( $arr_request->email)){
      $email = $arr_request->email;
      $bookAppCustomerController = new BookAppOsCustomersController();
      $response = $bookAppCustomerController->request_password_reset_token_bis($email);
    } else {
      return $response = new WP_REST_Response(
        array(
          'status' => '400',
          'message' => 'bad request',
        )
      );
    }
    $response_code = wp_remote_retrieve_response_code($request);
    $response_message = wp_remote_retrieve_response_message($request);
    $response_body = wp_remote_retrieve_body($request);
    if (!is_wp_error($request) ) {
      return $response = new WP_REST_Response(
        array(
          'status' => $response->status,
          'message' => $response->message,
        )
      );
    } else {
      return new WP_Error($response_code, $response_message, $response_body);
    }
}


/* /customerChangePassword */
add_action( 'rest_api_init', 'BookApp_customerChangePassword' );
function BookApp_customerChangePassword() {
    register_rest_route( 'apiBookApp', 'customerChangePassword', array(
                    'methods' => 'POST',
                    'callback' =>'customerChangePassword',
                )
            );
}

function customerChangePassword(WP_REST_Request $request ) {
    $arr_request = json_decode( $request->get_body() );
    if(!empty( $arr_request->tokenPassword) && (!empty( $arr_request->password)) && (!empty( $arr_request->confirmedPassword)) ){
      $tokenPassword = $arr_request->tokenPassword;
      $password = $arr_request->password;
      $confirmedPassword = $arr_request->confirmedPassword;
      $bookAppCustomerController = new BookAppOsCustomersController();

      $response = $bookAppCustomerController->change_password_bis($tokenPassword,$password,$confirmedPassword);
    } else {
      return $response = new WP_REST_Response(
        array(
          'status' => '400',
          'message' => 'bad request',
        )
      );
    }
    $response_code = wp_remote_retrieve_response_code($request);
    $response_message = wp_remote_retrieve_response_message($request);
    $response_body = wp_remote_retrieve_body($request);
    if (!is_wp_error($request) ) {
      return $response = new WP_REST_Response(
        array(
          'status' => $response->status,
          'message' => $response->message,
        )
      );
    } else {
      return new WP_Error($response_code, $response_message, $response_body);
    }
}


/* /start */
add_action( 'rest_api_init', 'BookApp_start' );
function BookApp_start() {
    register_rest_route( 'apiBookApp', 'start', array(
                    'methods' => 'GET',
                    'callback' =>'start',
                )
            );
}

function start(WP_REST_Request $request) {
  $BookAppOsStepsController = new BookAppOsStepsController();
  $data = $BookAppOsStepsController->start();
 $response_code = wp_remote_retrieve_response_code($request);
 $response_message = wp_remote_retrieve_response_message($request);
 $response_body = wp_remote_retrieve_body($request);

 if (!is_wp_error($request) ) {
  return $response = new WP_REST_Response(
     array(
       'status' => $response_code,
       'message' => $response_message,
       'data' => $data,
     )
   );
 } else {
   return new WP_Error($response_code, $response_message, $response_body);
 }
}
  

/* /firstStep*/
add_action( 'rest_api_init', 'BookApp_firstStep' );
function BookApp_firstStep() {
    register_rest_route( 'apiBookApp', 'firstStep', array(
                    'methods' => 'GET',
                    'callback' =>'firstStep',
                )
            );
}

function firstStep(WP_REST_Request $request) {
  $BookAppOsStepsController = new BookAppOsStepsController();
  $data = $BookAppOsStepsController->getFirstStep();
  
  $response_code = wp_remote_retrieve_response_code($request);
  $response_message = wp_remote_retrieve_response_message($request);
  $response_body = wp_remote_retrieve_body($request);

 if (!is_wp_error($request) ) {
  return $response = new WP_REST_Response(
     array(
       'status' => $response_code,
       'message' => $response_message,
       'data' => $data,
     )
   );
 } else {
   return new WP_Error($response_code, $response_message, $response_body);
 }
}


/* /loadStep*/
add_action( 'rest_api_init', 'BookApp_loadStep' );
function BookApp_loadStep() {
  register_rest_route( 'apiBookApp', 'loadStep', array(
                  'methods' => 'POST',
                  'callback' =>'loadStep',
              )
          );
}

function loadStep( WP_REST_Request $request ){
  $arr_request = json_decode( $request->get_body() );
  if ( ! empty( $arr_request->current_step) && ! empty( $arr_request->booking) && ! empty( $arr_request->restrictions)  && ! empty( $arr_request->direction)){
      $step_name = $arr_request->current_step;
      $booking_object = $arr_request->booking;
      $restrictions = $arr_request->restrictions;
      $direction = $arr_request->direction;
      $customer = $arr_request->customer;
      $BookAppOsStepsController = new BookAppOsStepsController();
      $data = $BookAppOsStepsController->get_step($step_name, $booking_object, $restrictions, $direction, $customer); 

      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      $response_body = wp_remote_retrieve_body($request);
     
      if (!is_wp_error($request) ) {
       return $response = new WP_REST_Response(
          array(
            'status' => $response_code,
            'message' => $response_message,
            'data' => $data,
          )
        );
      } else {
        return new WP_Error($response_code, $response_message, $response_body);
      }
  }
}


/* /BookingList*/
add_action( 'rest_api_init', 'BookApp_listBookings' );
function BookApp_listBookings() {
    register_rest_route( 'apiBookApp', 'listBookings', array(
                    'methods' => 'GET',
                    'callback' =>'listBookings',
                )
            );
}

function listBookings (WP_REST_Request $request){
  $customer = OsAuthHelper::get_logged_in_customer();
  if($customer){
    if($customer->bookings){
      $customer_bookings = $customer->bookings;
      foreach($customer_bookings as $customer_booking){
        $booking_id = $customer_booking->id;
        $BookAppOsBookingsController = new BookAppOsBookingsController();
        $booking_detail = $BookAppOsBookingsController->get_booking_details($booking_id);
        $customer_booking->booking_nice = $booking_detail;
      }
      $data = $customer_bookings;
    }
  }
  $response_code = wp_remote_retrieve_response_code($request);
  $response_message = wp_remote_retrieve_response_message($request);
  $response_body = wp_remote_retrieve_body($request);
  
  if (!is_wp_error($request) ) {
    return $response = new WP_REST_Response(
      array(
        'status' => LATEPOINT_STATUS_SUCCESS,
        'message' => 'liste des bookings OK',
        'data' => $data,
      )
    );
  } else {
    return new WP_Error($response_code, $response_message, $response_body);
  }
}


/* /Cancel Booking by booking id*/
add_action( 'rest_api_init', 'BookApp_cancelBooking' );
function BookApp_cancelBooking() {
    register_rest_route( 'apiBookApp', 'cancelBooking', array(
                    'methods' => 'POST',
                    'callback' =>'cancelBooking',
                )
            );
}

function cancelBooking (WP_REST_Request $request){
  $arr_request = json_decode( $request->get_body() );
  if ( ! empty( $arr_request->id)){
    $booking_id = $arr_request->id;
    $customer = OsAuthHelper::get_logged_in_customer();
    if($customer){
      $BookAppOsBookingsController = new BookAppOsBookingsController();
      $response = $BookAppOsBookingsController->request_cancellation_byId($booking_id);
    }
  }

  $response_code = wp_remote_retrieve_response_code($request);
  $response_message = wp_remote_retrieve_response_message($request);
  $response_body = wp_remote_retrieve_body($request);
  
  if (!is_wp_error($request) ) {
    return $response = new WP_REST_Response(
      array(
        'status' => $response->status,
        'message' => $response->message,
        /* 'data'=> $data */
      )
    );
  } else {
    return new WP_Error($response_code, $response_message, $response_body);
  }

}


/* /get update from Google calendar */
add_action( 'rest_api_init', 'BookApp_getgoogleevents' );
function BookApp_getgoogleevents() {
    register_rest_route( 'apiBookApp', 'getgoogleevents', array(
                    'methods' => 'POST',
                    'callback' =>'getgoogleevents',
                )
            );
}

function getgoogleevents(WP_REST_Request $request ) {
    $arr_request = json_decode( $request->get_body() );

    if(!empty( $arr_request)){
      $data = $arr_request;
      $eventsCancelled = [];
      $eventsAllDay = [];
      $eventsInTheDay = [];//array of objects
      $eventsCancelled = $data->eventsCancelled;
      $eventsAllDay = $data->eventsAllDay;
      $eventsInTheDay = $data->eventsInTheDay;
      $google_cal_id = $data->calendarId;
      $agentId_arr = findAgentToUpdateHisCalendar($google_cal_id);
      
      if($eventsCancelled && $eventsCancelled[0]){
        foreach($eventsCancelled as $eventCancelled){
          GoogleGASNewEvent($eventCancelled,$agentId_arr);
        }
      }
      if($eventsAllDay && $eventsAllDay[0]){
        foreach($eventsAllDay as $eventAllDay){
          GoogleGASNewEvent($eventAllDay, $agentId_arr);
        }
      }
      if($eventsInTheDay && $eventsInTheDay[0]){
        foreach($eventsInTheDay as $eventInTheDay){
          GoogleGASNewEvent($eventInTheDay, $agentId_arr);
        }
      }
    } 

    $response_code = wp_remote_retrieve_response_code($request);
    $response_message = wp_remote_retrieve_response_message($request);
    $response_body = wp_remote_retrieve_body($request);
    if (!is_wp_error($request) ) {
      return $response = new WP_REST_Response(
        array(
          'status' => '200',
          'message' => 'data have been well received by the app',
          'data' => $data,
        )
      );
    } else {
      return new WP_Error($response_code, $response_message, $response_body);
    }
}


function findAgentToUpdateHisCalendar($google_calendar_id){
  $arr_agent = [];
  $agentList = OsAgentHelper::get_agents_list();
  foreach ($agentList as $agent) {
    $id = $agent['value'];
    $agent = new OsAgentModel();
    $agent_details = $agent->load_by_id($id);
    if ($agent_details->email == $google_calendar_id){
      array_push($arr_agent, $agent_details->id);
    }
  }
  return $arr_agent;
  
}



function GoogleGASNewEvent($event, $agentId_arr) {
  if ($event && $event->bookingId){
    echo('we will need to update the event itself');
  }
  else {
    //on est ds le cas d'un RDV manuel de google. on va dc juste bloquer la plage horaire
    if($event->status == "confirmed"){
      //for now by default agent_id = 1
      // il faudrait faire un petite fonction qui retrouve à quelle adresse mail est associé l'agent, 
      //et si c'est la même que celle du calendrier google, alors elle met à jour le calendrier de l'agent correspondant.
      //attention, un seul agent sera pris en compte pour l'inscription de l'évt, car l ide de l evt est unique
      foreach ($agentId_arr as $agent_id){
        echo($agent_id);
        $savingGoogleevt = BookAppOsGoogleCalendarHelper::create_or_update_google_event_in_db_bis($event, $event->googleEventId, $agent_id);
      }
      
      //ici on doit vérifier si recurring_event_id.
      //Si oui, on doit renvoyer un call a google
      if($event->recurringEventId && $event->googleEventId){
        //ici on va aller récupérer le recurrence rule
        BookAppOsGoogleCalendarHelper::handle_exception_in_gcal($event->googleEventId, $event->recurringEventId);
        //le renvoyer à google
      }
    }
    else if ($event->status == "cancelled"){
      // here we need to set time as free
      $removedGoogleevt = OsGoogleCalendarHelper::unsync_google_event_from_db($event->googleEventId);
      //ici il faut vérifier si recurring_event_id.
      // et déclencher handle_exception
      if($event->recurringEventId && $event->googleEventId){
        //ici on va aller récupérer le recurrence rule
        BookAppOsGoogleCalendarHelper::handle_exception_in_gcal($event->googleEventId, $event->recurringEventId);
        //le renvoyer à google
      }

    }
    else {
      echo("je n'ai pas compris la demande");
    }
  }

}





// overriding methods in smser.php
//-------------------------------------------
//>>>TODO:
// to be copy/pasted in smser.php and replace the actual function send_sms
// for this to work, need to install plugin wp-twilio-core

/* function send_sms($to, $message){
  if(!OsSettingsHelper::is_sms_allowed()) {
    return false;}
  if(!OsSettingsHelper::is_sms_processor_setup()) {
    return false;}
  $to = OsUtilHelper::e164format($to);
  if(empty($to)) return false;
  $args = array( 
    'number_to' => $to,
    'message' => $message,
  ); 

  try{
    twl_send_sms( $args );
  }catch(Exception $e){
    error_log($e->getMessage());
  }
} */


//adding methods in customers_controllers.php
//-----------------------------------------------

if (class_exists('OsCustomersController')) {

class BookAppOsCustomersController extends OsCustomersController {

    //add function request_password_reset_token (Author:Sandy)
    public function request_password_reset_token_bis($user_email){
      $customer_model = new OsCustomerModel();
      $customer = $customer_model->where(['email' => $user_email])->set_limit(1)->get_results_as_models();
      $customer_mailer = new OsCustomerMailer();
        if($customer && $customer_mailer->password_reset_request($customer, $customer->account_nonse)){
          $res = new stdClass();
          $res->status = LATEPOINT_STATUS_SUCCESS;
          $res->message = 'Nous vous avons envoyé un email pour modifier votre mot de passe. La réception peut prendre quelques minutes';
          return $res;
        }else{
          $res = new stdClass();
          $res->status = LATEPOINT_STATUS_SUCCESS;
          $res->message = 'Email inconnu, nous ne pouvons donner suite à votre demande';
          return $res;
        }
    }

    //add function change_password_bis(Author:Sandy)
    public function change_password_bis($tokenPassword, $password, $confirmedPassword){
      $customer = new OsCustomerModel();

      if($tokenPassword && $customer->get_by_account_nonse($tokenPassword)){
          
        $customer = $customer->get_by_account_nonse($tokenPassword);
          $customer_email = $customer->email;
          $the_user = get_user_by('email', $customer_email);
          $the_user_id = $the_user->ID;

          if($the_user_id){ 
            //the customer is well registered in wp users, so we can update its password
            if(!empty($password) && $password === $confirmedPassword){
              wp_set_password($password, $the_user_id);
              $status = LATEPOINT_STATUS_SUCCESS;
              $response_html = __('Your password was successfully updated.', 'latepoint');
            }else{
              $status = LATEPOINT_STATUS_ERROR;
              $response_html = __('Error! Passwords do not match.', 'latepoint');
            }
          } else {
            //the customer is unown to wp users db, so would not update its password
            $status = LATEPOINT_STATUS_ERROR;
            $response_html = __('Error! Unknown user.', 'latepoint');
          }

      }else{
        $response_html = __('Invalid Secret Key', 'latepoint');
        $status = LATEPOINT_STATUS_ERROR;
      };
      
      $res = new stdClass();
      $res->status = $status;
      $res->message = $response_html;
      return $res;
    }
}
}



//adding methods in customer_model.php
//------------------------------------

if (class_exists('OsCustomerModel')) {

class BookAppOsCustomerModel extends OsCustomerModel {

  //add function CustomerForGoogleContact(Author:Sandy)
  function CustomerForGoogleContact($customer) {
    
    $body = array(
      "firstname"=> $customer->first_name,
      "lastname"=> $customer->last_name,
      "email"=> $customer->email,
      "phone"=> $customer->phone,
    );
  
    $args = array(
        "body" => json_encode($body),
        "method"      => "POST",
        "data_format" => "body",
        "redirection" => 5
    );

    // new contact in google contacts
    $response = wp_remote_post( $GLOBALS['url_gas_contact'], $args );
    if ( is_wp_error( $response ) ) {
      $error_message = $response->get_error_message();
      echo($error_message);
    }
  }

}
}


// overriding methods in steps_controller.php
//-------------------------------------------

if (class_exists('OsStepsController')){

class BookAppOsStepsController extends OsStepsController {

    //revised function start (Author:Sandy)
    public function start($restrictions = false, $output = false){
      BookAppOsStepsHelper::set_booking_object();
      if((!$restrictions || empty($restrictions)) && isset($this->params['restrictions'])) $restrictions = $this->params['restrictions'];
      BookAppOsStepsHelper::set_restrictions($restrictions);
      BookAppOsStepsHelper::get_step_names_in_order();
      BookAppOsStepsHelper::remove_already_selected_steps();

      $this->steps_models = BookAppOsStepsHelper::load_steps_as_models(BookAppOsStepsHelper::get_step_names_in_order());

      $active_step_model = $this->steps_models[0];

      // if is payment step - check if total is not $0 and if it is skip payment step
      if(BookAppOsStepsHelper::should_step_be_skipped($active_step_model->name)){
          $active_step_model = $this->steps_models[1];
      }

      $this->vars['show_next_btn'] = BookAppOsStepsHelper::can_step_show_next_btn($active_step_model->name);
      $this->vars['show_prev_btn'] = BookAppOsStepsHelper::can_step_show_prev_btn($active_step_model->name);
      $this->vars['steps_models'] = $this->steps_models;
      $this->vars['active_step_model'] = $active_step_model;

      $this->vars['current_step'] = $active_step_model->name;
      $this->vars['booking'] = BookAppOsStepsHelper::$booking_object;
      $this->vars['restrictions'] = BookAppOsStepsHelper::$restrictions;
      $this->set_layout('none');

      $dataToSend = [];
      $dataToSend['active_step_model'] = $this->vars['active_step_model'];
      $dataToSend['steps_models'] = $this->steps_models;
      $dataToSend['current_step'] = $active_step_model->name;
      $dataToSend['booking'] = BookAppOsStepsHelper::$booking_object;
      $dataToSend['restrictions'] = BookAppOsStepsHelper::$restrictions;
      $dataToSend['show_next_btn'] = BookAppOsStepsHelper::can_step_show_next_btn($active_step_model->name);
      $dataToSend['show_prev_btn'] = BookAppOsStepsHelper::can_step_show_prev_btn($active_step_model->name);

      return $dataToSend;

    } 

    //Added function getFirstStep (Author:Sandy)
    public function getFirstStep($restrictions = false, $output = true){
      BookAppOsStepsHelper::set_booking_object();
      if((!$restrictions || empty($restrictions)) && isset($this->params['restrictions'])) $restrictions = $this->params['restrictions'];
  
      BookAppOsStepsHelper::set_restrictions($restrictions);
      BookAppOsStepsHelper::get_step_names_in_order();
      BookAppOsStepsHelper::remove_already_selected_steps();

      $this->steps_models = BookAppOsStepsHelper::load_steps_as_models(BookAppOsStepsHelper::get_step_names_in_order());

      $active_step_model = $this->steps_models[0];

      //fix temporaire tant que les locations ne sont pas mises en place
      if($active_step_model->name === 'locations'){
        $active_step_model = $this->steps_models[1];
      }

      // if is payment step - check if total is not $0 and if it is skip payment step
      if(BookAppOsStepsHelper::should_step_be_skipped($active_step_model->name)){
          $active_step_model = $this->steps_models[1];
      }

      $this->vars['show_next_btn'] = BookAppOsStepsHelper::can_step_show_next_btn($active_step_model->name);
      $this->vars['show_prev_btn'] = BookAppOsStepsHelper::can_step_show_prev_btn($active_step_model->name);
      $this->vars['steps_models'] = $this->steps_models;
      $this->vars['active_step_model'] = $active_step_model;

      $this->vars['current_step'] = $active_step_model->name;
      $this->vars['booking'] = BookAppOsStepsHelper::$booking_object;
      $this->vars['restrictions'] = BookAppOsStepsHelper::$restrictions;
      $this->set_layout('none');

      /* do_action('latepoint_load_step', $active_step_model->name, $this->vars['booking'], 'json'); */
      $dataToSend = apply_filters( 'latepoint_load_step', $dataToSend, $active_step_model->name, $this->vars['booking'], 'json' );
      return $dataToSend;
    } 

    //revised function get_step (Author:Sandy)
    public function get_step($step_name, $booking, $restrictions, $direction, $customer){

      $dataToSend = [];
     
      if(!BookAppOsStepsHelper::is_valid_step($step_name)) return false;

      $booking_object_params = (array) $booking;
      $booking_object = BookAppOsStepsHelper::set_booking_object($booking_object_params);
      $booking_object->customer = $customer;
      
      $restrictions = BookAppOsStepsHelper::set_restrictions($restrictions);

      BookAppOsStepsHelper::get_step_names_in_order();
      BookAppOsStepsHelper::remove_already_selected_steps();
      // Check if a valid step name
      $current_step = $step_name;
      if(!in_array($current_step, BookAppOsStepsHelper::get_step_names_in_order())) return false;
      $step_direction = isset($direction) ? $direction : 'next';
      switch ($step_direction) {
        case 'next':
          do_action('latepoint_process_step', $current_step, BookAppOsStepsHelper::$booking_object);
          /* $dataToSend = apply_filters( 'latepoint_process_step', $dataToSend, $current_step, BookAppOsStepsHelper::$booking_object, 'json' ); */
          $step_name_to_load = BookAppOsStepsHelper::get_next_step_name($current_step);
         /*  return $dataToSend; */
          break;
        case 'prev':
		      $step_name_to_load = BookAppOsStepsHelper::get_prev_step_name($current_step);
          break;
        case 'specific':
	        $step_name_to_load = BookAppOsStepsHelper::should_step_be_skipped($current_step) ? BookAppOsStepsHelper::get_next_step_name($current_step) : $current_step;
          break;
      }
      if($step_name_to_load){
      /*do_action('latepoint_load_step', $step_name_to_load, OsStepsHelper::$booking_object); */
        $dataToSend = apply_filters( 'latepoint_load_step', $dataToSend, $step_name_to_load, BookAppOsStepsHelper::$booking_object, 'json' );
          return $dataToSend;
      }
    }

}
}




//adding method in model.php
//---------------------------

if (class_exists('OsModel')){

class BookAppOsModel extends OsModel {

  public static function get_thumbnail_media_url_from_id($id){
     $thumbnail_infos = wp_get_attachment_image_src($id,'thumbnail');
     return $thumbnail_infos[0];
  }
}
}


//adding method in bookings_controller.php
//----------------------------------------

if (class_exists('OsBookingsController')){

class BookAppOsBookingsController extends OsBookingsController {

  public static function request_cancellation_byId($booking_id){
    $booking = new OsBookingModel($booking_id);
      if(OsAuthHelper::get_logged_in_customer_id() == $booking->customer_id){
        $loggedincust = OsAuthHelper::get_logged_in_customer_id();
        $new_status = LATEPOINT_BOOKING_STATUS_CANCELLED;
        $result = BookAppOsBookingsController::change_status_bis($booking_id, $new_status);
        return $result;
      }else{
        $status = LATEPOINT_STATUS_ERROR;
        $response_html = __('Error! JSf29834', 'latepoint');
        $res = new stdClass();
        $res->status = $status;
        $res->message = $response_html;
        return $res;
      }
  }

  public static function change_status_bis($booking_id, $new_status){
    $booking = new OsBookingModel($booking_id);
    $old_status = $booking->status;
    $old_status_nice = $booking->nice_status;
    $booking->status = $new_status;
    if($new_status == $old_status){
      $response_html = __('Le statut du rendez-vous était déjà mis à jour', 'latepoint');
      $status = LATEPOINT_STATUS_SUCCESS;
      $res = new stdClass();
      $res->status = $status;
      $res->message = $response_html;
      return $res;
    }else{
      if($booking->save()){
        $response_html = __('Le rendez-vous a bien été annulé', 'latepoint');
        $status = LATEPOINT_STATUS_SUCCESS;
        $res = new stdClass();
        $res->status = $status;
        $res->message = $response_html;
        OsNotificationsHelper::process_booking_status_changed_notifications($booking, $old_status_nice);
        do_action('latepoint_booking_updated_admin', $booking);
        do_action('latepoint_booking_status_changed', $booking, $old_status);
        OsActivitiesHelper::create_activity(array('code' => 'booking_change_status', 'booking' => $booking, 'old_value' => $old_status));
        return $res;
      }else{
        $response_html = $booking->get_error_messages();
        $status = LATEPOINT_STATUS_ERROR;
        $res = new stdClass();
        $res->status = $status;
        $res->message = $response_html;
        return $res;
      }
    }
  }

  public static function get_booking_details($booking_id){
    $booking = new OsBookingModel($booking_id);
    $service_id = $booking->service_id;
    $agent_id = $booking->agent_id;
    $location_id = $booking->location_id;
    $service = new OsServiceModel();
    $service->load_by_id($service_id);
    $agent = new OsAgentModel();
    $agent->load_by_id($agent_id);
    $location = new OsLocationModel();
    $location->load_by_id($location_id);
    $booking_sumup = new stdClass();
    $booking_sumup->service = $service->name;
    $booking_sumup->agent = $agent->first_name;
    $booking_sumup->location_name = $location->name;
    $booking_sumup->location_address = $location->full_address;
    return $booking_sumup;


  }

}
}



// overriding methods in steps_helpers.php
//----------------------------------------

//>>>TODO:
// to be copy/pasted in steps_helpers.php, below other class
// WARNING!!!! i have modified steps helpers straight. 
// there, change the name BookAppOsStepsHelperTemplate to BookAppOsStepsHelper
// in latepoint.php, copy this BookAppOsStepsHelper::init_step_actions(); i/o  OsStepsHelper::init_step_actions();
  

if (class_exists('OsStepsHelper')){

class BookAppOsStepsHelperTemplate extends OsStepsHelper {


  //revised function init_step_actions (Author:Sandy)
  public static function init_step_actions(){
    add_action('latepoint_process_step', 'BookAppOsStepsHelper::process_step', 10, 2);
    add_filter( 'latepoint_load_step', 'BookAppOsStepsHelper::load_step', 10, 3 );
  } 

  //revised function process_step (Author:Sandy)
  public static function process_step($step_name, $booking_object){
		$step_function_name = 'process_step_'.$step_name;
  	if(method_exists('BookAppOsStepsHelper', $step_function_name)){
  		return $result = self::$step_function_name();
      if(is_wp_error($result)){
        wp_send_json(array('status' => LATEPOINT_STATUS_ERROR, 'message' => $result->get_error_message()));
        return;
      }
  	}
  }

  //revised function load_step (Author:Sandy)
  //original should be commented out because not the same arguments number. 
  //Check later if overloading is possible
  public static function load_step( $dataToSend = [], $step_name, $booking_object, $format = 'json'){

    if(OsAuthHelper::is_customer_logged_in() && OsSettingsHelper::get_settings_value('max_future_bookings_per_customer')){
      $customer = OsAuthHelper::get_logged_in_customer();
      if($customer->future_bookings_count >= OsSettingsHelper::get_settings_value('max_future_bookings_per_customer')){
		  	$steps_controller = new BookAppOsStepsController();
		    $steps_controller->set_layout('none');
			  $steps_controller->set_return_format($format);
        $steps_controller->format_render('_limit_reached', [], [
          'show_next_btn' => false, 
          'show_prev_btn' => false, 
          'is_first_step' => true, 
          'is_last_step' => true, 
          'is_pre_last_step' => false]);
        return;
      }
    }

    // run prepare step function
    $step_function_name = 'prepare_step_'.$step_name;
  	if(method_exists('BookAppOsStepsHelper', $step_function_name)){
     
      $result = self::$step_function_name();
      if(is_wp_error($result)){
        wp_send_json(array('status' => LATEPOINT_STATUS_ERROR, 'message' => $result->get_error_message()));
        return;
      }
    	$steps_controller = new BookAppOsStepsController();
      $steps_controller->vars = self::$vars_for_view;
      $steps_controller->vars['booking'] = self::$booking_object;
      $steps_controller->vars['current_step'] = $step_name;
      $steps_controller->vars['restrictions'] = self::$restrictions;
      $steps_controller->set_layout('none');
      $steps_controller->set_return_format($format);

      $logged_in_customer = OsAuthHelper::get_logged_in_customer();

        $dataToSend = array(
          'step_name' 				=> $step_name, 
          'show_next_btn' 		=> self::can_step_show_next_btn($step_name), 
          'show_prev_btn' 		=> self::can_step_show_prev_btn($step_name), 
          'is_first_step' 		=> self::is_first_step($step_name), 
          'is_last_step' 			=> self::is_last_step($step_name), 
          'is_pre_last_step' 	=> self::is_pre_last_step($step_name),
          'booking_object'    => self::$booking_object,
          'customer'          => $logged_in_customer,
          'vars_for_view'     => self::$vars_for_view,
          'restrictions'      => self::$restrictions
        );
        
      return $dataToSend;
  	}
  }


  //revised function set_restrictions (Author:Sandy)
  public static function set_restrictions($myrestrictions = array()){
    
    $restrictions= (array)$myrestrictions;

    if(isset($restrictions) && !empty($restrictions)){
      // filter locations
      if(isset($restrictions['show_locations'])) 
        self::$restrictions['show_locations'] = $restrictions['show_locations'];
        
      // filter agents
      if(isset($restrictions['show_agents'])) 
        self::$restrictions['show_agents'] = $restrictions['show_agents'];
        
      // filter service category
      if(isset($restrictions['show_service_categories'])) 
        self::$restrictions['show_service_categories'] = $restrictions['show_service_categories'];

      // filter services
      if(isset($restrictions['show_services'])) 
        self::$restrictions['show_services'] = $restrictions['show_services'];

      // preselected service category
      if(isset($restrictions['selected_service_category']) && is_numeric($restrictions['selected_service_category']))
        self::$restrictions['selected_service_category'] = $restrictions['selected_service_category'];

      // preselected calendar start date
      if(isset($restrictions['calendar_start_date']) && OsTimeHelper::is_valid_date($restrictions['calendar_start_date']))
        self::$restrictions['calendar_start_date'] = $restrictions['calendar_start_date'];

      // restriction in settings can ovveride it
      if(OsTimeHelper::is_valid_date(OsSettingsHelper::get_settings_value('earliest_possible_booking')))
        self::$restrictions['calendar_start_date'] = OsSettingsHelper::get_settings_value('earliest_possible_booking');

      // preselected location
      if(isset($restrictions['selected_location']) && is_numeric($restrictions['selected_location'])){
        self::$restrictions['selected_location'] = $restrictions['selected_location'];
        self::$booking_object->location_id = $restrictions['selected_location'];
      }
      // preselected agent
      if(isset($restrictions['selected_agent']) && (is_numeric($restrictions['selected_agent']) || ($restrictions['selected_agent'] == LATEPOINT_ANY_AGENT))){
        self::$restrictions['selected_agent'] = $restrictions['selected_agent'];
        self::$booking_object->agent_id = $restrictions['selected_agent'];
      }

      // preselected service
      if(isset($restrictions['selected_service']) && is_numeric($restrictions['selected_service'])){
        self::$restrictions['selected_service'] = $restrictions['selected_service'];
        self::$booking_object->service_id = $restrictions['selected_service'];
      }
    }
    return self::$restrictions;
  }


  //revised function prepare_step_services (Author:Sandy)
  //( include thumbnail url)
  public static function prepare_step_services(){
  
    $services_model = new OsServiceModel();
    $show_selected_services_arr = self::$restrictions['show_services'] ? explode(',', self::$restrictions['show_services']) : false;
    $show_service_categories_arr = self::$restrictions['show_service_categories'] ? explode(',', self::$restrictions['show_service_categories']) : false;
    $preselected_category = self::$restrictions['selected_service_category'];

    $connected_ids = OsConnectorHelper::get_connected_object_ids('service_id', ['agent_id' => self::$booking_object->agent_id, 'location_id' => self::$booking_object->location_id]);
    // if show only specific services are selected (restrictions) - remove ids that are not found in connection
    $show_services_arr = (!empty($show_selected_services_arr) && !empty($connected_ids)) ? array_intersect($connected_ids, $show_selected_services_arr) : $connected_ids;

    if(!empty($show_services_arr)) $services_model->where_in('id', $show_services_arr);

    $services = $services_model->should_be_active()->get_results_as_models();

    foreach($services as $service){

      $thumbnail_url = BookAppOSModel::get_thumbnail_media_url_from_id($service->selection_image_id);
      $_service = new stdClass;
      $_service = $service;
      $_service->thumbnail_url = $thumbnail_url;
    }

    self::$vars_for_view['show_services_arr'] = $show_services_arr;
    self::$vars_for_view['show_service_categories_arr'] = $show_service_categories_arr;
    self::$vars_for_view['preselected_category'] = $preselected_category;
    self::$vars_for_view['services'] = $services;

    return self::$vars_for_view;

  }


  //revised function prepare_step_agents (Author:Sandy)
  //( include thumbnail url)
  public static function prepare_step_agents(){
    
      $agents_model = new OsAgentModel();

      $show_selected_agents_arr = (self::$restrictions['show_agents']) ? explode(',', self::$restrictions['show_agents']) : false;
      $connected_ids = OsConnectorHelper::get_connected_object_ids('agent_id', ['service_id' => self::$booking_object->service_id, 'location_id' => self::$booking_object->location_id]);

      // If date/time is selected - filter agents who are available at that time
      if(self::$booking_object->start_date && self::$booking_object->start_time){
        $available_agent_ids = [];
        foreach($connected_ids as $agent_id){
          if(OsAgentHelper::is_agent_available_on($agent_id, self::$booking_object->start_date, self::$booking_object->start_time, self::$booking_object->get_total_duration(), self::$booking_object->service_id, self::$booking_object->location_id)) $available_agent_ids[] = $agent_id;
        }
        $connected_ids = (!empty($available_agent_ids) && !empty($connected_ids)) ? array_intersect($available_agent_ids, $connected_ids) : $connected_ids;
      }
      
      // if show only specific agents are selected (restrictions) - remove ids that are not found in connection
      $show_agents_arr = (!empty($show_selected_agents_arr) && !empty($connected_ids)) ? array_intersect($connected_ids, $show_selected_agents_arr) : $connected_ids;
      if(!empty($show_agents_arr)) $agents_model->where_in('id', $show_agents_arr);

      $agents = $agents_model->should_be_active()->get_results_as_models();

      foreach($agents as $agent){
        $thumbnail_url = BookAppOSModel::get_thumbnail_media_url_from_id($agent->avatar_image_id);
        $_agent = new stdClass;
        $_agent = $agent;
        $_agent->thumbnail_url = $thumbnail_url;
      }

      self::$vars_for_view['agents'] = $agents;
  }

  //revised function prepare_step_datepicker (Author:Sandy)
  public static function prepare_step_datepicker(){

    if(empty(self::$booking_object->agent_id)) self::$booking_object->agent_id = LATEPOINT_ANY_AGENT;
    $restrictions = self::set_restrictions(self::$restrictions);
  
    $calendar_start_date = $restrictions['calendar_start_date'] ? $restrictions['calendar_start_date'] : 'today';
    self::$vars_for_view['calendar_start_date'] = self::$restrictions['calendar_start_date'] ? self::$restrictions['calendar_start_date'] : 'today';
  
    $datePicker_data = BookAppOsBookingHelper::generate_monthly_calendar_front($calendar_start_date, ['timeshift_minutes' => OsTimeHelper::get_timezone_shift_in_minutes(OsTimeHelper::get_timezone_name_from_session()),'service_id' => self::$booking_object->service_id, 'agent_id' => self::$booking_object->agent_id, 'location_id' => self::$booking_object->location_id, 'duration' => self::$booking_object->get_total_duration()]);
    self::$vars_for_view['datePicker_data']= $datePicker_data;

  }


  //revised function process_step_contact (Author:Sandy)
  public static function process_step_contact(){

    $status = LATEPOINT_STATUS_SUCCESS;
    $customer_params =  (array) self::$booking_object->customer;
    $booking_params = (array) self::$booking_object;
    $logged_in_customer = OsAuthHelper::get_logged_in_customer();


    if($logged_in_customer){
      // LOGGED IN
      // Check if they are changing the email on file
      if($logged_in_customer->email != $customer_params['email']){

        // Check if other customer already has this email
        $customer = new OsCustomerModel();
        $customer_with_email_exist = $customer->where(array('email'=> $customer_params['email'], 'id !=' => $logged_in_customer->id))->set_limit(1)->get_results_as_models();
        if($customer_with_email_exist){
          $status = LATEPOINT_STATUS_ERROR;
          $response_html = __('Another customer is registered with this email.', 'latepoint');
        }
      } else {
      }
    }else{
      // NEW REGISTRATION
      $customer = new OsCustomerModel();
      $customer_exist = $customer->where(array('email'=> $customer_params['email']))->set_limit(1)->get_results_as_models();
      if($customer_exist){
        // CUSTOMER WITH THIS EMAIL EXISTS - ASK TO LOGIN, CHECK IF CURRENT CUSTOMER WAS REGISTERED AS A GUEST
        if($customer_exist->can_login_without_password()){
          $status == LATEPOINT_STATUS_SUCCESS;
          OsAuthHelper::authorize_customer($customer_exist->id);
        }else{
          // Not a guest account, do not allow using it
          $status = LATEPOINT_STATUS_ERROR;
          $response_html = __('An account with that email address already exists. Please try signing in.', 'latepoint');
        }
      }
    }
    if($status == LATEPOINT_STATUS_SUCCESS){
      $customer = new OsCustomerModel();
      $is_new_customer = true;
      if(OsAuthHelper::is_customer_logged_in()){
        $customer = OsAuthHelper::get_logged_in_customer();
        if(!$customer->is_new_record()) $is_new_customer = false;


      }
      $customer->set_data($customer_params);
      $custom_fields_data = isset($customer_params['custom_fields']) ? $customer_params['custom_fields'] : [];
      if($customer->validate_custom_fields($custom_fields_data) && $customer->save()){
        $customer->save_custom_fields($custom_fields_data);
        if($is_new_customer){
          OsNotificationsHelper::process_new_customer_notifications($customer);
          OsActivitiesHelper::create_activity(array('code' => 'customer_create', 'customer_id' => $customer->id));
        }

        self::$booking_object->customer_id = $customer->id;
        if(!OsAuthHelper::is_customer_logged_in()){
          OsAuthHelper::authorize_customer($customer->id);
        }
        $customer->set_timezone_name();
      }else{
        $status = LATEPOINT_STATUS_ERROR;
        $response_html = $customer->get_error_messages();
        if(is_array($response_html)) $response_html = implode(', ', $response_html);
      }
    }
    if($status == LATEPOINT_STATUS_ERROR){
      return new WP_Error(LATEPOINT_STATUS_ERROR, $response_html);
    }

  }


  //revised function prepare_step_confirmation (Author:Sandy)


  //revised function prepare_step_verify (Author:Sandy)
  public static function prepare_step_verify(){
    self::$vars_for_view['customer'] = new OsCustomerModel(self::$booking_object->customer_id);
    self::$vars_for_view['custom_fields_for_customer'] = OsCustomFieldsHelper::get_custom_fields_arr('customer');
    self::$booking_object->customer = new OsCustomerModel(self::$booking_object->customer_id);
    self::$vars_for_view['agent'] = new OsAgentModel(self::$booking_object->agent_id);
    self::$vars_for_view['location'] = new OsLocationModel(self::$booking_object->location_id);
    self::$vars_for_view['service'] = new OsServiceModel(self::$booking_object->service_id);
  }


  //revised function prepare_step_confirmation (Author:Sandy)
  public static function prepare_step_confirmation(){
    self::$vars_for_view['customer'] = new OsCustomerModel(self::$booking_object->customer_id);
    self::$vars_for_view['custom_fields_for_customer'] = OsCustomFieldsHelper::get_custom_fields_arr('customer');
    self::$booking_object->customer = new OsCustomerModel(self::$booking_object->customer_id);
    /* self::$vars_for_view['agent'] = new OsAgentModel(self::$booking_object->agent_id);
    self::$vars_for_view['location'] = new OsLocationModel(self::$booking_object->location_id);
    */
    if(self::$booking_object->is_new_record()){
      if(!self::$booking_object->save_from_booking_form()){
        OsDebugHelper::log(self::$booking_object->get_error_messages());
        $status = LATEPOINT_STATUS_ERROR;
        $response_html = self::$booking_object->get_error_messages();
        return new WP_Error(LATEPOINT_STATUS_ERROR, $response_html);
      }
    }
  }

}
}





// overriding methods in booking_helper.php
//------------------------------------------

if (class_exists('OsBookingHelper')){

class BookAppOsBookingHelper extends OsBookingHelper {


  //revised function generate_monthly_calendar_front (Author:Sandy)
  public static function generate_monthly_calendar_front($target_date_string = 'today', $settings = []){
    $defaults = [
    'service_id' => false,
    'agent_id' => false, 
    'location_id' => false, 
    'number_of_months_to_preload' => 1, 
    'allow_full_access' => false, 
    'duration' => false, 
    'timeshift_minutes' => 0, 
    'highlight_target_date' => false ];

    $settings = OsUtilHelper::merge_default_atts($defaults, $settings);

    if($settings['location_id'] === false) $settings['location_id'] = OsLocationHelper::get_selected_location_id();
    $target_date = new OsWpDateTime($target_date_string);
    $weekdays = OsBookingHelper::get_weekdays_arr();

    $hebdoDays = [];
    foreach($weekdays as $weekday_number => $weekday_name){
    $objDay = (object) array(
      'dayClass'=>'weekday weekday-'.($weekday_number + 1).'',
      'dayLabel'=> $weekday_name
    );
    array_push($hebdoDays, $objDay);
    } 

    $dataToReturnMonth = array(
                          'hebdoDays'=> $hebdoDays,
                          'osCurrentMonthLabel' => OsUtilHelper::get_month_name_by_number($target_date->format('n')), 
                          'osMonthPrevBtn' => $settings['allow_full_access'], 
                          'dataRoute_osMonthPrevBtn' => OsRouterHelper::build_route_name('calendars', 'load_monthly_calendar_days'), 
                          'dataRoute_osMonthNextBtn' =>  OsRouterHelper::build_route_name('calendars', 'load_monthly_calendar_days'), 
                          );
   
    $days_settings = ['service_id' => $settings['service_id'], 
                      'agent_id' => $settings['agent_id'], 
                      'location_id' => $settings['location_id'], 
                      'active' => true, 
                      'duration' => $settings['duration'], 
                      'timeshift_minutes' => $settings['timeshift_minutes'], 
                      'highlight_target_date' => $settings['highlight_target_date']];

    
    // if it's not from admin - blackout dates that are not available to select due to date restrictions in settings
    if(!$settings['allow_full_access']){
      $days_settings['earliest_possible_booking'] = OsSettingsHelper::get_settings_value('earliest_possible_booking', false);
      $days_settings['latest_possible_booking'] = OsSettingsHelper::get_settings_value('latest_possible_booking', false);
    }

    $monthly_calendar_days = [];
    $monthly_calendar_days_current = BookAppOsBookingHelper::generate_monthly_calendar_days_front($target_date_string, $days_settings, $dataToReturnMonth); 
    array_push($monthly_calendar_days, $monthly_calendar_days_current);
    for($i = 1; $i <= $settings['number_of_months_to_preload']; $i++){
      $target_date->modify('first day of next month');
      $days_settings['active'] = false;
      $days_settings['highlight_target_date'] = false;
      $monthly_calendar_days_others = BookAppOsBookingHelper::generate_monthly_calendar_days_front($target_date->format('Y-m-d'), $days_settings, $dataToReturnMonth);
      array_push($monthly_calendar_days, $monthly_calendar_days_others);

    return $monthly_calendar_days;
    }
  }


  //revised function generate_monthly_calendar_days_front (Author:Sandy)
  public static function generate_monthly_calendar_days_front($target_date_string = 'today', $settings = [], $dataMonth){

    $dataToReturnMonth = $dataMonth;

    $defaults = [
    'service_id' => false, 
    'agent_id' => false, 
    'location_id' => false, 
    'active' => false, 
    'duration' => false, 
    'highlight_target_date' => false, 
    'timeshift_minutes' => 0,
    'earliest_possible_booking' => false,
    'latest_possible_booking' => false ];

    $settings = OsUtilHelper::merge_default_atts($defaults, $settings);
    $service = new OsServiceModel($settings['service_id']);
    $duration_minutes = ($settings['duration']) ? $settings['duration'] : $service->duration;

    if($settings['location_id'] === false) $settings['location_id'] = OsLocationHelper::get_selected_location_id();
    if(($settings['agent_id'] == LATEPOINT_ANY_AGENT)){
      $agent_ids = OsAgentHelper::get_agents_for_service_and_location($service->id, $settings['location_id']);
    }else{
      $agent_ids = [$settings['agent_id']];
    }

    $target_date = new OsWpDateTime($target_date_string);
    $calendar_start = clone $target_date;
    $calendar_start->modify('first day of this month');
    $calendar_end = clone $target_date;
    $calendar_end->modify('last day of this month');

    $interval = $service->get_timeblock_interval();


    $weekday_for_first_day_of_month = $calendar_start->format('N') - 1;
    $weekday_for_last_day_of_month = $calendar_end->format('N') - 1;


    if($weekday_for_first_day_of_month > 0){
      $calendar_start->modify('-'.$weekday_for_first_day_of_month.' days');
    }

    if($weekday_for_last_day_of_month < 7){
      $days_to_add = 7 - $weekday_for_last_day_of_month;
      $calendar_end->modify('+'.$days_to_add.' days');
    }

    $date_range_start = clone $calendar_start;
    $date_range_end = clone $calendar_end;

    $now_datetime = OsTimeHelper::now_datetime_object();
    if($settings['timeshift_minutes']) $now_datetime->modify($settings['timeshift_minutes'].' minutes');

    if($date_range_start->format('Y-m-d') < $now_datetime->format('Y-m-d')){
      $date_range_start = clone $now_datetime;
    }

    if($settings['earliest_possible_booking']){
      $earliest_possible_booking = new OsWpDateTime($settings['earliest_possible_booking']);
      if($date_range_start < $earliest_possible_booking) $date_range_start = clone $earliest_possible_booking;
      if($date_range_end < $earliest_possible_booking) $date_range_end = clone $earliest_possible_booking;
    }
    if($settings['latest_possible_booking']){
      $latest_possible_booking = new OsWpDateTime($settings['latest_possible_booking']);
      if($date_range_end > $latest_possible_booking) $date_range_end = clone $latest_possible_booking;
      if($date_range_start > $latest_possible_booking) $date_range_start = clone $latest_possible_booking;
    }

    if(($date_range_start >= $calendar_start) && ($date_range_start <= $calendar_end) && ($date_range_end >= $calendar_start) && ($date_range_end <= $calendar_end)){
      $booked_periods_arr = self::get_bookings_times_for_date_range($date_range_start->format('Y-m-d'), $date_range_end->format('Y-m-d'), ['agent_id' => $agent_ids, 'location_id' => $settings['location_id'], 'timeshift_minutes' => $settings['timeshift_minutes']]);
      foreach($agent_ids as $agent_id){
        $work_periods_arr['agent_'.$agent_id] = self::get_work_periods_for_date_range($date_range_start->format('Y-m-d'), $date_range_end->format('Y-m-d'), ['service_id' => $settings['service_id'], 'agent_id' => $agent_id, 'location_id' => $settings['location_id'], 'timeshift_minutes' => $settings['timeshift_minutes']]);
      }
    }

    $active_class = $settings['active'] ? 'active' : '';

    $dataToReturnDays = [];

      // DAYS LOOP START
      for($day_date=clone $calendar_start; $day_date<$calendar_end; $day_date->modify('+1 day')){
        $is_today = ($day_date->format('Y-m-d') == $now_datetime->format('Y-m-d')) ? true : false;
        $is_day_in_past = ($day_date->format('Y-m-d') < $now_datetime->format('Y-m-d')) ? true : false;
        $is_target_month = ($day_date->format('m') == $target_date->format('m')) ? true : false;
        $is_next_month = ($day_date->format('m') > $target_date->format('m')) ? true : false;
        $is_prev_month = ($day_date->format('m') < $target_date->format('m')) ? true : false;
        $not_in_allowed_period = false;

        if($settings['earliest_possible_booking']){
          if($day_date->format('Y-m-d') < $earliest_possible_booking->format('Y-m-d')) $not_in_allowed_period = true;
        }
        if($settings['latest_possible_booking']){
          if($day_date->format('Y-m-d') > $latest_possible_booking->format('Y-m-d')) $not_in_allowed_period = true;
        }

        $booked_minutes = [];
        $not_working_minutes = [];
        $available_minutes = [];
        $day_minutes = [];

        foreach($agent_ids as $agent_id){
          if($is_today){
            // if today - block already passed time slots
            $booked_periods_arr['agent_'.$agent_id][$day_date->format('Y-m-d')][] = '0:'.OsTimeHelper::get_current_minutes($settings['timeshift_minutes']).':0:0';
          }

          if(!$is_day_in_past && !$not_in_allowed_period){
            foreach($work_periods_arr['agent_'.$agent_id][$day_date->format('Y-m-d')] as $work_period){
              list($period_start, $period_end) = explode(':', $work_period);
              if($period_start == $period_end) continue;
              for($minutes = $period_start; $minutes <= $period_end; $minutes+= $service->get_timeblock_interval()){
                $day_minutes[] = $minutes;
                $is_available = true;
                if(isset($booked_periods_arr['agent_'.$agent_id][$day_date->format('Y-m-d')])){
                  if(OsBookingHelper::is_timeframe_in_periods($minutes, $minutes + $duration_minutes, $booked_periods_arr['agent_'.$agent_id][$day_date->format('Y-m-d')])){
                    $booked_minutes[] = $minutes;
                    $is_available = false;
                  }
                }
                if(!OsBookingHelper::is_timeframe_in_periods($minutes, $minutes + $duration_minutes, $work_period, true)){
                  $not_working_minutes[] = $minutes;
                  $is_available = false;
                }
                if($is_available) $available_minutes[] = $minutes;
              }
            }
          }
        }

        $available_minutes = array_unique($available_minutes, SORT_NUMERIC);
        $booked_minutes = array_unique($booked_minutes, SORT_NUMERIC);
        $not_working_minutes = array_unique($not_working_minutes, SORT_NUMERIC);
        $day_minutes = array_unique($day_minutes, SORT_NUMERIC);

        if(empty($day_minutes)){
          $work_start_minutes = 0;
          $work_end_minutes = 0;
        }else{
          $work_start_minutes = min($day_minutes);
          $work_end_minutes = max($day_minutes);
        }
        $total_work_minutes = $work_end_minutes - $work_start_minutes;

        $day_class = 'os-day os-day-current week-day-'.strtolower($day_date->format('N')); 
        if($is_today) $day_class.= ' os-today';
        if($is_day_in_past) $day_class.= ' os-day-passed';
        if($is_target_month) $day_class.= ' os-month-current';
        if($is_next_month) $day_class.= ' os-month-next';
        if($is_prev_month) $day_class.= ' os-month-prev';
        if($not_in_allowed_period) $day_class.= ' os-not-in-allowed-period';
        if(($day_date->format('Y-m-d') == $target_date->format('Y-m-d')) && $settings['highlight_target_date']) $day_class.= ' selected';
      
        $addDivClassOsDayStatus = false;
        if(!$is_day_in_past && !$not_in_allowed_period){
          /* <div class="os-day-status"> */
          $addDivClassOsDayStatus = true;
        /*  $divClassAvailable = 'none'; */
            $divClassAvailable = new stdClass;
            if($total_work_minutes > 0){
              $interval_width = $interval / $total_work_minutes * 100;
              $available_blocks_count = 0;
              $not_available_started_count = 0;
              $total_day_minutes_count = count(array_filter($day_minutes, function($minute) use ($duration_minutes, $work_end_minutes){ return (($minute + $duration_minutes) <= $work_end_minutes); }));
              $processed_count = 0;
              $available_started_on = false;
              $prev_minute = false;
              foreach($day_minutes as $minute){
                if(in_array($minute, $available_minutes)){
                  if($available_started_on === false) $available_started_on = $processed_count;
                  $not_available_started_count = 0;
                  $available_blocks_count++;

                  if($prev_minute !== false && (($prev_minute + $interval) < $minute)){
                    $not_available_started_count = 1;

                  }
                }else{
                  if($available_blocks_count){
                    $left = ($available_started_on / $total_day_minutes_count * 100);
                    if(($minute + $duration_minutes) > $work_end_minutes){
                      $width = 100 - $left;
                    }else{
                      $width = ($available_blocks_count / $total_day_minutes_count * 100);
                    }
                  /*  $divClassAvailable = '<div class="day-available" style="left:'.$left.'%;width:'.$width.'%;"></div>'; */
                    $divClassAvailable->class = 'day-available';
                    $divClassAvailable->styleleft = ''.$left.'%';
                    $divClassAvailable->stylewidth = ''.$width.'%';
                  }
                  $not_available_started_count++;
                  $available_blocks_count = 0;
                  $available_started_on = false;
                }
                $prev_minute = $minute;
                $processed_count++;
              }
              if($available_started_on !== false){
                /* $divClassAvailable = '<div class="day-available" style="left:'.($available_started_on / $total_day_minutes_count * 100).'%;width:'.($available_blocks_count / $total_day_minutes_count * 100).'%;"></div>'; */
                $divClassAvailable->class = 'day-available';
                $divClassAvailable->styleleft = ''.($available_started_on / $total_day_minutes_count * 100).'%';
                $divClassAvailable->stylewidth = ''.($available_blocks_count / $total_day_minutes_count * 100).'%';
          
              }
            }
        }
          
        $dataToReturnDay = array(
        'class_osDayOsDayCurrentWeekDay' =>  $day_class, 
        'dataDate' => $day_date->format('Y-m-d'), 
        'dataNiceDate' => OsUtilHelper::get_month_name_by_number($day_date->format('n')).' '.$day_date->format('d'), 
        'dataServiceDuration' =>  $duration_minutes, 
        'dataTotalWorkMinutes' =>  $total_work_minutes, 
        'dataWorkStartTime' =>  $work_start_minutes, 
        'dataWorkEndTime' =>  $work_end_minutes, 
        'dataAvailableMinutes' =>  implode(',', $available_minutes), 
        'dataDayMinutes' =>  implode(',', $day_minutes), 
        'dataInterval' =>  $interval, 
        'osDayNumber' =>  $day_date->format('j'), 
        'class_osDayStatus' => $addDivClassOsDayStatus,
        'divClassAvailable' => $divClassAvailable,
        );

        array_push($dataToReturnDays, $dataToReturnDay);

      // DAYS LOOP END
      }

    return $dataToReturnMonthDays = array(
    'dataToReturnMonth' =>  $dataToReturnMonth,
    'class_osMonthlyCalendarDaysW' => $active_class, 
    'dataCalendarYear' => $target_date->format('Y'), 
    'dataCalendarMonth' => $target_date->format('n'), 
    'dataCalendarMonthLabel' =>  OsUtilHelper::get_month_name_by_number($target_date->format('n')), 
    'dataToReturnDays' => $dataToReturnDays,
    );
    
  }

}
}


// overriding methods in calendars_controllers.php
//-------------------------------------------------

//>>>TODO: straight in file calendars_controllers.php
//comment the actual functions of the plugin
//  load_monthly_calendar_days()
// and add the below revised methods


//revised function load_monthly_calendar_days (Author:Sandy)

/* public function load_monthly_calendar_days(){
  $target_date = new OsWpDateTime($this->params['target_date_string']);
  $service_id = $this->params['service_id'];
  $agent_id = $this->params['agent_id'];
  $location_id = isset($this->params['location_id']) ? $this->params['location_id'] : OsLocationHelper::get_selected_location_id();

  $calendar_settings = ['service_id' => $service_id, 'agent_id' => $agent_id, 'location_id' => $location_id];
  if(!isset($this->params['allow_full_access'])){
    $calendar_settings['earliest_possible_booking'] = OsSettingsHelper::get_settings_value('earliest_possible_booking', false);
    $calendar_settings['latest_possible_booking'] = OsSettingsHelper::get_settings_value('latest_possible_booking', false);
  }

  $this->format_render('_monthly_calendar_days', array('target_date' => $target_date, 'calendar_settings' => $calendar_settings), array(
    'target_date' => $target_date, 
    'calendar_settings' => $calendar_settings
  ));
} */








// overriding methods in auth_helpers.php

//>>>TODO: straight in file auth_helpers.php
//comment the actual functions of the plugin
//  public static function get_logged_in_customer_id()
//  public static function is_customer_logged_in()
// and add the below revised methods


//revised function get_logged_in_customer_id (Author:Sandy)

/* public static function get_logged_in_customer_id(){
  //return now the id of the wp_user from token
  $currentuserid_fromjwt = get_current_user_id();
  $currentuser = get_currentuserinfo();

  if ($currentuserid_fromjwt ){
    // should return the id of the customer who has the same e-mail as the wp_user
    $currentuseremail = (string) $currentuser->user_email;
    $customer = new OsCustomerModel();
    $customer_with_email_exist = $customer->where(array('email'=> $currentuseremail))->set_limit(1)->get_results_as_models();
    return $customer_with_email_exist->id;
  } else {
    return false;
  }
} */

//revised function is_customer_logged_in (Author:Sandy)

/* public static function is_customer_logged_in(){
  //return now the id of the wp_user from token
  $currentuserid_fromjwt = get_current_user_id();
  if ($currentuserid_fromjwt ){
    return $currentuserid_fromjwt;
  } else {
    return false;
  }
}
*/


//check if this need to be amended
//revised function get_highest_current_user_id (Author:Sandy)
  
  /* public static function get_highest_current_user_id(){
    $user_id = false;
    switch(self::get_highest_current_user_type()){
      case 'admin':
        $user_id = get_current_user_id();
      break;
      case 'agent':
        $user_id = self::get_logged_in_agent_id();
      break;
      case 'customer':
        $user_id = self::get_logged_in_wp_user_id();
      break;
    }
    return $user_id; 
  } */


// overriding methods in google_calendar_helper.php
//-------------------------------------------

//>>>TODO: straight in latepoint-google-calendar.php
//update the actual functions of the plugin with the below new class
//  process_action_booking_status_changed($booking_id, $old_status)
//  process_action_booking_created($booking)
//  process_action_booking_updated($booking)

if (class_exists('OsGoogleCalendarHelper')) {

class BookAppOsGoogleCalendarHelper extends OsGoogleCalendarHelper {


  //revised function (author: Sandy)
  //APP_TO_GOOGLE
  public static function create_or_update_booking_in_gcal($booking_id){
    //to be considered later: to pass the $google_calendar_receiving_appData_id as a parameter

    //passer le calendrier id
    $google_calendar_receiving_appData_id = $GLOBALS['id_google_calendar_receiving_appData'];

    $booking = new OsBookingModel();
    if(!$booking->load_by_id($booking_id)) return false;

    if($booking_id){

      $google_calendar_event_id = $booking->get_meta_by_key('google_calendar_event_id', false);

      $attendees = [['email' => $booking->customer->email, 'displayName' => $booking->customer->full_name]];
      $custom_fields_for_customer = OsCustomFieldsHelper::get_custom_fields_arr('customer');
      $description = __('Client: ', 'latepoint-google-calendar').$booking->customer->full_name."\r\n";
      $description.= __('Téléphone: ', 'latepoint-google-calendar').$booking->customer->phone."\r\n";
      $description.= __('Booking_ID: ', 'latepoint-google-calendar').$booking->id."\r\n";
      if ($booking->customer_comment){
        $description.= __('Commentaire_Client: ', 'latepoint-google-calendar').$booking->customer_comment."\r\n";
      }
      foreach($custom_fields_for_customer as $custom_field){
        $description.= $custom_field['label'].': '.$booking->customer->get_meta_by_key($custom_field['id'], '')."\r\n";
      }

      if($booking->status == LATEPOINT_BOOKING_STATUS_APPROVED){
        // Status Approved, add or update event in google calendar
        $body = array(
          "action"=> "createOrupdate",
          "calendarId" => $google_calendar_receiving_appData_id,
          "summary" => $booking->service->name,
          "location" => $booking->location->full_address,
          "attendees" => $attendees,
          "description" => $description,
          "bookingId" => $booking->id,
          "google_calendar_event_id" => $google_calendar_event_id,
          "start" => $booking->format_start_date_and_time_for_google(),
          "end" => $booking->format_end_date_and_time_for_google(),
          'timeZone' => OsTimeHelper::get_wp_timezone_name()
        );
      
        $args = array(
            "body" => json_encode($body),
            "headers" => array(
              'Content-Type' => 'application/json; charset=utf-8',
              'Accept' => '*/*'),
            "method"      => "POST",
            "data_format" => "body",
            "redirection" => 5
        );

        // new event in google cal
        //on ne récupèrera pas le google_event_id, mais on envoie à google le booking id pour qu'il garde trace
        $response = wp_remote_post( $GLOBALS['url_gas_calendar'], $args );
        if ( is_wp_error( $response ) ) {
          $error_message = $response->get_error_message();
        }
      
      }else{
        // Status Not Approved, remove event from calendar if exists and clean the booking meta
        $body = array(
          "action"=> "delete",
          "calendarId" => $google_calendar_receiving_appData_id,
          "summary" => $booking->service->name,
          "location" => $booking->location->full_address,
          "attendees" => $attendees,
          "description" => $description,
          "bookingId" => $booking->id,
          "google_calendar_event_id" => $google_calendar_event_id,
          "start" => $booking->format_start_date_and_time_for_google(),
          "end" => $booking->format_end_date_and_time_for_google(),
          'timeZone' => OsTimeHelper::get_wp_timezone_name()
        );
      
        $args = array(
            "body" => json_encode($body),
            "headers" => array(
              'Content-Type' => 'application/json; charset=utf-8',
              'Accept' => '*/*'),
            "method"      => "POST",
            "data_format" => "body",
            "redirection" => 5
        );

        // new event in google cal
        //on ne récupèrera pas le google_event_id, mais on envoie à google le booking id pour qu'il garde trace
        $response = wp_remote_post( $GLOBALS['url_gas_calendar'], $args );
        if ( is_wp_error( $response ) ) {
          $error_message = $response->get_error_message();
        }
        if($google_calendar_event_id){
          $booking->delete_meta_by_key('google_calendar_event_id');
        }
      }
      return true;
    }else{
      return false;
    }

  }


  //revised function (author: Sandy)
  //GOOGLE_TO_APP
  public static function create_or_update_google_event_in_db_bis($event, $google_event_id, $agent_id){
    echo('je cree le google event');
    echo($agent_id);
    if(!$google_event_id || !$agent_id) return true;
    $start_date_obj = OsWpDateTime::os_get_start_of_google_event($event);
    $end_date_obj = OsWpDateTime::os_get_end_of_google_event($event);
  
    // save event info to our database
    $google_calendar_event_in_db = new OsGoogleCalendarEventModel();
    $event_in_db = $google_calendar_event_in_db->where(['google_event_id' => $google_event_id])->set_limit(1)->get_results_as_models();

    if(!$event_in_db){
      // create new
      $event_in_db = new OsGoogleCalendarEventModel();
      $event_in_db->google_event_id = $google_event_id;
    }

    $event_in_db->agent_id = $agent_id;
    $event_in_db->summary = $event->summary;
   /*  $event_in_db->html_link = $event_in_gcal->getHtmlLink(); */
    $event_in_db->start_date = $start_date_obj->format('Y-m-d H:i:s');
    $event_in_db->start_time = OsTimeHelper::convert_time_to_minutes($start_date_obj->format('H:i'), false);
    $event_in_db->end_date = $end_date_obj->format('Y-m-d H:i:s');
    $event_in_db->end_time = OsTimeHelper::convert_time_to_minutes($end_date_obj->format('H:i'), false);
    
    if($event->recurringEventId){
      $event_in_db->recurring_event_id = $event->recurringEventId;
    } else {
      $event_in_db->recurring_event_id = NULL;
    }
    
    if($event->recurrence_rule){
      $event_in_db->recurrence_rule = $event->recurrence_rule[0];
    } else {
      $event_in_db->recurrence_rule = NULL;
    }
    
    $result = $event_in_db->save();
    if($result && $event->recurrences){
      $recurrences = self::get_gcal_event_recurrences_bis($event);
      $event_in_db->update_recurrences($recurrences);
    }

    return $result;
  }


  //revised function (author: Sandy)
  public static function get_gcal_event_recurrences_bis($gcal_event, $split_weekdays = true){
   /*  echo('jsuis ds get_gcal_event_recurrences_bis '); */
    $rrule = false;
    $gcal_recurrence = new OsGcalEventRecurrenceModel();
    /* foreach($gcal_event->getRecurrence() as $rec){ */
    foreach($gcal_event->recurrences as $rec){
      if(!strstr($rec, 'RRULE:')) continue;
      $rrule = str_replace('RRULE:', '', $rec);
    }
    if(!$rrule) return false;
    $rrules = false;
    parse_str((str_replace(';', '&', $rrule)), $rrules);
    if(!$rrules) return false;
    //$rrules = explode(';', $rrule);
    $gcal_recurrence->start_date = $gcal_event->start_date;
    if(isset($rrules['FREQ'])) $gcal_recurrence->frequency = $rrules['FREQ'];
    if(isset($rrules['UNTIL'])){
      $rrules['UNTIL'] = strtok($rrules['UNTIL'], 'T');
      $gcal_recurrence->until = $rrules['UNTIL'];
    }
    if(isset($rrules['INTERVAL'])){
      $gcal_recurrence->interval = $rrules['INTERVAL'];
    }else{
      $gcal_recurrence->interval = 1;
    }
    if(isset($rrules['COUNT'])) $gcal_recurrence->count = $rrules['COUNT'];
    
    if(isset($rrules['BYDAY'])){
      $gcal_recurrences = [];
      $weekdays = explode(',', $rrules['BYDAY']);
      if($split_weekdays && (count($weekdays) > 1)){
        echo('$split_weekdays');
        echo($split_weekdays);
        foreach($weekdays as $byday){
          echo('$byday');
          echo($byday);
          $gcal_recurrence->weekday = $byday;
          $gcal_recurrences[] = clone $gcal_recurrence;
        }
      }else{
        $gcal_recurrence->weekday = $rrules['BYDAY'];
      }
    }
    if(empty($gcal_recurrences)){
      $gcal_recurrences[] = $gcal_recurrence;
    }
    return $gcal_recurrences;
  }


  //added function (author: Sandy)
  //GOOGLE_TO_APP
  //this function will make a doPost call and send back to google both events exception and original series in order to amend them in google dataBase
  public static function handle_exception_in_gcal($exception_googleEvent_id, $googleEventSeries_id){

      //passer le calendrier id
      $google_calendar_saisie_manuelle_id = $GLOBALS['id_google_calendar_saisie_manuelle'];

      $parentEventRecordedInWp = OsGoogleCalendarHelper::get_record_by_google_event_id($googleEventSeries_id);

      //attention ds le cas d'une annulation, l'exception n'a pas été enregistrée en base de données. Dc impossible de récupérer les datas.
      $exeptionEventRecordedInWp = OsGoogleCalendarHelper::get_record_by_google_event_id($exception_googleEvent_id);

      //prepare datetime to correct format for google. To do so, use the method in booking object. We need to prepare both 
      // $parentEventRecordedInWp and $exeptionEventRecordedInWp;

      $booking_obj_parent = new OsBookingModel();
      
      if ($parentEventRecordedInWp){

        $booking_obj_parent->start_date = $parentEventRecordedInWp->start_date;
        $booking_obj_parent->end_date = $parentEventRecordedInWp->end_date;
        $booking_obj_parent->start_time = $parentEventRecordedInWp->start_time;
        $booking_obj_parent->end_time = $parentEventRecordedInWp->end_time;
        $parent_start = $booking_obj_parent->format_start_date_and_time_for_google();
        $parent_end = $booking_obj_parent->format_end_date_and_time_for_google();
        $parentEventRecordedInWp->start = $parent_start;
        $parentEventRecordedInWp->end = $parent_end;

        $booking_obj_exception = new OsBookingModel();

              if ($exeptionEventRecordedInWp){
                /* echo('il a retourné un $exeptionEventRecordedInWp'); */
                $booking_obj_exception->start_date = $exeptionEventRecordedInWp->start_date;
                $booking_obj_exception->end_date = $exeptionEventRecordedInWp->end_date;
                $booking_obj_exception->start_time = $exeptionEventRecordedInWp->start_time;
                $booking_obj_exception->end_time = $exeptionEventRecordedInWp->end_time;
                $exception_start = $booking_obj_exception->format_start_date_and_time_for_google();
                $exception_end = $booking_obj_exception->format_end_date_and_time_for_google();
                $exeptionEventRecordedInWp->start = $exception_start;
                $exeptionEventRecordedInWp->end = $exception_end;

                $body = array(
                  "action"=> "handleException",
                  "calendarId" => $google_calendar_saisie_manuelle_id,
                  "summary" => $exeptionEventRecordedInWp->summary,
                  "google_calendar_event_id" => $exception_googleEvent_id,
                  "start" => $exeptionEventRecordedInWp->start, 
                  "end" => $exeptionEventRecordedInWp->end, 
                  "parentSeries" => $parentEventRecordedInWp,
                  'timeZone' => OsTimeHelper::get_wp_timezone_name()
                );
              }

              else {
                  $body = array(
                    "action"=> "handleException",
                    "calendarId" => $google_calendar_saisie_manuelle_id,
                    "summary" => "not recorded in WP",
                    "google_calendar_event_id" => $exception_googleEvent_id,
                    "start" => "not recorded in WP", 
                    "end" => "not recorded in WP", 
                    "parentSeries" => $parentEventRecordedInWp,
                    'timeZone' => OsTimeHelper::get_wp_timezone_name()
                  );
              }

              $args = array(
                  "body" => json_encode($body),
                  "headers" => array(
                    'Content-Type' => 'application/json; charset=utf-8',
                    'Accept' => '*/*'),
                  "method"      => "POST",
                  "data_format" => "body",
                  "redirection" => 5
              );

              // new event in google cal
              $response = wp_remote_post( $GLOBALS['url_gas_calendar'], $args );
              if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
              }   

      }

  }
  




}
}
