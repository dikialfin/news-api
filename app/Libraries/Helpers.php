<?php

namespace App\Libraries;

class Helpers
{

    /*
            FUNCTION GET DATE TODAY
        function ini berfungsi untuk mengambil tanggal hari ini dalam bentuk epoch time
        return berupa integer 
        */
    static function getDateToday()
    {
        return strtotime(date("Y-m-d"));
    }

    static function generateToken($length = 32)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
