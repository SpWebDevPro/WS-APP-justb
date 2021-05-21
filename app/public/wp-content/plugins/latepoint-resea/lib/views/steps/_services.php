<div class="step-services-w latepoint-step-content">
  <div class="latepoint-step-content-text-centered">
    <h4><?php _e('Select Service Duration', 'latepoint'); ?></h4>
    <div><?php _e('You need to select service duration, the price of your service will depend on duration.', 'latepoint'); ?></div>
  </div>
  <?php 
  if(OsSettingsHelper::steps_show_service_categories()){
    OsBookingHelper::generate_services_and_categories_list(false, $show_service_categories_arr, $show_services_arr, $preselected_category);
  }else{
    OsBookingHelper::generate_services_list($services);
  } ?>
</div>