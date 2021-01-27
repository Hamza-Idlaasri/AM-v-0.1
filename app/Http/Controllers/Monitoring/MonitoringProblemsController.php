<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringProblemsController extends Controller
{
    public function show()
    {
        $problems = DB::table('nagios_hosts')
        ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
        ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
        ->get();
        
        $hosts = DB::table('nagios_hosts')->get();

        $hp = DB::table('nagios_hoststatus')
        ->join('nagios_hosts','nagios_hoststatus.host_object_id','=','nagios_hosts.host_object_id')
        ->get();

        return view('monitoring.problems',compact('problems','hosts','hp'));
    }
}
