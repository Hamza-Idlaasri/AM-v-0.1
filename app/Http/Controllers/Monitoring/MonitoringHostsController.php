<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringHostsController extends Controller
{

    public function show()
    {
        
        $hosts = DB::table('nagios_hoststatus')->join('nagios_hosts','nagios_hoststatus.host_object_id','=','nagios_hosts.host_object_id')->get();

        return view('monitoring.hosts',compact('hosts'));
    }
}
