<?php 

class OsBookingHelper {

  public static function get_default_payment_portion_type($booking){
    $regular_price = $booking->full_amount_to_charge(false);
    $deposit_price = $booking->deposit_amount_to_charge(false);
    if(($regular_price == 0) && ($deposit_price > 0)){
      return LATEPOINT_PAYMENT_PORTION_DEPOSIT;
    }else{
      return LATEPOINT_PAYMENT_PORTION_FULL;
    }
  }

  public static function get_payment_total_info_html($booking){
    $payment_portion = (OsBookingHelper::get_default_payment_portion_type($booking) == LATEPOINT_PAYMENT_PORTION_DEPOSIT) ? ' paying-deposit ' : '';
    $html = '<div class="payment-total-info '.$payment_portion.'">
              <div class="payment-total-price-w"><span>'.__('Total booking price: ', 'latepoint').'</span><span class="lp-price-value">'.$booking->formatted_full_price().'</span></div>
              <div class="payment-deposit-price-w"><span>'.__('Deposit Amount: ', 'latepoint').'</span><span class="lp-price-value">'.$booking->formatted_deposit_price().'</span></div>
            </div>';
    $html = apply_filters('latepoint_filter_payment_total_info', $html, $booking);
    return $html;
  }

  public static function process_actions_after_save($booking_id){
  }

  public static function get_quick_availability_days($start_date, $agent, $service, $location, $work_start_end = false, $number_of_days = 30, $duration){
    $html = '';
    $date_obj = new OsWpDateTime($start_date);

    // check if connection exxists between location, agent and service
    $is_connected = OsConnectorHelper::has_connection(['agent_id' => $agent->id, 'service_id' => $service->id, 'location_id' => $location->id]);

    for($i = 0; $i < $number_of_days; $i++){
      if($date_obj->format('j') == '1'){
        $html.= '<div class="ma-month-label">'.OsUtilHelper::get_month_name_by_number($date_obj->format('n')).'</div>';
      }
      $html.= '<div class="ma-day ma-day-number-'.$date_obj->format('N').'">';
        $html.= '<div class="ma-day-info">';
          $html.= '<span class="ma-day-number">'.$date_obj->format('j').'</span>';
          $html.= '<span class="ma-day-weekday">'.OsUtilHelper::get_weekday_name_by_number($date_obj->format('N'), true).'</span>';
        $html.= '</div>';
        ob_start();
        if($is_connected){
          OsAgentHelper::availability_timeline($agent, $service, $location, $date_obj->format('Y-m-d'), array('show_avatar' => false, 'book_on_click' => false, 'preset_work_start_end_time' => $work_start_end, 'custom_duration' => $duration));
        }else{
          OsAgentHelper::availability_timeline_off(__('Not Available', 'latepoint'));
        }
        $html.= ob_get_clean();
      $html.= '</div>';
      $date_obj->modify('+1 day');
    }
    return $html;
  }

  public static function count_pending_bookings($agent_id = false, $location_id = false){
    $bookings = new OsBookingModel();
    if($agent_id){
      $bookings->where(['agent_id' => $agent_id]);
    }
    if($location_id){
      $bookings->where(['location_id' => $location_id]);
    }
    return $bookings->where(['status IN' => [LATEPOINT_BOOKING_STATUS_PENDING, LATEPOINT_BOOKING_STATUS_PAYMENT_PENDING]])->count();
  }

  public static function generate_services_list($services = false){
    if($services && is_array($services) && !empty($services)){ ?>
      <div class="os-services os-animated-parent os-items">
        <?php foreach($services as $service){ ?>
          <div class="os-animated-child os-item <?php if($service->short_description) echo 'with-description'; ?> <?php if($service->get_extra_durations()) echo 'has-multiple-durations has-child-items'; ?>">
            <div class="os-service-selector os-item-i os-animated-self" href="#" data-service-id="<?php echo $service->id; ?>">
              <?php if($service->selection_image_id){ ?>
                <span class="os-item-img-w" style="background-image: url(<?php echo $service->selection_image_url; ?>);"></span>
              <?php } ?>
              <span class="os-item-name-w">
                <span class="os-item-name"><?php echo $service->name; ?></span>
                <?php if($service->short_description){ ?>
                  <span class="os-item-desc"><?php echo $service->short_description; ?></span>
                <?php } ?>
              </span>
              <?php if($service->price_min > 0){ ?>
                <span class="os-item-price-w">
                  <span class="os-item-price">
                    <?php echo $service->price_min_formatted; ?>
                  </span>
                  <?php if($service->price_min != $service->price_max){ ?>
                    <span class="os-item-price-label"><?php _e('Starts From', 'latepoint'); ?></span>
                  <?php } ?>
                </span>
              <?php } ?>
            </div>
            <?php if($service->get_extra_durations()){ ?>
              <div class="os-service-durations os-animated-parent os-items os-as-grid os-three-columns">
              <?php
              foreach($service->get_all_durations_arr() as $extra_duration){ ?>
                <div class="os-animated-child os-item with-floating-price">
                  <div class="os-animated-self os-item-i os-service-duration-selector" href="#" data-duration="<?php echo $extra_duration['duration']; ?>" 
                              data-charge-amount="<?php echo $extra_duration['charge_amount']; ?>" 
                              data-deposit-amount="<?php echo $extra_duration['deposit_amount']; ?>">
                    <div class="os-duration-value"><?php echo $extra_duration['duration']; ?></div>
                    <div class="os-duration-label"><?php _e('Minutes', 'latepoint'); ?></div>
                    <?php if($extra_duration['charge_amount']) echo '<div class="os-duration-price">'.OsMoneyHelper::format_price($extra_duration['charge_amount']).'</div>'; ?>
                  </div>
                </div>
                <?php
              } ?>
              </div>
              <?php
            } ?>
          </div>
        <?php } ?>
      </div>
    <?php } 
  }

  public static function generate_services_and_categories_list($parent_id = false, $show_selected_categories = false, $show_selected_services = false, $preselected_category = false){
    $service_categories = new OsServiceCategoryModel();
    $args = array();
    if($show_selected_categories && is_array($show_selected_categories)){
      if($parent_id){
        $service_categories->where(['parent_id' => $parent_id]);
      }else{
        if($preselected_category){
          $service_categories->where(['id' => $preselected_category]);
        }else{
          $service_categories->where_in('id', $show_selected_categories);
          $service_categories->where(['parent_id' => ['OR' => ['IS NULL', ' NOT IN' => $show_selected_categories] ]]);
        }
      }
    }else{
      if($preselected_category){
        $service_categories->where(['id' => $preselected_category]);
      }else{
        $args['parent_id'] = $parent_id ? $parent_id : 'IS NULL';
      }
    }
    $service_categories = $service_categories->where($args)->order_by('order_number asc')->get_results_as_models();
    if(!is_array($service_categories)) return;
    $main_parent_class = ($parent_id) ? 'os-animated-parent': 'os-service-categories-main-parent os-animated-parent';
    if(!$preselected_category) echo '<div class="os-service-categories-holder '.$main_parent_class.'">';
    foreach($service_categories as $service_category){ ?>
      <?php 
      $services = [];
      $category_services = $service_category->active_services;
      if(is_array($category_services)){
        // if show selected services restriction is set - filter
        if($show_selected_services){
          foreach($category_services as $category_service){
            if(in_array($category_service->id, $show_selected_services)) $services[] = $category_service;
          }
        }else{
          $services = $category_services;
        }  
      }
      $child_categories = new OsServiceCategoryModel();
      $count_child_categories = $child_categories->where(['parent_id' => $service_category->id])->count();
      // show only if it has either at least one child category or service
      if($count_child_categories || count($services)){ 
        // preselected category, just show contents, not the wrapper
        if($service_category->id == $preselected_category){
          OsBookingHelper::generate_services_list($services);
          OsBookingHelper::generate_services_and_categories_list($service_category->id, $show_selected_categories, $show_selected_services);
        }else{ ?>
          <div class="os-service-category-w os-items os-animated-child" data-id="<?php echo $service_category->id; ?>">
            <div class="os-service-category-info-w os-item os-animated-self with-plus">
              <div class="os-service-category-info os-item-i">
                <div class="os-item-img-w" style="background-image: url(<?php echo $service_category->selection_image_url; ?>);"></div>
                <div class="os-item-name-w">
                  <div class="os-item-name"><?php echo $service_category->name; ?></div>
                </div>
                <?php if(count($services)){ ?>
                  <div class="os-item-child-count"><span><?php echo count($services); ?></span> <?php _e('Services', 'latepoint'); ?></div>
                <?php } ?>
              </div>
            </div>
            <?php OsBookingHelper::generate_services_list($services); ?>
            <?php OsBookingHelper::generate_services_and_categories_list($service_category->id, $show_selected_categories, $show_selected_services); ?>
          </div><?php
        }
      }
    }
    if(!$preselected_category) echo '</div>';
  }

  public static function quick_booking_btn_html($booking_id = false, $params = array()){
    $html = '';
    if($booking_id){
      $params['id'] = $booking_id;
      $route = OsRouterHelper::build_route_name('bookings', 'quick_edit_form');
    }else{
      $route = OsRouterHelper::build_route_name('bookings', 'quick_new_form');
    }
    $params_str = http_build_query($params);
    $html = 'data-os-params="'.$params_str.'" 
    data-os-action="'.$route.'" 
    data-os-output-target="side-panel"
    data-os-after-call="latepoint_init_quick_booking_form"';
    return $html;
  }

  public static function get_services_count_by_type_for_date($date, $agent_id = false){
    $bookings = new OsBookingModel();
    $where_args = array('start_date' => $date);
    if($agent_id) $where_args['agent_id'] = $agent_id;
    return $bookings->select(LATEPOINT_TABLE_SERVICES.".name, count(".LATEPOINT_TABLE_BOOKINGS.".id) as count, bg_color")->join(LATEPOINT_TABLE_SERVICES, array(LATEPOINT_TABLE_SERVICES.".id" => 'service_id'))->where($where_args)->group_by('service_id')->get_results(ARRAY_A);
  }

  public static function get_any_agent_for_booking_by_rule($booking){
    // ANY AGENT SELECTED
    // get available agents 
    $connected_ids = OsAgentHelper::get_agents_for_service_and_location($booking->service_id, $booking->location_id);

    // If date/time is selected - filter agents who are available at that time
    if($booking->start_date && $booking->start_time){
      $available_agent_ids = [];
      foreach($connected_ids as $agent_id){
        if(OsAgentHelper::is_agent_available_on($agent_id, $booking->start_date, $booking->start_time, $booking->get_total_duration(), $booking->service_id, $booking->location_id)){
          $available_agent_ids[] = $agent_id;
        }
      }
      $connected_ids = (!empty($available_agent_ids) && !empty($connected_ids)) ? array_intersect($available_agent_ids, $connected_ids) : $connected_ids;
    }


    $agents_model = new OsAgentModel();
    if(!empty($connected_ids)) $agents_model->where_in('id', $connected_ids);
    $agents = $agents_model->should_be_active()->get_results_as_models();

    if(empty($agents)){
      return false;
    }


    $selected_agent_id = false;
    switch(OsSettingsHelper::get_any_agent_order()){
      case LATEPOINT_ANY_AGENT_ORDER_RANDOM:
        $selected_agent_id = $connected_ids[rand(0, count($connected_ids) - 1)];
      break;
      case LATEPOINT_ANY_AGENT_ORDER_PRICE_HIGH:
        $highest_price = false;
        foreach($agents as $agent){
          $booking->agent_id = $agent->id;
          $price = OsMoneyHelper::calculate_full_amount_to_charge($booking);
          if($highest_price === false && $selected_agent_id === false){
            $highest_price = $price;
            $selected_agent_id = $agent->id;
          }else{
            if($highest_price < $price){
              $highest_price = $price;
              $selected_agent_id = $agent->id;
            }
          }
        }
      break;
      case LATEPOINT_ANY_AGENT_ORDER_PRICE_LOW:
        $lowest_price = false;
        foreach($agents as $agent){
          $booking->agent_id = $agent->id;
          $price = OsMoneyHelper::calculate_full_amount_to_charge($booking);
          if($lowest_price === false && $selected_agent_id === false){
            $lowest_price = $price;
            $selected_agent_id = $agent->id;
          }else{
            if($lowest_price > $price){
              $lowest_price = $price;
              $selected_agent_id = $agent->id;
            }
          }
        }
      break;
      case LATEPOINT_ANY_AGENT_ORDER_BUSY_HIGH:
        $max_bookings = false;
        foreach($agents as $agent){
          $agent_total_bookings = OsBookingHelper::total_bookings_for_date($booking->start_date, ['agent_id' => $agent->id]);
          if($max_bookings === false && $selected_agent_id === false){
            $max_bookings = $agent_total_bookings;
            $selected_agent_id = $agent->id;
          }else{
            if($max_bookings < $agent_total_bookings){
              $max_bookings = $agent_total_bookings;
              $selected_agent_id = $agent->id;
            }
          }
        }
      break;
      case LATEPOINT_ANY_AGENT_ORDER_BUSY_LOW:
        $min_bookings = false;
        foreach($agents as $agent){
          $agent_total_bookings = OsBookingHelper::total_bookings_for_date($booking->start_date, ['agent_id' => $agent->id]);
          if($min_bookings === false && $selected_agent_id === false){
            $min_bookings = $agent_total_bookings;
            $selected_agent_id = $agent->id;
          }else{
            if($min_bookings > $agent_total_bookings){
              $min_bookings = $agent_total_bookings;
              $selected_agent_id = $agent->id;
            }
          }
        }
      break;
    }
    $booking->agent_id = $selected_agent_id;
    return $selected_agent_id;
  }

  public static function total_bookings_for_date($date, $conditions = []){
    $args = ['start_date' => $date];
    if(isset($conditions['agent_id']) && $conditions['agent_id']) $args['agent_id'] = $conditions['agent_id'];
    if(isset($conditions['service_id']) && $conditions['service_id']) $args['service_id'] = $conditions['service_id'];
    if(isset($conditions['location_id']) && $conditions['location_id']) $args['location_id'] = $conditions['location_id'];

    $bookings = new OsBookingModel();
    $bookings = $bookings->where($args);
    return $bookings->count();
  }

  public static function get_default_booking_status(){
    $default_status = OsSettingsHelper::get_settings_value('default_booking_status');
    if($default_status){
      return $default_status;
    }else{
      return LATEPOINT_BOOKING_STATUS_APPROVED;
    }
  }





  public static function is_timeframe_in_periods($timeframe_start, $timeframe_end, $periods_arr, $should_be_fully_inside = false){
    if(empty($periods_arr)) return false;
    if(!is_array($periods_arr)) $periods_arr = [$periods_arr];
    foreach($periods_arr as $period){
      $period_info = explode(':', $period);
      if(count($period_info) == 2){
        list($period_start, $period_end) = $period_info;
      }
      if(count($period_info) == 4){
        list($period_start, $period_end, $buffer_before, $buffer_after) = $period_info;
        $period_start = $period_start - $buffer_before;
        $period_end = $period_end + $buffer_after;
      }
      if($should_be_fully_inside){
        if(self::is_period_inside_another($timeframe_start, $timeframe_end, $period_start, $period_end)){
          return true;
        }
      }else{
        if(self::is_period_overlapping($timeframe_start, $timeframe_end, $period_start, $period_end)){
          return true;
        }
      }
    }
    return false;
  }

  public static function is_period_overlapping($period_one_start, $period_one_end, $period_two_start, $period_two_end){
    // https://stackoverflow.com/questions/325933/determine-whether-two-date-ranges-overlap/
    return (($period_one_start < $period_two_end) && ($period_two_start < $period_one_end));
  }

  public static function is_period_inside_another($period_one_start, $period_one_end, $period_two_start, $period_two_end){
    return (($period_one_start >= $period_two_start) && ($period_one_end <= $period_two_end));
  }



  public static function get_bookings_times_for_date($date, $agent_id, $location_id = false, $approved_only = true){
    if(!$location_id) $location_id = OsLocationHelper::get_selected_location_id();
    $bookings = new OsBookingModel();

    $bookings->select('start_time, end_time, buffer_before, buffer_after')->where([
      'start_date' => $date,
      'agent_id' => $agent_id]);

    if($location_id){
      $bookings->where(['location_id' => $location_id]);
    }

    if($approved_only){
      $bookings->should_be_approved();
    }

    $booked_periods = $bookings->get_results();

    $booked_periods_arr = array();

    foreach($booked_periods as $booked_period){
      $start_time = $booked_period->start_time;
      $end_time = $booked_period->end_time;
      $booked_periods_arr[] = $start_time. ':' .$end_time. ':' .$booked_period->buffer_before. ':' .$booked_period->buffer_after;
    }

    $booked_periods_arr = apply_filters('latepoint_filter_booked_periods', $booked_periods_arr, $date, $agent_id);

    return $booked_periods_arr;
  }


  // args = [agent_id, 'service_id', 'location_id']
  public static function get_bookings_for_date($date, $args = []){
    $bookings = new OsBookingModel();
    $args['start_date'] = $date;
    $bookings->should_not_be_cancelled()->where($args);
    return $bookings->get_results_as_models();
  }


  public static function generate_monthly_calendar($target_date_string = 'today', $settings = []){
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

    ?>

    <div class="os-current-month-label-w">
      
      <div class="os-current-month-label"><?php echo OsUtilHelper::get_month_name_by_number($target_date->format('n')); ?></div>
      <button type="button" class="os-month-prev-btn <?php if(!$settings['allow_full_access']) echo 'disabled'; ?>" data-route="<?php echo OsRouterHelper::build_route_name('calendars', 'load_monthly_calendar_days') ?>"><i class="latepoint-icon latepoint-icon-arrow-left"></i></button>
      <button type="button" class="os-month-next-btn" data-route="<?php echo OsRouterHelper::build_route_name('calendars', 'load_monthly_calendar_days') ?>"><i class="latepoint-icon latepoint-icon-arrow-right"></i></button>
    </div>
    <div class="os-weekdays">
    <?php foreach($weekdays as $weekday_number => $weekday_name){
      echo '<div class="weekday weekday-'.($weekday_number + 1).'">'.$weekday_name.'</div>';
    } ?>
    </div>
    <div class="os-months">
      <?php 
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

      OsBookingHelper::generate_monthly_calendar_days($target_date_string, $days_settings); 
      for($i = 1; $i <= $settings['number_of_months_to_preload']; $i++){
        $target_date->modify('first day of next month');
        $days_settings['active'] = false;
        $days_settings['highlight_target_date'] = false;
        OsBookingHelper::generate_monthly_calendar_days($target_date->format('Y-m-d'), $days_settings);
      }
      ?>
    </div><?php
  }


/*   public static function generate_monthly_calendar_front($target_date_string = 'today', $settings = []){
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
    echo '<div class="weekday weekday-'.($weekday_number + 1).'">'.$weekday_name.'</div>';
    $objDay = (object) array(
      'dayClass'=>'weekday weekday-'.($weekday_number + 1).'',
      'dayLabel'=> $weekday_name
    );
    array_push($hebdoDays, $objDay);
    } 
   
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
    $monthly_calendar_days_current = OsBookingHelper::generate_monthly_calendar_days_front($target_date_string, $days_settings, $dataToReturnMonth); 
    array_push($monthly_calendar_days, $monthly_calendar_days_current);
    for($i = 1; $i <= $settings['number_of_months_to_preload']; $i++){
      $target_date->modify('first day of next month');
      $days_settings['active'] = false;
      $days_settings['highlight_target_date'] = false;
      $monthly_calendar_days_others = OsBookingHelper::generate_monthly_calendar_days_front($target_date->format('Y-m-d'), $days_settings, $dataToReturnMonth);
      array_push($monthly_calendar_days, $monthly_calendar_days_others);

    return $monthly_calendar_days;
    }
} */






  public static function generate_monthly_calendar_days($target_date_string = 'today', $settings = []){
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
    echo '<div class="os-monthly-calendar-days-w '.$active_class.'" data-calendar-year="' . $target_date->format('Y') . '" data-calendar-month="' . $target_date->format('n') . '" data-calendar-month-label="' . OsUtilHelper::get_month_name_by_number($target_date->format('n')) . '"><div class="os-monthly-calendar-days">';

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
        ?>

        <div class="<?php echo $day_class; ?>" 
          data-date="<?php echo $day_date->format('Y-m-d'); ?>" 
          data-nice-date="<?php echo OsUtilHelper::get_month_name_by_number($day_date->format('n')).' '.$day_date->format('d'); ?>"
          data-service-duration="<?php echo $duration_minutes; ?>" 
          data-total-work-minutes="<?php echo $total_work_minutes; ?>" 
          data-work-start-time="<?php echo $work_start_minutes; ?>" 
          data-work-end-time="<?php echo $work_end_minutes ?>" 
          data-available-minutes="<?php echo implode(',', $available_minutes); ?>" 
          data-day-minutes="<?php echo implode(',', $day_minutes); ?>"
          data-interval="<?php echo $interval; ?>">
          <div class="os-day-box">
            <div class="os-day-number"><?php echo $day_date->format('j'); ?></div>
            <?php if(!$is_day_in_past && !$not_in_allowed_period){ ?>
              <div class="os-day-status">
                <?php 
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
                        echo '<div class="day-available" style="left:'.$left.'%;width:'.$width.'%;"></div>';
                      }
                      $not_available_started_count++;
                      $available_blocks_count = 0;
                      $available_started_on = false;
                    }
                    $prev_minute = $minute;
                    $processed_count++;
                  }
                  if($available_started_on !== false){
                    echo '<div class="day-available" style="left:'.($available_started_on / $total_day_minutes_count * 100).'%;width:'.($available_blocks_count / $total_day_minutes_count * 100).'%;"></div>';
                  }
                }
                ?>
              </div>
            <?php } ?>
          </div>
        </div>

        <?php

        // DAYS LOOP END
      }
    echo '</div></div>';
  }

  //revised function generate_monthly_calendar_days_front (Author:Sandy)

  public static function generate_monthly_calendar_days_front_previous($target_date_string = 'today', $settings = [], $dataMonth){

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

      // DAYS LOOP END
      }
    
  }



  // Used on holiday/custom schedule generator lightbox
  public static function generate_monthly_calendar_days_only($target_date_string = 'today', $highlight_target_date = false){
    $target_date = new OsWpDateTime($target_date_string);
    $calendar_start = clone $target_date;
    $calendar_start->modify('first day of this month');
    $calendar_end = clone $target_date;
    $calendar_end->modify('last day of this month');

    $weekday_for_first_day_of_month = $calendar_start->format('N') - 1;
    $weekday_for_last_day_of_month = $calendar_end->format('N') - 1;


    if($weekday_for_first_day_of_month > 0){
      $calendar_start->modify('-'.$weekday_for_first_day_of_month.' days');
    }

    if($weekday_for_last_day_of_month < 7){
      $days_to_add = 7 - $weekday_for_last_day_of_month;
      $calendar_end->modify('+'.$days_to_add.' days');
    }

    echo '<div class="os-monthly-calendar-days-w" data-calendar-year="' . $target_date->format('Y') . '" data-calendar-month="' . $target_date->format('n') . '" data-calendar-month-label="' . OsUtilHelper::get_month_name_by_number($target_date->format('n')) . '">
            <div class="os-monthly-calendar-days">';
              for($day_date=clone $calendar_start; $day_date<$calendar_end; $day_date->modify('+1 day')){
                $is_today = ($day_date->format('Y-m-d') == OsTimeHelper::today_date()) ? true : false;
                $is_day_in_past = ($day_date->format('Y-m-d') < OsTimeHelper::today_date()) ? true : false;
                $day_class = 'os-day os-day-current week-day-'.strtolower($day_date->format('N'));

                if($day_date->format('m') > $target_date->format('m')) $day_class.= ' os-month-next';
                if($day_date->format('m') < $target_date->format('m')) $day_class.= ' os-month-prev';

                if($is_today) $day_class.= ' os-today';
                if($highlight_target_date && ($day_date->format('Y-m-d') == $target_date->format('Y-m-d'))) $day_class.= ' selected';
                if($is_day_in_past) $day_class.= ' os-day-passed'; ?>
                <div class="<?php echo $day_class; ?>" data-date="<?php echo $day_date->format('Y-m-d'); ?>">
                  <div class="os-day-box">
                    <div class="os-day-number"><?php echo $day_date->format('j'); ?></div>
                  </div>
                </div><?php
              }
    echo '</div></div>';
  }

  public static function get_nice_status_name($status){
    $statuses_list = OsBookingHelper::get_statuses_list();
    if($status && isset($statuses_list[$status])){
      return $statuses_list[$status];
    }else{
      return __('Undefined Status', 'latepoint');
    }
  }

  public static function get_statuses_list(){
    return array( LATEPOINT_BOOKING_STATUS_APPROVED => __('Approved', 'latepoint'), 
                  /* LATEPOINT_BOOKING_STATUS_PENDING => __('Pending Approval', 'latepoint'), 
                  LATEPOINT_BOOKING_STATUS_PAYMENT_PENDING => __('Payment Pending', 'latepoint'),  */
                  LATEPOINT_BOOKING_STATUS_CANCELLED => __('Cancelled', 'latepoint'));
  }

  public static function get_payment_methods_list(){
    $payment_methods = [
      LATEPOINT_PAYMENT_METHOD_LOCAL => __('Local Payment', 'latepoint'),
      LATEPOINT_PAYMENT_METHOD_PAYPAL => __('PayPal', 'latepoint'),
      LATEPOINT_PAYMENT_METHOD_CARD => __('Credit/Debit Card', 'latepoint')
    ];
    return $payment_methods;
  }

  public static function get_payment_portions_list(){
    $payment_portions = [ LATEPOINT_PAYMENT_PORTION_FULL => __('Full Amount', 'latepoint'), LATEPOINT_PAYMENT_PORTION_DEPOSIT => __('Deposit', 'latepoint')];
    return $payment_portions;
  }



  public static function get_weekdays_arr($full_name = false) {
    if($full_name){
      $weekdays = array(__('Monday', 'latepoint'), 
                        __('Tuesday', 'latepoint'), 
                        __('Wednesday', 'latepoint'), 
                        __('Thursday', 'latepoint'), 
                        __('Friday', 'latepoint'), 
                        __('Saturday', 'latepoint'), 
                        __('Sunday', 'latepoint'));
    }else{
      $weekdays = array(__('Mon', 'latepoint'), 
                        __('Tue', 'latepoint'), 
                        __('Wed', 'latepoint'), 
                        __('Thu', 'latepoint'), 
                        __('Fri', 'latepoint'), 
                        __('Sat', 'latepoint'), 
                        __('Sun', 'latepoint'));
    }
    return $weekdays;
  }

  public static function get_weekday_name_by_number($weekday_number, $full_name = false) {
    $weekdays = OsBookingHelper::get_weekdays_arr($full_name);
    if(!isset($weekday_number) || $weekday_number < 1 || $weekday_number > 7) return '';
    else return $weekdays[$weekday_number - 1];
  }



  public static function get_bookings_per_day_for_period($date_from, $date_to, $service_id = false, $agent_id = false, $location_id = false){
    $bookings = new OsBookingModel();
    $query_args = array($date_from, $date_to);
    $query = 'SELECT count(id) as bookings_per_day, start_date FROM '.$bookings->table_name.' WHERE start_date >= %s AND start_date <= %s';
    if($service_id){
      $query.= ' AND service_id = %d';
      $query_args[] = $service_id;
    }
    if($agent_id){
      $query.= ' AND agent_id = %d';
      $query_args[] = $agent_id;
    }
    if($location_id){
      $query.= ' AND location_id = %d';
      $query_args[] = $location_id;
    }
    $query.= ' GROUP BY start_date';
    return $bookings->get_query_results($query, $query_args);
  }


  public static function get_bookings_times_for_date_range($date_from = false, $date_to = false, $args = []){
    if(!$date_from || !$date_to) return false;
    $defaults = [
      'agent_id' => false,
      'location_id' => false,
      'service_id' => false,
      'timeshift_minutes' => 0,
      'approved_only' => true];
    $args = OsUtilHelper::merge_default_atts($defaults, $args);

    // agent id is required
    if(empty($args['agent_id'])) return false;

    $date_from_obj = new DateTime( $date_from );
    $date_to_obj   = new DateTime( $date_to );

    if($args['timeshift_minutes'] > 0){
      $date_from_obj->modify('-1 day');
    }elseif($args['timeshift_minutes'] < 0){
      $date_to_obj->modify('+1 day');
    }

    if(!$args['location_id']) $args['location_id'] = OsLocationHelper::get_selected_location_id();

    $bookings = new OsBookingModel();

    $bookings->select('start_date, agent_id, start_time, end_time, buffer_before, buffer_after')->where(['start_date >=' => $date_from_obj->format('Y-m-d'),'start_date <=' => $date_to_obj->format('Y-m-d')]);
    $bookings->where(['agent_id' => $args['agent_id']]);
    if(!empty($args['location_id'])) $bookings->where(['location_id' => $args['location_id']]);
    if($args['approved_only']) $bookings->should_be_approved();

    $booked_periods = $bookings->get_results();

    $booked_periods_arr = array();

    if(!is_array($args['agent_id'])) $args['agent_id'] = [$args['agent_id']];
    // fill days
    foreach($args['agent_id'] as $agent_id){
      for($day = clone $date_from_obj; $day <= $date_to_obj; $day->modify('+1 day')){
        $booked_periods_arr['agent_'.$agent_id][$day->format('Y-m-d')] = [];
      }
    }


    foreach($booked_periods as $booked_period){
      $start_time = $booked_period->start_time;
      $end_time = $booked_period->end_time;
      $booked_periods_arr['agent_'.$booked_period->agent_id][$booked_period->start_date][] = $start_time. ':' .$end_time. ':' .$booked_period->buffer_before. ':' .$booked_period->buffer_after;
    }

    $booked_periods_arr = apply_filters('latepoint_filter_booked_periods_for_range', $booked_periods_arr, $date_from_obj->format('Y-m-d'), $date_to_obj->format('Y-m-d'), $args);

    if($args['timeshift_minutes']){
      foreach($args['agent_id'] as $agent_id){
        $booked_periods_arr['agent_'.$agent_id] = self::apply_timeshift($booked_periods_arr['agent_'.$agent_id], $args['timeshift_minutes']);
      }
    }
    return $booked_periods_arr;
  }

  public static function get_work_periods_for_date_range($date_from = false, $date_to = false, $args = []){
    if(!$date_from || !$date_to) return false;

    $timeshift_minutes = (isset($args['timeshift_minutes']) && !empty($args['timeshift_minutes'])) ? $args['timeshift_minutes'] : 0;


    $query_args = array();
    $query_args['service_id'] = 0;
    $query_args['location_id'] = 0;
    $query_args['agent_id'] = 0;

    if(isset($args['service_id']) && !empty($args['service_id'])) $query_args['service_id'] = (is_array($args['service_id'])) ? array_merge([0], $args['service_id']) : [0, $args['service_id']];
    if(isset($args['location_id']) && !empty($args['location_id'])) $query_args['location_id'] = (is_array($args['location_id'])) ? array_merge([0], $args['location_id']) : [0, $args['location_id']];
    if(isset($args['agent_id']) && !empty($args['agent_id'])) $query_args['agent_id'] = (is_array($args['agent_id'])) ? array_merge([0], $args['agent_id']) : [0, $args['agent_id']];



    $date_from_obj = new DateTime( $date_from );
    $date_to_obj   = new DateTime( $date_to );

    if($timeshift_minutes > 0){
      $date_from_obj->modify('-1 day');
    }elseif($timeshift_minutes < 0){
      $date_to_obj->modify('+1 day');
    }


    if($date_from_obj->format('Y-m-d') == $date_to_obj->format('Y-m-d')){
      $query_args['OR'][] = ['custom_date' => $date_from_obj->format('Y-m-d')];
      $query_args['week_day'] = $date_from_obj->format('N');
    }else{
      $query_args['OR']['AND'] = ['custom_date >=' => $date_from_obj->format('Y-m-d'), 'custom_date <=' => $date_to_obj->format('Y-m-d')];
    }

    $query_args['OR'][] = ['custom_date' => 'IS NULL'];

    $work_periods_model = new OsWorkPeriodModel();
    $work_periods = $work_periods_model->where($query_args)->order_by('week_day ASC, custom_date DESC, agent_id DESC, service_id DESC, location_id DESC, start_time asc')->get_results();


    $periods = [];
    foreach($work_periods as $work_period){
      $periods[$work_period->week_day][] = ['agent_id' => $work_period->agent_id, 
                                            'service_id' => $work_period->service_id, 
                                            'location_id' => $work_period->location_id, 
                                            'custom_date' => $work_period->custom_date,
                                            'start_time' => $work_period->start_time,
                                            'end_time' => $work_period->end_time ];
    }

    $daily_work_periods = [];
    for($day = clone $date_from_obj; $day <= $date_to_obj; $day->modify('+1 day')){
      $last_added_period = false;
      $daily_work_periods[$day->format('Y-m-d')] = [];
      foreach($periods[$day->format('N')] as $period){
        if(!empty($period['custom_date']) && ($period['custom_date'] != $day->format('Y-m-d'))) continue;
        if($last_added_period && (
                                  $last_added_period['custom_date'] != $period['custom_date'] ||
                                  $last_added_period['service_id'] != $period['service_id'] ||
                                  $last_added_period['agent_id'] != $period['agent_id'] ||
                                  $last_added_period['location_id'] != $period['location_id'])) break;
        $daily_work_periods[$day->format('Y-m-d')][] = $period['start_time'].':'.$period['end_time'];
        $last_added_period = $period;
      }
    }

    if(!empty($timeshift_minutes)){
      $daily_work_periods = self::apply_timeshift($daily_work_periods, $timeshift_minutes);
    }

    return $daily_work_periods;
  }

  public static function apply_timeshift($daily_periods, $timeshift_minutes){
    if(empty($timeshift_minutes)) return $daily_periods;
    $shifted_periods = [];
    // apply timeshift
    foreach($daily_periods as $day => $periods){
      if($timeshift_minutes > 0){
        $day_obj = OsWpDateTime::os_createFromFormat('Y-m-d', $day);
        $day_obj->modify('-1 day');
        if(isset($daily_periods[$day_obj->format('Y-m-d')])){
          foreach($daily_periods[$day_obj->format('Y-m-d')] as $prev_day_period){
            list($period_start, $period_end) = explode(':', $prev_day_period);
            $period_start = $period_start + $timeshift_minutes;
            $period_end = $period_end + $timeshift_minutes;
            if($period_end <= (24 * 60)) continue;
            if($period_start < 24 * 60) $period_start = 24 * 60;
            $period_start = $period_start - 24 * 60;
            $period_end = $period_end - 24 * 60;
            $shifted_periods[$day][] = $period_start.':'.$period_end;
          }
        }
      }
      if(!empty($periods)){
        foreach($periods as $period){
          list($period_start, $period_end) = explode(':', $period);
          $period_start = $period_start + $timeshift_minutes;
          $period_end = $period_end + $timeshift_minutes;
          // if starts next day or ended previous day - skip
          if($period_start >= (24 * 60) || $period_end <= 0) continue;
          if($period_end > (24 * 60)) $period_end = 24 * 60;
          if($period_start < 0) $period_start = 0;
          $shifted_periods[$day][] = $period_start.':'.$period_end;
        }
      }else{
        $shifted_periods[$day] = [];
      }
      if($timeshift_minutes < 0){
        $day_obj = OsWpDateTime::os_createFromFormat('Y-m-d', $day);
        $day_obj->modify('+1 day');
        if(isset($daily_periods[$day_obj->format('Y-m-d')])){
          foreach($daily_periods[$day_obj->format('Y-m-d')] as $next_day_period){
            list($period_start, $period_end) = explode(':', $next_day_period);
            $period_start = $period_start + $timeshift_minutes;
            $period_end = $period_end + $timeshift_minutes;
            if($period_start >= (24 * 60)) continue;
            if($period_end > 24 * 60) $period_end = 24 * 60;
            $shifted_periods[$day][] = $period_start.':'.$period_end;
          }
        }
      }
    }
    return $shifted_periods;
  }

  public static function get_work_periods($args = array()){
    $work_periods = OsWorkPeriodsHelper::load_work_periods($args);
    $work_periods_formatted_arr = array();
    if($work_periods){
      foreach($work_periods as $work_period){
        $work_periods_formatted_arr[] = $work_period->start_time. ':' .$work_period->end_time;
      }
    }
    return $work_periods_formatted_arr;
  }

  public static function get_min_max_work_periods($specific_weekdays = false, $service_id = false, $agent_id = false){
    $select_string = 'MIN(start_time) as start_time, MAX(end_time) as end_time';
    $work_periods = new OsWorkPeriodModel();
    $work_periods = $work_periods->select($select_string);
    $query_args = array('service_id' => 0, 'agent_id' => 0);
    if($service_id) $query_args['service_id'] = $service_id;
    if($agent_id) $query_args['agent_id'] = $agent_id;
    if($specific_weekdays && !empty($specific_weekdays)) $query_args['week_day'] = $specific_weekdays;
    $results = $work_periods->set_limit(1)->where($query_args)->get_results(ARRAY_A);
    if(($service_id || $agent_id) && empty($results['min_start_time'])){
      if($service_id && empty($results['min_start_time'])){
        $query_args['service_id'] = 0;
        $work_periods = new OsWorkPeriodModel();
        $work_periods = $work_periods->select($select_string);
        $results = $work_periods->set_limit(1)->where($query_args)->get_results(ARRAY_A);
      }
      if($agent_id && empty($results['min_start_time'])){
        $query_args['agent_id'] = 0;
        $work_periods = new OsWorkPeriodModel();
        $work_periods = $work_periods->select($select_string);
        $results = $work_periods->set_limit(1)->where($query_args)->get_results(ARRAY_A); 
      }
    }
    if($results){
      return array($results['start_time'], $results['end_time']);
    }else{
      return false;
    }
  }




  public static function get_work_start_end_time_for_multiple_dates($dates = false, $service_id = false, $agent_id = false){
    $specific_weekdays = array();
    if($dates){
      foreach($dates as $date){
        $target_date = new OsWpDateTime($date);
        $weekday = $target_date->format('N');
        if(!in_array($weekday, $specific_weekdays)) $specific_weekdays[] = $weekday;
      }
    }
    $work_minmax_start_end = self::get_min_max_work_periods($specific_weekdays, $service_id, $agent_id);
    return $work_minmax_start_end;
  }

  public static function get_work_start_end_time_for_date_multi_agent($agent_ids = array(), $args = array()){
    $work_start_times = [];
    $work_end_times = [];
    foreach($agent_ids as $agent_id){
      $args['agent_id'] = $agent_id;
      $work_times = self::get_work_start_end_time_for_date($args);
      if($work_times[0] == 0 && $work_times[1] == 0){
        // day off, do not count
      }else{
        $work_start_times[] = $work_times[0];
        $work_end_times[] = $work_times[1];
      }
    }
    if(empty($work_start_times)) $work_start_times = [0];
    if(empty($work_end_times)) $work_end_times = [0];
    return array(min($work_start_times), max($work_end_times));
  }

  public static function get_work_start_end_time_for_date($args = array()){
    $work_periods_arr = self::get_work_periods($args);
    return self::get_work_start_end_time($work_periods_arr);
  }

  public static function is_minute_in_work_periods($minute, $work_periods_arr){
    // print_r($work_periods_arr);
    if(empty($work_periods_arr)) return false;
    foreach($work_periods_arr as $work_period){
      list($period_start, $period_end) = explode(':', $work_period);
      if($period_start <= $minute && $period_end >= $minute){
        return true;
      }
    }
    return false;
  }

  public static function get_work_start_end_time($work_periods_arr){
    $work_start_minutes = 0;
    $work_end_minutes = 0;
    foreach($work_periods_arr as $work_period){
      list($period_start, $period_end) = explode(':', $work_period);
      if($period_start == $period_end) continue;
      $work_start_minutes = ($work_start_minutes > 0) ? min($period_start, $work_start_minutes) : $period_start;
      $work_end_minutes = ($work_end_minutes > 0) ? max($period_end, $work_end_minutes) : $period_end;
    }
    return array($work_start_minutes, $work_end_minutes);
  }

  public static function get_work_start_end_time_for_date_range($dated_work_periods_arr){
    $work_periods_arr = [];
    foreach($dated_work_periods_arr as $date => $work_periods_for_date_arr){
      $work_periods_arr = array_merge($work_periods_arr, $work_periods_for_date_arr);
    }
    $work_periods_arr = array_unique($work_periods_arr);
    return self::get_work_start_end_time($work_periods_arr);
  }




}
