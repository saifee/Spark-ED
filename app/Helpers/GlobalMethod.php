<?php
namespace App\Helpers;

class GlobalMethod
{
    public function parseDate($date){
        $date=substr($date, 0, 10);
        if (strpos($date, '/') !== false) {
            list($day, $month, $year) =explode('/',$date);
        }else{
            list($day, $month, $year) =explode('-',$date);
        }
        return $year.'-'.$month.'-'.$day;
    }

    public function parseDateTime($dateTime){
        $date=substr($dateTime, 0, 10);
        $time=substr($dateTime, 11, 13);
        $time=substr($time, 0, 8);

        if (strpos($dateTime, '/') !== false) {
            list($day, $month, $year) =explode('/',$date);
            list($hour, $minute, $second) =explode(':',$time);
        }else{
            list($day, $month, $year) =explode('-',$date);
            list($hour, $minute, $second) =explode(':',$time);
        }
        return $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute.':'.$second;
    }
}