<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationsController extends Controller
{
    public function index()
    {
        // $services_notifs = DB::table('nagios_notifications')
        //     ->join('nagios_services','nagios_services.service_object_id','=','nagios_notifications.object_id')
        //     ->join('nagios_hosts','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
        //     ->select('nagios_services.display_name as service_name','nagios_hosts.display_name as host_name','nagios_notifications.*')
        //     ->orderByDesc('start_time')
        //     ->get();

        // $hosts_notifs = DB::table('nagios_notifications')
        //     ->join('nagios_hosts','nagios_hosts.host_object_id','=','nagios_notifications.object_id')
        //     ->select('nagios_hosts.display_name as host_name','nagios_notifications.*')
        //     ->orderByDesc('start_time')
        //     ->get();
        
        // $notif =(object) array_merge_recursive((array)$services_notifs, (array)$hosts_notifs);
        
        // dd($notif);

        $myfile = fopen('C:\Users\pc\Desktop\Laravel\test.txt', "w") or die("Unable to open file!");
        $txt = "John Doe\t";
        fwrite($myfile, $txt);
        $txt = "test 12\n";
        fwrite($myfile, $txt);
        fclose($myfile);

        return back();
    }

   
}
