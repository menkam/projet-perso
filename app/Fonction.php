<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Fonction
{
    public static function getTime($format){
        $heure = getDate();
        $currentTime = '';
        if($format == "h:m"){
            if(($heure["hours"]+1) < 10 && $heure["minutes"] < 10){
                $currentTime = ("0".($heure["hours"]+1).":0".$heure["minutes"].":".$heure["seconds"]);
            }
            if(($heure["hours"]+1) < 10 && $heure["minutes"] > 10){
                $currentTime = ("0".($heure["hours"]+1).":".$heure["minutes"].":".$heure["seconds"]);
            }
            if(($heure["hours"]+1) > 10 && $heure["minutes"] < 10){
                $currentTime = (($heure["hours"]+1).":0".$heure["minutes"].":".$heure["seconds"]);
            }
            if(($heure["hours"]+1) > 10 && $heure["minutes"] > 10){
                $currentTime = (($heure["hours"]+1).":".$heure["minutes"].":".$heure["seconds"]);
            }
        }if($format == "H"){
            if(($heure["hours"]+1) < 10){
                $currentTime = ("0".($heure["hours"]+1)."H");
            }else {
                $currentTime = (($heure["hours"]+1)."H");
            }
        }
        return $currentTime;
        //return "08H";
    }

    public static  function getDate(){
        $date = getDate();
        $currentDate = '';
        if((int)$date["mon"] < 10 && (int)$date["mday"] < 10){
            $currentDate = $date["year"]."-0".$date["mon"]."-0".$date["mday"];
        }
        if((int)$date["mon"] < 10 && (int)$date["mday"] >= 10){
            $currentDate = $date["year"]."-0".$date["mon"]."-".$date["mday"];
        }
        if((int)$date["mon"] >= 10 && (int)$date["mday"] < 10){
            $currentDate = $date["year"]."-".$date["mon"]."-0".$date["mday"];
        }
        if((int)$date["mon"] >= 10 && (int)$date["mday"] >= 10){
            $currentDate = $date["year"]."-".$date["mon"]."-".$date["mday"];
        }
        return $currentDate;
        //return "18-07-2018";
    }
}