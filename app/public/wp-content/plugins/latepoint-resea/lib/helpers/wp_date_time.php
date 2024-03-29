<?php 
class OsWpDateTime extends DateTime {
  function __construct($time = 'now'){
      parent::__construct($time, OsTimeHelper::get_wp_timezone());
  }

  public static function os_createFromFormat($format, $datetime_string, $timezone = false){
	$timezone = ($timezone) ? $timezone : OsTimeHelper::get_wp_timezone();
  	return DateTime::createFromFormat($format, $datetime_string, $timezone);
  }

  public static function os_get_start_of_google_event($google_event){
  	if(!empty($google_event->start->dateTime)){
  		$date_string = $google_event->start->dateTime;
  		$date_format = \DateTime::RFC3339;
  	}else{
		  $date_string = $google_event->start->date;
		  $date_string = $date_string . "00:00:00";
		  $date_format = 'Y-m-d H:i:s';

  	}
		return self::os_createFromFormat($date_format, $date_string);
  }

  public static function os_get_end_of_google_event($google_event){
  	if(!empty($google_event->end->dateTime)){
  		$date_string = $google_event->end->dateTime;
		$date_format = \DateTime::RFC3339;
		return self::os_createFromFormat($date_format, $date_string);
  	}else{
		  $date_string = $google_event->end->date;
		  $date_string = $date_string . "00:00:00";
		  $date_format = 'Y-m-d H:i:s';
		  $endOfDateTime = DateTime::createFromFormat($date_format, $date_string);
		  $endOfDateTimestamp = $endOfDateTime->getTimestamp();
		  $endOfDate = $endOfDateTime->setTimestamp($endOfDateTimestamp - 1);
		  var_dump($endOfDate);
		  $d = new DateTime($endOfDate->date);
		  return self::os_createFromFormat($date_format, $d->format('Y-m-d H:i:s'));
  	}
  }
}