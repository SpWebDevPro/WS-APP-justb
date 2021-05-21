<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}


if ( ! class_exists( 'OsBookingsController' ) ) :


  class OsBookingsController extends OsController {

    private $booking;

    function __construct(){
      parent::__construct();

      $this->views_folder = LATEPOINT_VIEWS_ABSPATH . 'bookings/';
      $this->vars['page_header'] = __('Appointments', 'latepoint');
      $this->vars['breadcrumbs'][] = array('label' => __('Appointments', 'latepoint'), 'link' => OsRouterHelper::build_link(OsRouterHelper::build_route_name('bookings', 'pending_approval') ) );
    }



    public function pending_approval(){
      $this->vars['page_header'] = __('Pending Appointments', 'latepoint');
      $this->vars['breadcrumbs'][] = array('label' => __('Pending Appointments', 'latepoint'), 'link' => false );

      $page_number = isset($this->params['page_number']) ? $this->params['page_number'] : 1;
      $per_page = 20;
      $offset = ($page_number > 1) ? (($page_number - 1) * $per_page) : 0;

      $bookings = new OsBookingModel();
      $query_args = ['location_id' => OsLocationHelper::get_selected_location_id(), 'status' => [LATEPOINT_BOOKING_STATUS_PENDING, LATEPOINT_BOOKING_STATUS_PAYMENT_PENDING]];

      if($this->logged_in_agent_id) $query_args['agent_id'] = $this->logged_in_agent_id;
      $this->vars['bookings'] = $bookings->where($query_args)->set_limit($per_page)->set_offset($offset)->order_by('id desc')->get_results_as_models();

      $count_bookings = new OsBookingModel();
      $total_bookings = $count_bookings->where($query_args)->count();
      $total_pages = ceil($total_bookings / $per_page);

      $this->vars['total_pages'] = $total_pages;
      $this->vars['total_bookings'] = $total_bookings;
      $this->vars['per_page'] = $per_page;
      $this->vars['current_page_number'] = $page_number;

      $this->vars['showing_from'] = (($page_number - 1) * $per_page) ? (($page_number - 1) * $per_page) : 1;
      $this->vars['showing_to'] = min($page_number * $per_page, $this->vars['total_bookings']);

      $this->format_render(__FUNCTION__);
    }


    public function index(){
      
      $this->vars['page_header'] = __('Bookings', 'latepoint');
      $this->vars['breadcrumbs'][] = array('label' => __('All', 'latepoint'), 'link' => false );

      $page_number = isset($this->params['page_number']) ? $this->params['page_number'] : 1;
      $per_page = 20;
      $offset = ($page_number > 1) ? (($page_number - 1) * $per_page) : 0;


      $bookings = new OsBookingModel();
      $query_args = ['location_id' => OsLocationHelper::get_selected_location_id()];

      if($this->logged_in_agent_id) $query_args['agent_id'] = $this->logged_in_agent_id;
      $filter = isset($this->params['filter']) ? $this->params['filter'] : false;

      // TABLE SEARCH FILTERS
      if($filter){
        if($filter['service_id']) $query_args['service_id'] = $filter['service_id'];
        if($filter['agent_id']) $query_args['agent_id'] = $filter['agent_id'];
        if($filter['status']) $query_args[LATEPOINT_TABLE_BOOKINGS.'.status'] = $filter['status'];
        if($filter['id']) $query_args['id'] = $filter['id'];
        if($filter['created_date_from']){
          $query_args[LATEPOINT_TABLE_BOOKINGS.'.created_at >='] = $filter['created_date_from'].' 00:00:00';
          $query_args[LATEPOINT_TABLE_BOOKINGS.'.created_at <='] = $filter['created_date_from'].' 23:59:59';
        }
        if($filter['booking_date_from'] && $filter['booking_date_to']){
          $query_args[LATEPOINT_TABLE_BOOKINGS.'.start_date >='] = $filter['booking_date_from'];
          $query_args[LATEPOINT_TABLE_BOOKINGS.'.start_date <='] = $filter['booking_date_to'];
        }
        if($filter['customer']){
          $bookings->select(LATEPOINT_TABLE_BOOKINGS.'.*, '.LATEPOINT_TABLE_CUSTOMERS.'.first_name, '.LATEPOINT_TABLE_CUSTOMERS.'.last_name');
          $bookings->join(LATEPOINT_TABLE_CUSTOMERS, [LATEPOINT_TABLE_CUSTOMERS.'.id' => 'customer_id']);
          $query_args['CONCAT('.LATEPOINT_TABLE_CUSTOMERS.'.first_name, " " ,'.LATEPOINT_TABLE_CUSTOMERS.'.last_name) LIKE'] = '%'.$filter['customer'].'%';
          $this->vars['customer_name_query'] = $filter['customer'];
        }
      }

      if($this->logged_in_agent_id){
        $query_args['agent_id'] = $this->logged_in_agent_id;
        $this->vars['show_single_agent'] = $this->logged_in_agent;
      }else{
        $this->vars['show_single_agent'] = false;
      }

      // OUTPUT CSV IF REQUESTED
      if(isset($this->params['download']) && $this->params['download'] == 'csv'){
        $csv_filename = 'all_bookings_'.OsUtilHelper::random_text().'.csv';
        
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename={$csv_filename}.csv");

        $labels_row = [  __('ID', 'latepoint'), 
                              __('Service', 'latepoint'), 
                              __('Start Date & Time', 'latepoint'), 
                              __('Duration', 'latepoint'), 
                              __('Customer', 'latepoint'), 
                              __('Customer Phone', 'latepoint'), 
                              __('Customer Email', 'latepoint'), 
                              __('Agent', 'latepoint'), 
                              __('Agent Phone', 'latepoint'), 
                              __('Agent Email', 'latepoint'), 
                              __('Status', 'latepoint'), 
                              __('Booked On', 'latepoint') ];


        $custom_fields_for_customer = OsCustomFieldsHelper::get_custom_fields_arr('customer');
        foreach($custom_fields_for_customer as $custom_field){
          $labels_row[] = $custom_field['label'];
        }

        $bookings_data = [];
        $bookings_data[] = $labels_row;


        $bookings_arr = $bookings->where($query_args)->order_by('id desc')->get_results_as_models();                              
        if($bookings_arr){
          foreach($bookings_arr as $booking){
            $values_row = [  $booking->id, 
                                  $booking->service->name, 
                                  $booking->nice_start_date_time, 
                                  $booking->get_total_duration(), 
                                  $booking->customer->full_name, 
                                  $booking->customer->phone, 
                                  $booking->customer->email, 
                                  $booking->agent->full_name, 
                                  $booking->agent->phone, 
                                  $booking->agent->email, 
                                  $booking->nice_status, 
                                  $booking->nice_created_at];
            foreach($custom_fields_for_customer as $custom_field){
              $values_row[] = $booking->customer->get_meta_by_key($custom_field['id'], '');
            }
            $bookings_data[] = $values_row;
          }

        }
        OsCSVHelper::array_to_csv($bookings_data);
        return;
      }

      $this->vars['bookings'] = $bookings->where($query_args)->set_limit($per_page)->set_offset($offset)->order_by('id desc')->get_results_as_models();

      $count_total_bookings = new OsBookingModel();
      if($filter['customer']){
        $count_total_bookings->join(LATEPOINT_TABLE_CUSTOMERS, [LATEPOINT_TABLE_CUSTOMERS.'.id' => 'customer_id']);
      }
      $total_bookings = $count_total_bookings->where($query_args)->count();
      $this->vars['total_bookings'] = $total_bookings;
      $total_pages = ceil($total_bookings / $per_page);

      $this->vars['total_pages'] = $total_pages;
      $this->vars['per_page'] = $per_page;
      $this->vars['current_page_number'] = $page_number;
      
      $this->vars['showing_from'] = (($page_number - 1) * $per_page) ? (($page_number - 1) * $per_page) : 1;
      $this->vars['showing_to'] = min($page_number * $per_page, $this->vars['total_bookings']);

      $this->format_render(['json_view_name' => '_table_body', 'html_view_name' => __FUNCTION__], [], ['total_pages' => $total_pages, 'showing_from' => $this->vars['showing_from'], 'showing_to' => $this->vars['showing_to'], 'total_records' => $total_bookings]);
    }

    function quick_availability(){
      $this->update_formatted_time_params();

      $booking = new OsBookingModel();
      $this->params['booking']['start_date'] = OsTimeHelper::reformat_date_string($this->params['booking']['start_date'], OsSettingsHelper::get_date_format(), 'Y-m-d');
      $booking->set_data($this->params['booking']);

      $calendar_start_date_obj = isset($this->params['start_date']) ? new OsWpDateTime($this->params['start_date']) : new OsWpDateTime($booking->start_date);
      $calendar_start_date = $calendar_start_date_obj->format('Y-m-d');
      $calendar_end_date = $calendar_start_date_obj->modify('+30 days')->format('Y-m-d');
      
      if($this->logged_in_agent_id) $booking->agent_id = $this->logged_in_agent_id;

      $dated_work_periods_arr = OsBookingHelper::get_work_periods_for_date_range($calendar_start_date, $calendar_end_date, ['service_id' => $booking->service_id, 'agent_id' => $booking->agent_id, 'location_id' => $booking->location_id]);
      $work_start_end = OsBookingHelper::get_work_start_end_time_for_date_range($dated_work_periods_arr);

      $this->vars['booking'] = $booking;
      $this->vars['work_start_end'] = $work_start_end;
      $this->vars['show_days_only'] = isset($this->params['show_days_only']) ? true : false;
      
      $this->vars['timeblock_interval'] = $booking->service->get_timeblock_interval();
      $this->vars['days_availability_html'] = OsBookingHelper::get_quick_availability_days($calendar_start_date, $booking->agent, $booking->service, $booking->location, $work_start_end, 30, $booking->get_total_duration() );
      $this->vars['calendar_start_date'] = $calendar_start_date;
      $this->vars['calendar_end_date'] = $calendar_end_date;

      $agents = new OsAgentModel();
      if($this->logged_in_agent_id) $agents->where(['id' => $this->logged_in_agent_id]);
      $this->vars['agents'] = $agents->get_results_as_models();

      $this->format_render(__FUNCTION__);
    }

    function calculate_full_price(){
      $booking = new OsBookingModel();
      $booking->set_data($this->params['booking']);
      if($this->get_return_format() == 'json'){
        $this->send_json(array('status' => LATEPOINT_STATUS_SUCCESS, 'message' => $booking->formatted_full_price()));
      }
    }

    function request_cancellation(){
      $booking_id = $this->params['id'];
      $booking = new OsBookingModel($booking_id);
      if(OsAuthHelper::get_logged_in_customer_id() == $booking->customer_id){
        $this->params['status'] = LATEPOINT_BOOKING_STATUS_CANCELLED;
        $this->change_status();
      }else{
        $status = LATEPOINT_STATUS_ERROR;
        $response_html = __('Error! JSf29834', 'latepoint');
      }
      if($this->get_return_format() == 'json'){
        $this->send_json(array('status' => $status, 'message' => $response_html));
      }
    }

    function change_status(){
      $booking_id = $this->params['id'];
      $new_status = $this->params['status'];
      $booking = new OsBookingModel($booking_id);
      $old_status = $booking->status;
      $old_status_nice = $booking->nice_status;
      $booking->status = $new_status;
      if($new_status == $old_status){
        $response_html = __('Appointment Status Updated', 'latepoint');
        $status = LATEPOINT_STATUS_SUCCESS;
      }else{
        if($booking->save()){
          $response_html = __('Appointment Status Updated', 'latepoint');
          $status = LATEPOINT_STATUS_SUCCESS;
          OsNotificationsHelper::process_booking_status_changed_notifications($booking, $old_status_nice);
          do_action('latepoint_booking_updated_admin', $booking);
          do_action('latepoint_booking_status_changed', $booking, $old_status);
          OsActivitiesHelper::create_activity(array('code' => 'booking_change_status', 'booking' => $booking, 'old_value' => $old_status));
        }else{
          $response_html = $booking->get_error_messages();
          $status = LATEPOINT_STATUS_ERROR;
        }
      }

      if($this->get_return_format() == 'json'){
        $this->send_json(array('status' => $status, 'message' => $response_html));
      }
    }


    function ical_download(){
      $booking_id = $this->params['latepoint_booking_id'];
      if($booking_id){
        $booking = new OsBookingModel($booking_id);
        if($booking->id && OsAuthHelper::is_customer_logged_in() && ($booking->customer_id == OsAuthHelper::get_logged_in_customer_id())){

          header('Content-Type: text/calendar; charset=utf-8');
          header('Content-Disposition: attachment; filename=booking_'.$booking->id.'.ics');

          $booking_description = sprintf(__('Appointment with %s for %s', 'latepoint'), $booking->agent->full_name, $booking->service->name);

          $ics = new ICS(array(
            'location' => $booking->location->full_address,
            'description' => '',
            'dtstart' => $booking->nice_start_date_time,
            'dtend' => $booking->nice_end_date_time,
            'summary' => $booking_description,
            'url' => get_site_url()
          ));

          echo $ics->to_string();
        }
      }
    }

    // Changes timezone for customer, called from booking form timezone selector on change
    public function change_timezone(){
      $timezone_name = $this->params['timezone_name'];
      OsTimeHelper::set_timezone_name_in_session($timezone_name);
      if(OsAuthHelper::is_customer_logged_in()){
        OsMetaHelper::save_customer_meta_by_key('timezone_name', $timezone_name, OsAuthHelper::get_logged_in_customer_id());
      }

      if($this->get_return_format() == 'json'){
        $this->send_json(array('status' => LATEPOINT_STATUS_SUCCESS, 'message' => __('Timezone Updated', 'latepoint')));
      }
    }

    private function update_formatted_time_params(){
      if(isset($this->params['booking']['start_time']['formatted_value'])){
        $start_ampm = isset($this->params['booking']['start_time']['ampm']) ? $this->params['booking']['start_time']['ampm'] : false;
        $end_ampm = isset($this->params['booking']['end_time']['ampm']) ? $this->params['booking']['end_time']['ampm'] : false;
        $this->params['booking']['start_time'] = OsTimeHelper::convert_time_to_minutes($this->params['booking']['start_time']['formatted_value'], $start_ampm);
        $this->params['booking']['end_time'] = OsTimeHelper::convert_time_to_minutes($this->params['booking']['end_time']['formatted_value'], $end_ampm);
      }
    }

    /*
      Create booking (used in admin on quick side form save)
    */

    public function create(){
      if($this->params['booking']['id']){
        $this->update();
        return;
      }
      $form_values_to_update = array();
      $this->update_formatted_time_params();
      
      $customer_params = $this->params['customer'];
      $this->params['booking']['start_date'] = OsTimeHelper::reformat_date_string($this->params['booking']['start_date'], OsSettingsHelper::get_date_format(), 'Y-m-d');
      $booking_params = $this->params['booking'];
      $custom_fields_data = isset($customer_params['custom_fields']) ? $customer_params['custom_fields'] : [];

      $booking = new OsBookingModel();
      $booking->set_data($booking_params);

      // Customer update/create
      if($booking->customer_id){
        $customer = new OsCustomerModel($booking->customer_id);
        $is_new_customer = false;
      }else{
        $customer = new OsCustomerModel();
        $is_new_customer = true;
      }
      $customer->set_data($customer_params);
      if($customer->validate_custom_fields($custom_fields_data) && $customer->save()){
        $customer->save_custom_fields($custom_fields_data);
        if($is_new_customer){
          OsNotificationsHelper::process_new_customer_notifications($customer);
          OsActivitiesHelper::create_activity(array('code' => 'customer_create', 'customer_id' => $customer->id));
        }

        $booking->customer_id = $customer->id;
        $form_values_to_update['booking[customer_id]'] = $booking->customer_id;
        if($booking->save()){
          $form_values_to_update['booking[id]'] = $booking->id;
          $response_html = __('Appointment Added: ID#', 'latepoint') . $booking->id;
          $status = LATEPOINT_STATUS_SUCCESS;
          do_action('latepoint_booking_created_admin', $booking);
          OsNotificationsHelper::process_new_booking_notifications($booking);
          OsActivitiesHelper::create_activity(array('code' => 'booking_create', 'booking' => $booking));
        }else{
          $response_html = $booking->get_error_messages();
          $status = LATEPOINT_STATUS_ERROR;
        }
      }else{
        // error customer validation/saving
        $status = LATEPOINT_STATUS_ERROR;
        $response_html = $customer->get_error_messages();
        if(is_array($response_html)) $response_html = implode(', ', $response_html);
      }
      if($this->get_return_format() == 'json'){
        $this->send_json(array('status' => $status, 'message' => $response_html, 'form_values_to_update' => $form_values_to_update));
      }
    }


    /*
      Update booking
    */

    public function update(){
      $this->update_formatted_time_params();

      $customer_params = $this->params['customer'];
      $this->params['booking']['start_date'] = OsTimeHelper::reformat_date_string($this->params['booking']['start_date'], OsSettingsHelper::get_date_format(), 'Y-m-d');
      $booking_params = $this->params['booking'];
      $custom_fields_data = isset($customer_params['custom_fields']) ? $customer_params['custom_fields'] : [];

      $booking = new OsBookingModel($booking_params['id']);
      $old_status = $booking->status;
      $old_status_nice = $booking->nice_status;
      $booking->set_data($booking_params);

      // Customer update/create
      if($booking->customer_id){
        $customer = new OsCustomerModel($booking->customer_id);
        $is_new_customer = false;
      }else{
        $customer = new OsCustomerModel();
        $is_new_customer = true;
      }
      $customer->set_data($customer_params);
      if($customer->validate_custom_fields($custom_fields_data) && $customer->save()){
        $customer->save_custom_fields($custom_fields_data);
        if($is_new_customer){
          OsNotificationsHelper::process_new_customer_notifications($customer);
          OsActivitiesHelper::create_activity(array('code' => 'customer_create', 'customer_id' => $customer->id));
        }

        $booking->customer_id = $customer->id;
        $form_values_to_update['booking[customer_id]'] = $booking->customer_id;
        if($booking->save()){
          do_action('latepoint_booking_updated_admin', $booking);
          OsActivitiesHelper::create_activity(array('code' => 'booking_update', 'booking' => $booking));
          if($old_status != $booking->status){
            OsNotificationsHelper::process_booking_status_changed_notifications($booking, $old_status_nice);
            do_action('latepoint_booking_status_changed', $booking, $old_status);
          }
          $response_html = __('Appointment Updated: ID#', 'latepoint') . $booking->id;
          $status = LATEPOINT_STATUS_SUCCESS;
        }else{
          $response_html = $booking->get_error_messages();
          $status = LATEPOINT_STATUS_ERROR;
        }
      }else{
        // error customer validation/saving
        $status = LATEPOINT_STATUS_ERROR;
        $response_html = $customer->get_error_messages();
        if(is_array($response_html)) $response_html = implode(', ', $response_html);
      }
      if($this->get_return_format() == 'json'){
        $this->send_json(array('status' => $status, 'message' => $response_html));
      }
    }


    function customer_quick_edit_form(){
      $selected_customer = new OsCustomerModel();
      if(isset($this->params['customer_id'])){
        $selected_customer->load_by_id($this->params['customer_id']);
      }
      $this->vars['custom_fields_for_customer'] = OsCustomFieldsHelper::get_custom_fields_arr('customer');
      $this->vars['selected_customer'] = $selected_customer;
      $this->format_render(__FUNCTION__);
    }

    function edit_form(){
      $agents = new OsAgentModel();
      $agents_arr = $agents->get_results();
      $this->vars['agents'] = $agents_arr;

      $customers = new OsCustomerModel();
      $customers_arr = $customers->get_results();
      $this->vars['customers'] = $customers_arr;

      $booking_id = $this->params['id'];

      $booking = new OsBookingModel($booking_id);

      $service = new OsServiceModel();
      $services = $service->get_results_as_models();

      $selected_agent = new OsAgentModel($booking->agent_id);
      $selected_customer = new OsCustomerModel($booking->customer_id);

      $this->vars['services'] = $services;
      $this->vars['booking'] = $booking;
      $this->vars['selected_agent'] = $selected_agent;
      $this->vars['selected_customer'] = $selected_customer;
      $this->format_render(__FUNCTION__);
    }

    function quick_edit_form(){
      $agents = new OsAgentModel();
      if($this->logged_in_agent_id) $agents->where(['id' => $this->logged_in_agent_id]);
      $agents_arr = $agents->get_results();
      $this->vars['agents'] = $agents_arr;

      $customers = new OsCustomerModel();
      $customers_arr = $customers->get_results_as_models();
      $this->vars['customers'] = $customers_arr;

      $booking_id = $this->params['id'];

      $booking = new OsBookingModel($booking_id);

      $transactions_model = new OsTransactionModel();
      $transactions = $transactions_model->where(['booking_id' => $booking_id])->get_results_as_models();

      $service = new OsServiceModel();
      $services = $service->get_results_as_models();

      $selected_agent = new OsAgentModel($booking->agent_id);
      $selected_customer = new OsCustomerModel($booking->customer_id);

      $this->vars['custom_fields_for_customer'] = OsCustomFieldsHelper::get_custom_fields_arr('customer');
      $service_categories = new OsServiceCategoryModel();
      $service_categories = $service_categories->get_results_as_models();
      $this->vars['service_categories'] = $service_categories;
      $this->vars['services'] = $services;

      $services = new OsServiceModel();
      $this->vars['uncategorized_services'] = $services->where(array('category_id' => ['OR' => [0, 'IS NULL']]))->order_by('order_number asc')->get_results_as_models();

      $this->vars['booking'] = $booking;
      $this->vars['selected_agent'] = $selected_agent;
      $this->vars['selected_customer'] = $selected_customer;
      $this->vars['transactions'] = $transactions;
      $this->format_render(__FUNCTION__);
    }


    function quick_new_form(){
      $agents = new OsAgentModel();
      if($this->logged_in_agent_id) $agents->where(['id' => $this->logged_in_agent_id]);
      $agents_arr = $agents->get_results();
      $this->vars['agents'] = $agents_arr;

      $customers = new OsCustomerModel();

      if($this->logged_in_agent_id){
        $customers->select(LATEPOINT_TABLE_CUSTOMERS.'.*')->join(LATEPOINT_TABLE_BOOKINGS, ['customer_id' => LATEPOINT_TABLE_CUSTOMERS.'.id'])->group_by(LATEPOINT_TABLE_CUSTOMERS.'.id')->where(['agent_id' => $this->logged_in_agent_id]);
      }

      $customers_arr = $customers->get_results_as_models();
      $this->vars['customers'] = $customers_arr;
      
      $booking = new OsBookingModel();
      $service = new OsServiceModel();
      $services = $service->get_results_as_models();

      $booking->agent_id = isset($this->params['agent_id']) ? $this->params['agent_id'] : '';
      $booking->service_id = isset($this->params['service_id']) ? $this->params['service_id'] : '';
      $booking->customer_id = isset($this->params['customer_id']) ? $this->params['customer_id'] : '';
      $booking->location_id = isset($this->params['location_id']) ? $this->params['location_id'] : OsLocationHelper::get_selected_location_id();

      $booking->start_date = isset($this->params['start_date']) ? $this->params['start_date'] : OsTimeHelper::today_date('Y-m-d');
      $booking->start_time = isset($this->params['start_time']) ? $this->params['start_time'] : 600;

      $booking->end_date = $booking->start_date;
      $booking->end_time = ($booking->service_id) ? $booking->calculate_end_time() : 660;
      $booking->buffer_before = $booking->service->buffer_before;
      $booking->buffer_after = $booking->service->buffer_after;
      $booking->status = 'approved';

      $selected_customer = new OsCustomerModel($booking->customer_id);
      $this->vars['custom_fields_for_customer'] = OsCustomFieldsHelper::get_custom_fields_arr('customer');
      $this->vars['selected_customer'] = $selected_customer;
      $service_categories = new OsServiceCategoryModel();
      $service_categories = $service_categories->get_results_as_models();
      $this->vars['service_categories'] = $service_categories;
      $this->vars['services'] = $services;

      $services = new OsServiceModel();
      $this->vars['uncategorized_services'] = $services->where(array('category_id' => ['OR' => [0, 'IS NULL']]))->order_by('order_number asc')->get_results_as_models();
      
      $this->vars['booking'] = $booking;
      $this->vars['transactions'] = false;
      $this->format_render(__FUNCTION__);
    }





































  }

endif;