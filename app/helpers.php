<?php

if (!function_exists("dateWithFormatter")) {

    function dateWithFormatter($string)
    {
        $months = [
            'January',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $d = date("d", strtotime($string));
        $m = $months[intval(date("m", strtotime($string))) - 1];
        $y = date("Y", strtotime($string));
        $res = $d . " " . $m . " " . $y;
        return $res;
    }
}
