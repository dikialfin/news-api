<?php

    namespace App\Libraries;

    class Helpers {

        /*
            FUNCTION GET DATE TODAY
        function ini berfungsi untuk mengambil tanggal hari ini dalam bentuk epoch time
        return berupa integer 
        */
        static function getDateToday() {
            return strtotime(date("Y-m-d"));
        }

    }