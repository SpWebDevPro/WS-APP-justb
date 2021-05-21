<div class="latepoint-w">
	<a href="<?php echo OsRouterHelper::build_admin_post_link(['customers', 'logout'] ); ?>"><?php _e('Logout', 'latepoint'); ?></a>
	<h4><?php printf( __('Welcome %s', 'latepoint'), $customer->full_name); ?></h4>
	<div class="latepoint-tabs-w">
		<div class="latepoint-tab-triggers customer-dashboard-tabs">
			<a href="#" data-tab-target=".tab-content-customer-bookings" class="active latepoint-tab-trigger"><?php _e('My Appointments', 'latepoint'); ?></a>
			<a href="#" data-tab-target=".tab-content-customer-info-form" class="latepoint-tab-trigger"><?php _e('My Contact Information', 'latepoint'); ?></a>
			<a href="#" data-tab-target=".tab-content-customer-new-appointment-form" class="latepoint-tab-trigger"><?php _e('New Appointment', 'latepoint'); ?></a>
			<?php do_action('latepoint_customer_dashboard_after_tabs', $customer); ?>
		</div>
		<div class="latepoint-tab-content tab-content-customer-bookings active">
	    <?php if(OsSettingsHelper::is_on('steps_show_timezone_selector')){
	      echo '<div class="latepoint-customer-timezone-selector-w" data-route-name="'.OsRouterHelper::build_route_name('bookings', 'change_timezone').'">';
	        echo OsFormHelper::select_field('latepoint_timezone_selector', __('My Timezone:', 'latepoint'), OsTimeHelper::timezones_options_list($customer->get_selected_timezone_name()), $customer->get_selected_timezone_name());
	      echo '</div>';
	    } ?>
			<?php if($customer->future_bookings || $customer->past_bookings){ ?>
				<?php if($customer->future_bookings){ ?>
				<div class="latepoint-section-heading-w">
					<h5 class="latepoint-section-heading"><?php _e('Upcoming Appointments', 'latepoint'); ?></h5>
					<div class="heading-extra"><?php printf( __('%d Appointments'), count($customer->future_bookings)); ?></div>
				</div>
				<div class="customer-bookings-tiles">
					<?php 
					foreach($customer->future_bookings as $booking){
						$editable_booking = true;
						include('_booking_tile.php');
					} ?>
				</div>
				<?php } ?>
				<?php
				if($customer->past_bookings){ ?>
				<div class="latepoint-section-heading-w">
					<h5 class="latepoint-section-heading"><?php _e('Past Appointments', 'latepoint'); ?></h5>
					<div class="heading-extra"><?php printf( __('%d Appointments'), count($customer->past_bookings)); ?></div>
				</div>
				<div class="customer-bookings-tiles">
					<?php 
						foreach($customer->past_bookings as $booking){
							$editable_booking = false;
							include('_booking_tile.php');
					} ?>
				</div>
				<?php } ?>
			<?php }else{ 
				echo '<div class="latepoint-message-info latepoint-message">'.__('No appointments found', 'latepoint').'</div>';
			}
			?>
		</div>
		<div class="latepoint-tab-content tab-content-customer-info-form">
			<form action="">
			  <div class="os-row">
			    <?php echo OsFormHelper::text_field('customer[first_name]', __('Your First Name', 'latepoint'), $customer->first_name, array('class' => 'required'), array('class' => 'os-col-6')); ?>
			    <?php echo OsFormHelper::text_field('customer[last_name]', __('Your Last Name', 'latepoint'), $customer->last_name, array('class' => 'required'), array('class' => 'os-col-6')); ?>
			    <?php echo OsFormHelper::text_field('customer[phone]', __('Your Phone Number', 'latepoint'), $customer->phone, array('class' => 'os-mask-phone'), array('class' => 'os-col-6')); ?>
			    <?php echo OsFormHelper::text_field('customer[email]', __('Your Email Address', 'latepoint'), $customer->email, array('class' => 'required'), array('class' => 'os-col-6')); ?>
				</div>
				<?php echo OsFormHelper::button('submit', __('Save Changes', 'latepoint'), 'submit', ['class' => 'latepoint-btn']); ?>
			</form>
		</div>
		<div class="latepoint-tab-content tab-content-customer-new-appointment-form">
			<?php echo do_shortcode('[latepoint_book_form]'); ?>
		</div>
		<?php do_action('latepoint_customer_dashboard_after_tab_contents', $customer); ?>
	</div>
</div>