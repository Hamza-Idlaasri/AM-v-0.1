<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringServicesController extends Controller
{
    public function show()
    {
        $services = DB::table('nagios_hosts')
        ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
        ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
        ->get();

        $hosts = DB::table('nagios_hosts')->get();
   
        
        return view('monitoring.services',compact('services','hosts'));

    }
}
