<?php 

class OsAuthHelper {

  public static function get_highest_current_user_id(){
    $user_id = false;
    switch(self::get_highest_current_user_type()){
      case 'admin':
        $user_id = get_current_user_id();
      break;
      case 'agent':
        $user_id = self::get_logged_in_agent_id();
      break;
      case 'customer':
        $user_id = self::get_logged_in_customer_id();
      break;
    }
    return $user_id;
  }


  public static function get_admin_or_agent_avatar_url(){
    $avatar_url = LATEPOINT_DEFAULT_AVATAR_URL;
    if(self::is_agent_logged_in()){
      $agent = self::get_logged_in_agent();
      $avatar_url = $agent->get_avatar_url();
    }elseif(self::get_logged_in_wp_user_id()){
      $wp_user = self::get_logged_in_wp_user();
      $avatar_url = get_avatar_url($wp_user->user_email);
    }
    return $avatar_url;
  }

  public static function get_highest_current_user_type(){
    // check if WP admin is logged in
    if(current_user_can('edit_posts')){
      $user_type = 'admin';
    }elseif(self::is_agent_logged_in()){
      $user_type = 'agent';
    }elseif(self::is_customer_logged_in()){
      $user_type = 'customer';
    }
    return $user_type;
  }

  public static function login_customer($email, $password){
    if(empty($email) || empty($password)) return false;
    $customer = new OsCustomerModel();
    $customer = $customer->where(array('email' => $email))->set_limit(1)->get_results_as_models();
    if($customer && OsAuthHelper::verify_password($password, $customer->password)){
      OsAuthHelper::authorize_customer($customer->id);
      return $customer;
    }else{
      return false;
    }
  }
  

  // CUSTOMERS 
  // ---------------

  public static function logout_customer(){
    unset($_SESSION['customer_id']);
  }

  public static function authorize_customer($customer_id){
    $_SESSION['customer_id'] = $customer_id;
  }


  /* public static function get_logged_in_customer_id(){
    return isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : false;
  } */


 //revised function get_logged_in_customer_id (Author:Sandy)
  public static function get_logged_in_customer_id(){
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
  }


  /* public static function is_customer_logged_in(){
    return isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : false;
  } */

 
//revised function is_customer_logged_in (Author:Sandy)
  public static function is_customer_logged_in(){
    //return now the id of the wp_user from token
    $currentuserid_fromjwt = get_current_user_id();
    if ($currentuserid_fromjwt ){
      return $currentuserid_fromjwt;
    } else {
      return false;
    }
  }

  public static function get_logged_in_customer(){
    $customer = false;
    if(self::is_customer_logged_in()){
      $customer = new OsCustomerModel(self::get_logged_in_customer_id());
    }
    return $customer;
  }


  // AGENTS
  // -------------

  public static function logout_agent(){
    unset($_SESSION['agent_id']);
  }

  public static function authorize_agent($agent_id){
    $_SESSION['agent_id'] = $agent_id;
  }

  public static function get_logged_in_agent_id(){
    $agent_id = false;
    if(self::is_agent_logged_in()){
      $agent = new OsAgentModel();
      $agent = $agent->select('id')->where(['wp_user_id' => self::get_logged_in_wp_user_id()])->set_limit(1)->get_results();
      if($agent && isset($agent->id)) $agent_id = $agent->id;
    }
    return $agent_id;
  }

  public static function is_agent_logged_in(){
    return current_user_can('edit_bookings');
  }

  public static function get_logged_in_agent(){
    $agent = false;
    if(self::is_agent_logged_in()){
      $agent = new OsAgentModel();
      $agent = $agent->where(['wp_user_id' => self::get_logged_in_wp_user_id()])->set_limit(1)->get_results_as_models();
    }
    return $agent;
  }








  // ADMIN USER
  public static function is_admin_logged_in(){
    return current_user_can('manage_options');
  }

  public static function get_logged_in_admin_user(){
    $admin_user = false;
    if(self::is_admin_logged_in()){
      $admin_user = self::get_logged_in_wp_user();
    }
    return $admin_user;
  }

  public static function get_logged_in_admin_user_id(){
    $admin_id = false;
    if(self::is_admin_logged_in()){
      $admin_id = self::get_logged_in_wp_user_id();
    }
    return $admin_id;
  }








  // WP USER
  public static function get_logged_in_wp_user_id(){
    return OsWpUserHelper::get_current_user_id();
  }

  public static function get_logged_in_wp_user(){
    return OsWpUserHelper::get_current_user();
  }
  

  // UTILS

  public static function hash_password($password){
    return password_hash($password, PASSWORD_DEFAULT);
  }

  public static function verify_password($password, $hash){
    return password_verify($password, $hash);
  }

}