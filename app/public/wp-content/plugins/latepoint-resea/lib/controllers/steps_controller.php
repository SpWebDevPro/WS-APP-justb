<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}


if ( ! class_exists( 'OsStepsController' ) ) :


  class OsStepsController extends OsController {

    private $booking;

    function __construct(){
      parent::__construct();

      $this->views_folder = LATEPOINT_VIEWS_ABSPATH . 'steps/';
      $this->vars['page_header'] = __('Appointments', 'latepoint');
      $this->vars['breadcrumbs'][] = array('label' => __('Appointments', 'latepoint'), 'link' => OsRouterHelper::build_link(OsRouterHelper::build_route_name('bookings', 'pending_approval') ) );
    }


    public function start($restrictions = false, $output = true){
      OsStepsHelper::set_booking_object();
      if((!$restrictions || empty($restrictions)) && isset($this->params['restrictions'])) $restrictions = $this->params['restrictions'];
      OsStepsHelper::set_restrictions($restrictions);
      OsStepsHelper::get_step_names_in_order();
      OsStepsHelper::remove_already_selected_steps();

      $this->steps_models = OsStepsHelper::load_steps_as_models(OsStepsHelper::get_step_names_in_order());

      $active_step_model = $this->steps_models[0];

      // if is payment step - check if total is not $0 and if it is skip payment step
      if(OsStepsHelper::should_step_be_skipped($active_step_model->name)){
          $active_step_model = $this->steps_models[1];
      }

      $this->vars['show_next_btn'] = OsStepsHelper::can_step_show_next_btn($active_step_model->name);
      $this->vars['show_prev_btn'] = OsStepsHelper::can_step_show_prev_btn($active_step_model->name);
      $this->vars['steps_models'] = $this->steps_models;
      $this->vars['active_step_model'] = $active_step_model;

      $this->vars['current_step'] = $active_step_model->name;
      $this->vars['booking'] = OsStepsHelper::$booking_object;
      $this->vars['restrictions'] = OsStepsHelper::$restrictions;
      $this->set_layout('none');
     
     if($output){
        $this->format_render(__FUNCTION__, array(), array('step' => $active_step_model->name));
      }else{
        return $this->format_render_return(__FUNCTION__, array(), array('step' => $active_step_model->name));
      }
    } 

    //commented for now. Check later how to overload because i have changed the  number of arguments
   /*  public function get_step(){

      if(!OsStepsHelper::is_valid_step($this->params['current_step'])) return false;
      OsStepsHelper::set_booking_object($this->params['booking']);
      OsStepsHelper::set_restrictions($this->params['restrictions']);
      OsStepsHelper::get_step_names_in_order();
      OsStepsHelper::remove_already_selected_steps();
      // Check if a valid step name
      $current_step = $this->params['current_step'];
      if(!in_array($current_step, OsStepsHelper::get_step_names_in_order())) return false;
      $step_direction = isset($this->params['step_direction']) ? $this->params['step_direction'] : 'next';
      switch ($step_direction) {
        case 'next':
		      do_action('latepoint_process_step', $current_step, OsStepsHelper::$booking_object);
		      $step_name_to_load = OsStepsHelper::get_next_step_name($current_step);
          break;
        case 'prev':
		      $step_name_to_load = OsStepsHelper::get_prev_step_name($current_step);
          break;
        case 'specific':
	        $step_name_to_load = OsStepsHelper::should_step_be_skipped($current_step) ? OsStepsHelper::get_next_step_name($current_step) : $current_step;
          break;
      }
      if($step_name_to_load){
        do_action('latepoint_load_step', $step_name_to_load, OsStepsHelper::$booking_object);
      }
    } */


    
    //revised function start (Author:Sandy)
    //*************************************

    /* public function start($restrictions = false, $output = false){
      OsStepsHelper::set_booking_object();
      if((!$restrictions || empty($restrictions)) && isset($this->params['restrictions'])) $restrictions = $this->params['restrictions'];
      OsStepsHelper::set_restrictions($restrictions);
      OsStepsHelper::get_step_names_in_order();
      OsStepsHelper::remove_already_selected_steps();

      $this->steps_models = OsStepsHelper::load_steps_as_models(OsStepsHelper::get_step_names_in_order());

      $active_step_model = $this->steps_models[0];

      // if is payment step - check if total is not $0 and if it is skip payment step
      if(OsStepsHelper::should_step_be_skipped($active_step_model->name)){
          $active_step_model = $this->steps_models[1];
      }

      $this->vars['show_next_btn'] = OsStepsHelper::can_step_show_next_btn($active_step_model->name);
      $this->vars['show_prev_btn'] = OsStepsHelper::can_step_show_prev_btn($active_step_model->name);
      $this->vars['steps_models'] = $this->steps_models;
      $this->vars['active_step_model'] = $active_step_model;

      $this->vars['current_step'] = $active_step_model->name;
      $this->vars['booking'] = OsStepsHelper::$booking_object;
      $this->vars['restrictions'] = OsStepsHelper::$restrictions;
      $this->set_layout('none');

      $dataToSend = [];
      $dataToSend['active_step_model'] = $this->vars['active_step_model'];
      $dataToSend['steps_models'] = $this->steps_models;
      $dataToSend['current_step'] = $active_step_model->name;
      $dataToSend['booking'] = OsStepsHelper::$booking_object;
      $dataToSend['restrictions'] = OsStepsHelper::$restrictions;
      $dataToSend['show_next_btn'] = OsStepsHelper::can_step_show_next_btn($active_step_model->name);
      $dataToSend['show_prev_btn'] = OsStepsHelper::can_step_show_prev_btn($active_step_model->name);

      return $dataToSend;

    }  */

    
    //Added function getFirstStep (Author:Sandy)
    //*****************************************

    /* public function getFirstStep($restrictions = false, $output = true){
      OsStepsHelper::set_booking_object();
      if((!$restrictions || empty($restrictions)) && isset($this->params['restrictions'])) $restrictions = $this->params['restrictions'];
      OsStepsHelper::set_restrictions($restrictions);
      OsStepsHelper::get_step_names_in_order();
      OsStepsHelper::remove_already_selected_steps();

      $this->steps_models = OsStepsHelper::load_steps_as_models(OsStepsHelper::get_step_names_in_order());

      $active_step_model = $this->steps_models[0];

      // if is payment step - check if total is not $0 and if it is skip payment step
      if(OsStepsHelper::should_step_be_skipped($active_step_model->name)){
          $active_step_model = $this->steps_models[1];
      }

      $this->vars['show_next_btn'] = OsStepsHelper::can_step_show_next_btn($active_step_model->name);
      $this->vars['show_prev_btn'] = OsStepsHelper::can_step_show_prev_btn($active_step_model->name);
      $this->vars['steps_models'] = $this->steps_models;
      $this->vars['active_step_model'] = $active_step_model;

      $this->vars['current_step'] = $active_step_model->name;
      $this->vars['booking'] = OsStepsHelper::$booking_object;
      $this->vars['restrictions'] = OsStepsHelper::$restrictions;
      $this->set_layout('none');

      /* do_action('latepoint_load_step', $active_step_model->name, $this->vars['booking'], 'json'); 
      $dataToSend = apply_filters( 'latepoint_load_step', $dataToSend, $active_step_model->name, $this->vars['booking'], 'json' );
      return $dataToSend;
    }  */

   
    //revised function get_step (Author:Sandy)
    //****************************************
    
    /* public function get_step($step_name, $booking, $restrictions, $direction, $customer){

      $dataToSend = [];
     
      if(!OsStepsHelper::is_valid_step($step_name)) return false;

      $booking_object_params = (array) $booking;
      $booking_object = OsStepsHelper::set_booking_object($booking_object_params);
      $booking_object->customer = $customer;
      
      $restrictions = OsStepsHelper::set_restrictions($restrictions);

      OsStepsHelper::get_step_names_in_order();
      OsStepsHelper::remove_already_selected_steps();
      // Check if a valid step name
      $current_step = $step_name;
      if(!in_array($current_step, OsStepsHelper::get_step_names_in_order())) return false;
      $step_direction = isset($direction) ? $direction : 'next';
      switch ($step_direction) {
        case 'next':
          //do_action('latepoint_process_step', $current_step, OsStepsHelper::$booking_object);          $dataToSend = apply_filters( 'latepoint_load_step', $dataToSend, $current_step, OsStepsHelper::$booking_object, 'json' );
          $step_name_to_load = OsStepsHelper::get_next_step_name($current_step);
          return $dataToSend;
          break;
        case 'prev':
		      $step_name_to_load = OsStepsHelper::get_prev_step_name($current_step);
          break;
        case 'specific':
	        $step_name_to_load = OsStepsHelper::should_step_be_skipped($current_step) ? OsStepsHelper::get_next_step_name($current_step) : $current_step;
          break;
      }
      if($step_name_to_load){
       //do_action('latepoint_load_step', $step_name_to_load, OsStepsHelper::$booking_object);
        $dataToSend = apply_filters( 'latepoint_load_step', $dataToSend, $step_name_to_load, OsStepsHelper::$booking_object, 'json' );
          return $dataToSend;
      }
    } */

  }

endif;