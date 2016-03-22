<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
 /**
 * Date Class
 *
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @author		Victory Osayi Airuoyuwa
 * @since       Version 1.0
 */
class Date
{
    /**
    * Covert Date or DateTime to integer Timestamp
    * 
    * @param string $date: Date (2013-12-30) or DateTime (2013-12-31 12:30:58)
    * @return int $timestamp
    */
    function toTimestamp($date) 
    {
        // if $date is Date (2013-12-30)
        if (preg_match("/^([0-9]{4})-((0[1-9])|(1[0-2]))-(([0-2][1-9])|(3[0-1]))$/", $date)) {
            list($year, $month, $day) = explode('-', $date);
            $timestamp = mktime(0, 0, 0, $month, $day, $year);        
       
        // if $date is DateTime (2013-12-31 12:30:58)
        } elseif (preg_match("/^([0-9]{4})-((0[1-9])|(1[0-2]))-(([0-2][1-9])|(3[0-1])) ([0-2][0-9]):([0-5][0-9]):([0-5][0-9])$/", $date)) {
            list($date_part, $time_part) = explode(' ', $date);
            list($year, $month, $day) = explode('-', $date_part);
            list($hour, $minute, $second) = explode(':', $time_part);
            $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
        
        // set 0 and trigger error
        } else {
            $timestamp = 0;
            trigger_error('Invalid date or datetime format for toTimestamp conversion');
        }
        return $timestamp;
    }
    
    /**
    * Convert Date or DateTime or Timestamp to a Since ago past
    * 
    * @param string $date: Date (2013-12-30) or DateTime (2013-12-31 12:30:58) or Timestamp (1388489458)
    * @return string
    */
    function toSinceAgo($date)
    {
        // check if $date is not an integer Timestamp and convert date to timestamp
        if (!is_int($date)) {
            $timestamp = $this->toTimestamp($date);
        } else {
            $timestamp = $date;
        }
        $int_time_elapsed = time() - $timestamp;
    
        if ($int_time_elapsed < (60)) {
            $int_num = intval($int_time_elapsed); $str_unit = "second";
        } else if ($int_time_elapsed < (60*60)) {
            $int_num = intval($int_time_elapsed / 60); $str_unit = "minute";
        } else if ($int_time_elapsed < (24*60*60)) {
            $int_num = intval($int_time_elapsed / (60*60)); $str_unit = "hour";
        } else if ($int_time_elapsed < (30*24*60*60)) {
            $int_num = intval($int_time_elapsed / (24*60*60)); $str_unit = "day";
        } else if ($int_time_elapsed < (365*24*60*60)) {
            $int_num = intval($int_time_elapsed / (30*24*60*60)); $str_unit = "month";
        } else {
            $int_num = intval($int_time_elapsed / (365*24*60*60)); $str_unit = "year";
        }
        
        if ($int_num == 0){ // For Just Now
            return 'just now'; 
                        
        } elseif ($int_num < 0){ // For Future time
            /** Call convert to future time to come */
            return $this->toFutureTime($date);
                        
        } elseif ($int_num == 1 && $str_unit == 'hour') {  //For Hours  
            return "an " . $str_unit . (($int_num != 1) ? "s ago" : " ago");
                        
        } elseif ($int_num >= 1 && $int_num <= 7 && $str_unit == 'day') {    // For Days
            
                if ($int_num == 1) {
                    return "Yesterday at " . date('h:ma', $timestamp); //Return Yesterday at Time
                } else {
                    return date('l', $timestamp) . " at " . date('h:ma', $timestamp); //Return DayofTheWeek at Time
                }
                      
        } elseif ($int_num >= 1 && $str_unit == 'year') {
            return date('M jS, Y', $timestamp) . " at " . date('h:ma', $timestamp); //Return Month Day, Year  at Time
         
        } elseif ($int_num == 1 && $str_unit != 'hour') {   
            
                if ($str_unit == 'month') {
                    return date('M jS', $timestamp) . " at " . date('h:ma', $timestamp); //Return Month Day at Time
                
                } else {
                    return "a " . $str_unit . (($int_num != 1) ? "s ago" : " ago");
                }
         
        } else {   
            
                if ($int_num >= 12 && $int_num < 24 && $str_unit == 'hour') {
                    return "Today at " . date('h:ma', $timestamp); //Return Today at Time
                    
                } elseif ($int_num != 1 && $str_unit != 'second' && $str_unit != 'minute' && $str_unit != 'hour') {
                    return date('M jS', $timestamp) . " at " . date('h:ma', $timestamp); //Return Month Day at Time
                
                } else {
                    return $int_num . " " . $str_unit . (($int_num != 1) ? "s ago" : " ago"); // Return any other like Hours ago, months ago 
                }            
        }
    }
    
    /**
    * Convert Date or DateTime or Timestamp to Future time to come
    * 
    * @param string $date: Date (2013-12-30) or DateTime (2013-12-31 12:30:58) or Timestamp (1388489458)
    * @return string
    */
    function toFutureTime($date)
    {
        // check if $date is not an integer Timestamp and convert date to timestamp
        if (!is_int($date)) {
            $timestamp = $this->toTimestamp($date);
        } else {
            $timestamp = $date;
        }        
        $int_time_left = $timestamp - time();
    
        if ($int_time_left < (60)) {
            $int_num = intval($int_time_left); $str_unit = "second";
        } else if ($int_time_left < (60*60)) {
            $int_num = intval($int_time_left / 60); $str_unit = "minute";
        } else if ($int_time_left < (24*60*60)) {
            $int_num = intval($int_time_left / (60*60)); $str_unit = "hour";
        } else if ($int_time_left < (30*24*60*60)) {
            $int_num = intval($int_time_left / (24*60*60)); $str_unit = "day";
        } else if ($int_time_left < (365*24*60*60)) {
            $int_num = intval($int_time_left / (30*24*60*60)); $str_unit = "month";
        } else {
            $int_num = intval($int_time_left / (365*24*60*60)); $str_unit = "year";
        }
        
        
        if ($int_num <= 0){ // For Just Now
            return 'Just now'; 
                        
        } elseif ($int_num == 1 && $str_unit == 'hour') {  //For Hours  
            return "An " . $str_unit . (($int_num != 1) ? "s time" : " time");
                        
        } elseif ($int_num >= 1 && $int_num <= 7 && $str_unit == 'day') {    // For Days
            
                if ($int_num == 1) {
                    return "Tomorrow at " . date('h:ma', $timestamp); //Return Tomorrow at Time
                }elseif ($int_num == 2) {
                    return "Day after tomorrow at " . date('h:ma', $timestamp); //Return Day after tomorrow at Time
                } else {
                    return date('l', $timestamp) . " at " . date('h:ma', $timestamp); //Return DayofTheWeek at Time
                }
                      
        } elseif ($int_num >= 1 && $str_unit == 'year') {
            return date('M jS, Y', $timestamp) . " at " . date('h:ma', $timestamp); //Return Month Day, Year  at Time
         
        } elseif ($int_num == 1 && $str_unit != 'hour') {   
            
                if ($str_unit == 'month') {
                    return date('M jS', $timestamp) . " at " . date('h:ma', $timestamp); //Return Month Day at Time
                
                } else {
                    return "A " . $str_unit . (($int_num != 1) ? "s time" : " time");
                }
         
        } else {   
            
                if ($int_num >= 12 && $int_num < 24 && $str_unit == 'hour') {
                    return "Today at " . date('h:ma', $timestamp); //Return Today at Time
                    
                } elseif ($int_num != 1 && $str_unit != 'second' && $str_unit != 'minute' && $str_unit != 'hour') {
                    return date('M jS', $timestamp) . " at " . date('h:ma', $timestamp); //Return Month Day at Time
                
                } else {
                    return $int_num . " " . $str_unit . (($int_num != 1) ? "s time" : " time"); // Return any other like Hours ago, months ago 
                }            
        }
    }
    
    /**
    * Convert Date or DateTime or Timestamp to a ISO8601 date
    * 
    * @param string $date: Date (2013-12-30) or DateTime (2013-12-31 12:30:58) or Timestamp (1388489458)
    * @return string datetime 
    */
    function toIso8601($date = null)
    {
        
        if ($date !== null) {
            // check if $date is not an integer Timestamp and convert date to timestamp
            if (!is_int($date)) {
                $timestamp = $this->toTimestamp($date);
            } else {
                $timestamp = $date;
            }
            return date(DATE_ISO8601, $timestamp);  //'Y-m-d\TH:i:sO' is ISO8601
        } else {
            return date(DATE_ISO8601); //'Y-m-d\TH:i:sO' is ISO8601
        }
    }
        
    
    /**
    * Get current date
    * 
    * @param int $timestamp
    * @return string date
    */
    function getDate($timestamp = null)
    {
        if ($timestamp !== null) {
            return date('Y-m-d', $timestamp);
        } else {
            return date('Y-m-d');
        }
    }
    
    /**
    * Get current date time
    * 
    * @param int $timestamp 
    * @return string datetime 
    */
    function getDateTime($timestamp = null)
    {
        if ($timestamp !== null) {
            return date('Y-m-d G:i:s', $timestamp);
        } else {
            return date('Y-m-d G:i:s');
        }
    }
    
    /**
    * Get formated current date
    * 
    * @param string $date: Date (2013-12-30) or DateTime (2013-12-31 12:30:58) or Timestamp (1388489458)
    * @return string date
    */
    function getFormatedDate($date = null, $short_date=false)
    {        
        if ($date !== null) {
            // check if $date is not an integer Timestamp and convert date to timestamp
            if (!is_int($date)) {
                $timestamp = $this->toTimestamp($date);
            } else {
                $timestamp = $date;
            }
            if ($short_date === true) {
                return date('D, M j, Y', $timestamp);
            } else {
                return date('l, F j, Y', $timestamp);
            }            
        } else {
            if ($short_date === true) {
                return date('D, M j, Y');
            } else {
                return date('l, F j, Y');
            } 
        }
    }
    
    /**
    * Get formated current date time
    * 
    * @param string $date: Date (2013-12-30) or DateTime (2013-12-31 12:30:58) or Timestamp (1388489458)
    * @return string datetime 
    */
    function getFormatedDateTime($date = null, $short_datetime=false)
    {            
        
        if ($date !== null) {
            // check if $date is not an integer Timestamp and convert date to timestamp
            if (!is_int($date)) {
                $timestamp = $this->toTimestamp($date);
            } else {
                $timestamp = $date;
            }
            if ($short_datetime === true) {
                return date('D, M j, Y \a\t h:i a', $timestamp);
            } else {
                return date('l, F j, Y \a\t h:i a', $timestamp);
            }            
        } else {
            if ($short_datetime === true) {
                return date('D, M j, Y \a\t h:i a');
            } else {
                return date('l, F j, Y \a\t h:i a');
            } 
        }
    }
    
    /**
    * Format Date / Time
    * 
    * @param string $format
    * @param string $date: Date (2013-12-30) or DateTime (2013-12-31 12:30:58) or Timestamp (1388489458)
    * @return string 
    */
    function format($format, $date = null)
    {        
        if ($date !== null) {
            // check if $date is not an integer Timestamp and convert date to timestamp
            if (!is_int($date)) {
                $timestamp = $this->toTimestamp($date);
            } else {
                $timestamp = $date;
            }
            return date("$format", $timestamp);          
        } else {
            return date("$format");
        }
    }
    
    
    
    /**
    * Get array of number of days in a month
    * 
    * @param int $for_month
    * @param int $year
    * @param bool Add blank option item at the beginning of array
    * @return array 
    */
    function getDaysInAMonth($for_month = null, $year = null, $blank_option_item = true)
    {
        if ($for_month !== null && $year !== null){
            if ($for_month == date('m') && $year == date('Y')) {
                $highest_day = date('d'); //set the highest number of day to TODAY's Date if Month and Year = Today's own
            } else {
                $highest_day = cal_days_in_month(CAL_GREGORIAN, $for_month, $year); //number of days in a particular month of selected year
            }            
        } else {
            $highest_day = 31;
        }     
        
        if ($blank_option_item === true) {
            $days_array[''] = 'Day:';
        }
        
        for($day=1; $day<=$highest_day; $day++){
            $days_array[$day] = $day;
        }
        return $days_array;
    }
    
    /**
    * Get month name
    * 
    * @param int month number
    * @param bool $abbreviate : abbreviate month name
    * @return string
    */
    
    function getMonthName($month_number, $abbreviate = false)
    {
        $month_array = array(
                '1'=>array('Jan','January'), 
                '2'=>array('Feb','February'),
                '3'=>array('Mar','March'),
                '4'=>array('Apr','April'),
                '5'=>array('May','May'),
                '6'=>array('Jun','June'),
                '7'=>array('Jul','July'),
                '8'=>array('Aug','August'),
                '9'=>array('Sep','September'),
                '10'=>array('Oct','October'),
                '11'=>array('Nov','November'),
                '12'=>array('Dec','December')
            );
        if ($abbreviate === true) {            
            $month_name = $month_array[$month_number][0];  //for abbreviated  month name           
        } else {           
             $month_name = $month_array[$month_number][1];  // for full  month name 
        }
        return $month_name;
    }
    
    /**
    * Get array of months
    * 
    * @param bool abbreviated month name 
    * @param bool Add blank option item at the beginning
    * @return array 
    */
    function getMonths($abbreviate = false, $blank_option_item = true)
    {
        if ($blank_option_item === true) {
            $month_array[''] = 'Month:';
        }
        
        for($month_number=1; $month_number<=12; $month_number++){
            $month_name = $this->getMonthName($month_number, $abbreviate); 
            $month_array[$month_number] = $month_name;                                  
        }
        return $month_array;
    }
    
    /**
    * Generate and get array of range of years
    * 
    * @param int $max: maximum value for year 
    * @param int $min: minimum value for year    
    * @param bool Add blank option item at the beginning    
    * @return array
    */
    function getYears($max = 2013, $min = 1901, $blank_option_item = true)
    {
        if ($blank_option_item === true) {
            $years_array[''] = 'Year:';
        }
        
        for($year=$max; $year>=$min; $year--){ 
            $years_array[$year] = $year;   
        }        
        return $years_array;
    }  
    
}//End of Class  
