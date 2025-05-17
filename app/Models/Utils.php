<?php

namespace App\Models;
use Carbon\Carbon;

class Utils 
{

       public static function my_date_3($t)
    {
        $c = Carbon::parse($t);
        //set timezone
        if ($t == null) {
            return $t;
        }
        $c->setTimezone('Africa/Nairobi');
        return $c->format('D d-m-Y');
    }


     public static function money_short($money)
    {
        if ($money < 1000) {
            return number_format($money);
        }
        if ($money < 1000000) {
            return round($money / 1000, 2) . "K";
        }
        if ($money < 1000000000) {
            return round($money / 1000000, 2) . "M";
        }
        return round($money / 1000000000, 2) . "B";
    }


      public static function my_date($t)
    {
        $c = Carbon::parse($t);
        if ($t == null) {
            return $t;
        }
        $c->setTimezone('Africa/Nairobi');
        return $c->format('d M, Y');
    }

    public static function my_date_time($t)
    {
        $c = Carbon::parse($t);
        $c->setTimezone('Africa/Nairobi');
        if ($t == null) {
            return $t;
        }
        return $c->format('d M, Y - h:m a');
    }

    public static function to_date_time($raw)
    {
        $t = Carbon::parse($raw);
        if ($t == null) {
            return  "-";
        }
        $my_t = $t->toDateString();

        return $my_t . " " . $t->toTimeString();
    }

    public static function file_upload($file)
    {
        if ($file == null) {
            return '';
        }
        //get file extension
        $file_extension = $file->getClientOriginalExtension();
        $file_name = time() . "_" . rand(1000, 100000) . "." . $file_extension;
        $public_path = public_path() . "/storage/images";
        $file->move($public_path, $file_name);
        $url = 'images/' . $file_name;
        return $url;
    }


}
