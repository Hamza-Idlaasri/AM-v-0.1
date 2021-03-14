<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    public function host($host_id)
    {
        $details = DB::table('nagios_hosts')
        ->where('host_id','=',$host_id)
        ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id')
        ->get();

        return view('details.host&box',compact('details'));
    }

    public function service($service_id)
    {
        $details = DB::table('nagios_hosts')
        ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
        ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
        ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_services.display_name as service_name','nagios_services.*','nagios_servicestatus.*')
        ->where('service_id','=',$service_id)
        ->get();

        return view('details.service&equip',compact('details'));
    }
}
