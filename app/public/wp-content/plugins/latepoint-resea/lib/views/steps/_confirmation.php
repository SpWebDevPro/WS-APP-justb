<div class="step-confirmation-w latepoint-step-content">
  <h3 class="confirmation-header"><?php _e('Thank you for your reservation.', 'latepoint'); ?></h3>
  <div class="confirmation-number"><?php _e('Confirmation Number:', 'latepoint'); ?> <strong><?php echo $booking->id; ?></strong></div>
  <a href="<?php echo $booking->ical_download_link; ?>" class="ical-download-btn" target="_blank"><i class="latepoint-icon latepoint-icon-calendar"></i><span><?php _e('Add to my Calendar', 'latepoint'); ?></span></a>
  <div class="confirmation-info-w">
  	<div class="confirmation-app-info">
		  <h5><?php _e('Appointment Info', 'latepoint'); ?></h5>
		  <ul>
		  	<li><?php _e('Date:', 'latepoint'); ?> <strong><?php echo $booking->format_start_date_and_time(get_option('date_format'), false, OsTimeHelper::get_timezone_from_session()); ?></strong></li>
		  	<li>
          <?php _e('Time:', 'latepoint'); ?> 
          <strong>
            <?php echo OsTimeHelper::minutes_to_hours_and_minutes($booking->get_start_time_shifted_for_customer()); ?>
            <?php if(OsSettingsHelper::get_settings_value('show_booking_end_time') == 'on') echo ' - '. OsTimeHelper::minutes_to_hours_and_minutes($booking->get_end_time_shifted_for_customer()); ?>
          </strong>
        </li>
        <?php if(!empty($booking->location->full_address)){ ?>
          <li><?php _e('Location:', 'latepoint'); ?> <strong><?php echo $booking->location->full_address; ?></strong></li>
        <?php } ?>
        <?php if(!OsSettingsHelper::is_on('steps_hide_agent_info')){ ?>
  		  	<li><?php _e('Agent:', 'latepoint'); ?> <strong><?php echo $booking->agent->full_name; ?></strong></li>
        <?php } ?>
		  	<li><?php _e('Service:', 'latepoint'); ?> <strong><?php echo $booking->service->name; ?></strong></li>
        <?php do_action('latepoint_step_verify_appointment_info', $booking); ?>
		  </ul>
  	</div>
  	<div class="confirmation-customer-info">
		  <h5><?php _e('Customer Info', 'latepoint'); ?></h5>
		  <ul>
		  	<li><?php _e('Name:', 'latepoint'); ?> <strong><?php echo $customer->full_name; ?></strong></li>
		  	<li><?php _e('Phone:', 'latepoint'); ?> <strong><?php echo $customer->formatted_phone; ?></strong></li>
		  	<li><?php _e('Email:', 'latepoint'); ?> <strong><?php echo $customer->email; ?></strong></li>
        <?php if($custom_fields_for_customer){
          foreach($custom_fields_for_customer as $custom_field){
            echo '<li>'.$custom_field['label'].': <strong>'.$customer->get_meta_by_key($custom_field['id'], __('n/a', 'latepoint')).'</strong></li>';
          }
        } ?>
		  </ul>
  	</div>
  </div>
  <?php if(OsSettingsHelper::is_accepting_payments()){
    $amount_paid = $booking->get_total_amount_paid_from_transactions();
    if(($amount_paid > 0) || ($booking->price > 0)){ ?>
      <div class="payment-summary-info">
        <h5><?php _e('Payment Info', 'latepoint'); ?></h5>
        <div class="confirmation-info-w">
          <div class="confirmation-app-info">
            <ul>
              <li><?php _e('Payment Method:', 'latepoint'); ?> <strong><?php echo $booking->payment_method_nice_name; ?></strong></li>
              <?php if($amount_paid < $booking->price){ ?>
                <li><?php _e('Total Amount Due:', 'latepoint'); ?> <strong><?php echo OsMoneyHelper::format_price($booking->price); ?></strong></li>
              <?php } ?>
              <?php if($amount_paid > 0){ ?>
                <li><?php _e('Amount Paid Now:', 'latepoint'); ?> <strong><?php echo OsMoneyHelper::format_price($amount_paid); ?></strong></li>
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>
    <?php } ?>
  <?php } ?>
  <?php if($customer->is_guest && (OsSettingsHelper::get_settings_value('steps_hide_registration_prompt') != 'on')){ ?>
    <div class="step-confirmation-set-password">
      <h5><?php _e('Create Your Account', 'latepoint'); ?></h5>
      <div class="set-password-fields">
        <?php echo OsFormHelper::password_field('customer[password]', __('Enter Password', 'latepoint')); ?>
        <?php echo OsFormHelper::password_field('customer[password_confirmation]', __('Confirm Password', 'latepoint')); ?>
        <a href="#" class="latepoint-btn latepoint-btn-primary set-customer-password-btn" data-btn-action="<?php echo OsRouterHelper::build_route_name('customers', 'set_account_password_on_booking_completion'); ?>"><?php _e('Save', 'latepoint'); ?></a>
      </div>
      <?php echo OsFormHelper::hidden_field('account_nonse', $customer->account_nonse); ?>
    </div>
    <div class="info-box text-center">
    	<?php _e('Did you know that you can create an account to manage your reservations and schedule new appointments?', 'latepoint'); ?>
    	<div class="info-box-buttons">
    		<a href="#" class="show-set-password-fields"><?php _e('Create Account', 'latepoint'); ?></a>
    	</div>
    </div>
  <?php } ?>
</div>