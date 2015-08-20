<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
 /**
 * Date Class
 *
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class Date
{
    
    /**
    * Convert to Since ago past of a timestamp
    * 
    * @param int Unix timestamp
    * @return string
    */
    function convertToSinceAgo($timestamp, $timestamp_is_datetime=false)
    {
        if ($timestamp_is_datetime === true) {
            $timestamp = $this->dateTimeToIntegerTimestamp($timestamp);
        }
        $iTimeElapsed = time() - $timestamp;
    
        if ($iTimeElapsed < (60)) {
            $iNum = intval($iTimeElapsed); $sUnit = "second";
        } else if ($iTimeElapsed < (60*60)) {
            $iNum = intval($iTimeElapsed / 60); $sUnit = "minute";
        } else if ($iTimeElapsed < (24*60*60)) {
            $iNum = intval($iTimeElapsed / (60*60)); $sUnit = "hour";
        } else if ($iTimeElapsed < (30*24*60*60)) {
            $iNum = intval($iTimeElapsed / (24*60*60)); $sUnit = "day";
        } else if ($iTimeElapsed < (365*24*60*60)) {
            $iNum = intval($iTimeElapsed / (30*24*60*60)); $sUnit = "month";
        } else {
            $iNum = intval($iTimeElapsed / (365*24*60*60)); $sUnit = "year";
        }
        
        
        if ($iNum == 0){ // For Just Now
            return 'just now'; 
                        
        } elseif ($iNum < 0){ // For Future time
            /** Call convert to future time to come */
            return $this->convertToFutureTime($timestamp);
                        
        } elseif ($iNum == 1 && $sUnit == 'hour') {  //For Hours  
            return "an " . $sUnit . (($iNum != 1) ? "s ago" : " ago");
                        
        } elseif ($iNum >= 1 && $iNum <= 7 && $sUnit == 'day') {    // For Days
            
                if ($iNum == 1) {
                    return "Yesterday at " . date('h:ma', $timestamp); //Return Yesterday at Time
                } else {
                    return date('l', $timestamp) . " at " . date('h:ma', $timestamp); //Return DayofTheWeek at Time
                }
                      
        } elseif ($iNum >= 1 && $sUnit == 'year') {
            return date('M jS, Y', $timestamp) . " at " . date('h:ma', $timestamp); //Return Month Day, Year  at Time
         
        } elseif ($iNum == 1 && $sUnit != 'hour') {   
            
                if ($sUnit == 'month') {
                    return date('M jS', $timestamp) . " at " . date('h:ma', $timestamp); //Return Month Day at Time
                
                } else {
                    return "a " . $sUnit . (($iNum != 1) ? "s ago" : " ago");
                }
         
        } else {   
            
                if ($iNum >= 12 && $iNum < 24 && $sUnit == 'hour') {
                    return "Today at " . date('h:ma', $timestamp); //Return Today at Time
                    
                } elseif ($iNum != 1 && $sUnit != 'second' && $sUnit != 'minute' && $sUnit != 'hour') {
                    return date('M jS', $timestamp) . " at " . date('h:ma', $timestamp); //Return Month Day at Time
                
                } else {
                    return $iNum . " " . $sUnit . (($iNum != 1) ? "s ago" : " ago"); // Return any other like Hours ago, months ago 
                }            
        }
    }
    
    
    
    /**
    * Convert to Future time to come of a timestamp
    * 
    * @param int Unix timestamp
    * @return string
    */
    function convertToFutureTime($timestamp)
    {
        $iTimeLeft = $timestamp - time();
    
        if ($iTimeLeft < (60)) {
            $iNum = intval($iTimeLeft); $sUnit = "second";
        } else if ($iTimeLeft < (60*60)) {
            $iNum = intval($iTimeLeft / 60); $sUnit = "minute";
        } else if ($iTimeLeft < (24*60*60)) {
            $iNum = intval($iTimeLeft / (60*60)); $sUnit = "hour";
        } else if ($iTimeLeft < (30*24*60*60)) {
            $iNum = intval($iTimeLeft / (24*60*60)); $sUnit = "day";
        } else if ($iTimeLeft < (365*24*60*60)) {
            $iNum = intval($iTimeLeft / (30*24*60*60)); $sUnit = "month";
        } else {
            $iNum = intval($iTimeLeft / (365*24*60*60)); $sUnit = "year";
        }
        
        
        if ($iNum <= 0){ // For Just Now
            return 'Just now'; 
                        
        } elseif ($iNum == 1 && $sUnit == 'hour') {  //For Hours  
            return "An " . $sUnit . (($iNum != 1) ? "s time" : " time");
                        
        } elseif ($iNum >= 1 && $iNum <= 7 && $sUnit == 'day') {    // For Days
            
                if ($iNum == 1) {
                    return "Tomorrow at " . date('h:ma', $timestamp); //Return Yesterday at Time
                }elseif ($iNum == 2) {
                    return "Day after tomorrow at " . date('h:ma', $timestamp); //Return Yesterday at Time
                } else {
                    return date('l', $timestamp) . " at " . date('h:ma', $timestamp); //Return DayofTheWeek at Time
                }
                      
        } elseif ($iNum >= 1 && $sUnit == 'year') {
            return date('M jS, Y', $timestamp) . " at " . date('h:ma', $timestamp); //Return Month Day, Year  at Time
         
        } elseif ($iNum == 1 && $sUnit != 'hour') {   
            
                if ($sUnit == 'month') {
                    return date('M jS', $timestamp) . " at " . date('h:ma', $timestamp); //Return Month Day at Time
                
                } else {
                    return "A " . $sUnit . (($iNum != 1) ? "s time" : " time");
                }
         
        } else {   
            
                if ($iNum >= 12 && $iNum < 24 && $sUnit == 'hour') {
                    return "Today at " . date('h:ma', $timestamp); //Return Today at Time
                    
                } elseif ($iNum != 1 && $sUnit != 'second' && $sUnit != 'minute' && $sUnit != 'hour') {
                    return date('M jS', $timestamp) . " at " . date('h:ma', $timestamp); //Return Month Day at Time
                
                } else {
                    return $iNum . " " . $sUnit . (($iNum != 1) ? "s time" : " time"); // Return any other like Hours ago, months ago 
                }            
        }
    }
    
    
    
    /**
    * The whole application default current date
    * 
    * @param int Timestamp
    * @return string date
    */
    function getCurrentDate($timestamp = NULL)
    {
        if ($timestamp !== NULL) {
            return date('Y-m-d', $timestamp);
        } else {
            return date('Y-m-d');
        }
    }
    
    /**
    * The whole application default current date time format
    * 
    * @param int Timestamp 
    * @return string datetime 
    */
    function getCurrentDateTime($timestamp = NULL)
    {
        if ($timestamp !== NULL) {
            return date('Y-m-d G:i:s', $timestamp);
        } else {
            return date('Y-m-d G:i:s');
        }
    }
    
    /**
    * The whole application default current date time format
    * 
    * @param int Timestamp 
    * @return string datetime 
    */
    function getFormatedDateTime($timestamp = NULL, $timestamp_is_datetime=false, $short_datetime=false)
    {
        
        if ($timestamp !== NULL) {
            if ($timestamp_is_datetime === true) {
                $timestamp = $this->dateTimeToIntegerTimestamp($timestamp);
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
    
    function getFormatedDate($timestamp = NULL, $timestamp_is_datetime=false, $short_datetime=false)
    {
        
        if ($timestamp !== NULL) {
            if ($timestamp_is_datetime === true) {
                $timestamp = $this->dateToIntegerTimestamp($timestamp);
            }
            if ($short_datetime === true) {
                return date('D, M j, Y', $timestamp);
            } else {
                return date('l, F j, Y', $timestamp);
            }            
        } else {
            if ($short_datetime === true) {
                return date('D, M j, Y');
            } else {
                return date('l, F j, Y');
            } 
        }
    }
    function formatDateTime($format, $timestamp = NULL, $timestamp_is_datetime=false)
    {
        
        if ($timestamp !== NULL) {
            if ($timestamp_is_datetime === true) {
                $timestamp = $this->dateTimeToIntegerTimestamp($timestamp);
            }
            return date("$format", $timestamp);          
        } else {
            return date("$format");
        }
    }
    
    /**
    * The whole application default current date time format
    * 
    * @param int Timestamp 
    * @return string datetime 
    */
    function convertToIso8601($timestamp = NULL, $timestamp_is_datetime=false)
    {
        
        if ($timestamp !== NULL) {
            if ($timestamp_is_datetime === true) {
                $timestamp = $this->dateTimeToIntegerTimestamp($timestamp);
            }
            return date(DATE_ISO8601, $timestamp);  //'Y-m-d\TH:i:sO' is ISO8601
        } else {
            return date(DATE_ISO8601); //'Y-m-d\TH:i:sO' is ISO8601
        }
    }
    
    
    /**
    * Convert datetime to integer timestamp
    * 
    * @param string date and time
    * @return int
    */
    function dateTimeToIntegerTimestamp($datetime_string) 
    {
    
        list($date, $time) = explode(' ', $datetime_string);
        list($year, $month, $day) = explode('-', $date);
        list($hour, $minute, $second) = explode(':', $time);
        $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
        return $timestamp;
    }
    
    /**
    * Convert date to integer timestamp
    * 
    * @param string date
    * @return int 
    */
    function dateToIntegerTimestamp($date_string) 
    {
        list($year, $month, $day) = explode('-', $date_string);
        $timestamp = mktime(0, 0, 0, $month, $day, $year);
        return $timestamp;
    }
    
    /**
    * Get number of days in month on array
    * 
    * @param int return highest day for month
    * @param int  for month
    * @param bool Add blank option item at the beginning
    * @return array 
    */
    function getDaysArray($for_month = NULL, $year = NULL, $blank_option_item = TRUE)
    {
        if ($for_month !== NULL && $year !== NULL){
            if ($for_month == date('m') && $year == date('Y')) {
                $highest_day = date('d'); //set the highest number of day to TODAY's Date if Month and Year = Today's own
            } else {
                $highest_day = cal_days_in_month(CAL_GREGORIAN, $for_month, $year); //number of days in a particular month of selected year
            }            
        } else {
            $highest_day = 31;
        }     
        
        if ($blank_option_item === TRUE) {
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
    * @param bool abbreviated month name
    * @return string
    */
    
    function getMonthName($month_number, $abbreviate = FALSE)
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
        if ($abbreviate === TRUE) {            
            $month_name = $month_array[$month_number][0];  //for abbreviated  month name           
        } else {           
             $month_name = $month_array[$month_number][1];  // for full  month name 
        }
        return $month_name;
    }
    
    /**
    * Get months on array
    * 
    * @param bool abbreviated month name 
    * @param bool Add blank option item at the beginning
    * @return array 
    */
    function getMonthsArray($abbreviate = FALSE, $blank_option_item = TRUE)
    {
        if ($blank_option_item === TRUE) {
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
    * @param int maximum value for year 
    * @param int minimum value for year    
    * @param bool Add blank option item at the beginning    
    * @return array
    */
    function getYearsArray($max = 2013, $min = 1901, $blank_option_item = TRUE)
    {
        if ($blank_option_item === TRUE) {
            $years_array[''] = 'Year:';
        }
        
        for($year=$max; $year>=$min; $year--){ 
            $years_array[$year] = $year;   
        }        
        return $years_array;
    }  
    
}//End of Class  