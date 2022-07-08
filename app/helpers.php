<?php

use Jenssegers\Agent\Agent;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

if (!function_exists("dateWithFormatter")) {

    function dateWithFormatter($string)
    {
        $months = [
            'Januari',
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

if (!function_exists("storeAudit")) {
    function storeAudit($object)
    {
        // // Ambil user agent
        // $agent = new Agent();
        // $browser = $agent->browser();
        // $ver_browser = $agent->version($browser);
        // $platform = $agent->platform();
        // $ver_platform = $agent->version($platform);
        // $userAgent = $browser . ' ' . $ver_browser . '; ' . $platform . ' ' . $ver_platform . ';';

        // Ambil url
        $url = URL::current();

        // // Ambil mac address
        // $macAddr = exec('getmac');
        // $clientIP = request()->ip();

        AuditTrail::create([
            'user_id' => $object['users'],
            'kegiatan' => $object['kegiatan'],
            'deskripsi' => $object['deskripsi'],
            'url' => $url,
            // 'ip_address' => $clientIP,
            // 'mac_address' => $macAddr,
            // 'user_agent' => $userAgent
        ]);
    }
}
