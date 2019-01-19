<?php
/**
 * @author Julian Andres Muñoz Cardozo
 * @email julianmc90@gmail.com
 * @create date 2019-01-19 15:46:58
 * @modify date 2019-01-19 15:50:04
 * @desc Utility Class
 */
namespace App\Util;
use DatePeriod;
use DateTime;
use DateInterval;

class Util {

    /**
     * Constructor 
     */
    public function __construct()
    {
        
    }

    /**
     * Checks  if a string is a correct representation of a json array objects
     *
     * @param string $str
     * @return boolean 
     */
    public static function isJson($str) {

        $json = json_decode($str);
        return $json && $str != $json;
    }

     /**
      * Calculate the dates between two dates
      *
      * @param String $dateIni Initial date
      * @param String $dateEnd End date
      * @return void
      */
      public static function getDatesBetweenDates($dateIni, $dateEnd){

        $datesBetween = [];

        //Finding dates between two dates as an array
        $dates = new DatePeriod(
            new DateTime($dateIni), new DateInterval('P1D'), new DateTime($dateEnd)
        );

        //Setting up some format
        foreach ($dates as $key => $value) {
             $datesBetween[] = $value->format('Y-m-d');       
        }

        if(count($datesBetween) > 0){
            $datesBetween[] = $dateEnd;
        }

        return $datesBetween;
    }

     /**
      * Remove ocurrences from one array to another
      * similar to array_udiff 
      * @param [Array] $a ocurrences to remove
      * @param [Array] $b array to remove ocurrences
      * @return Array new array
      */
    public static function arrayDiff($a, $b){
        $r = [];
        foreach ($b as $item) {
            if(!in_array($item,  $a)){
                $r[] = $item;
            }
        }
        return $r;
    }
    
    /**
     * Get the elements that are inside the two arrays
     * Similar to array_uintersect
     * @param [Array] $a array to compare
     * @param [Array] $b array to compare
     * @return Array new array
     */
    public static function arrayIntersect($a,$b){
        $r = [];
        foreach ($b as $item) {
            if(in_array($item,  $a)){
                $r[] = $item;
            }
        }
        return $r;
    }


}
