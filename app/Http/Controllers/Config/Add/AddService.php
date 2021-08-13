<?php

namespace App\Http\Controllers\Config\Add;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddService extends Controller
{
    public function manage()
    {
        $hosts = DB::table('nagios_hosts')
        ->where('alias','host')
        ->select('nagios_hosts.display_name as host_name','nagios_hosts.*')
        ->get();

        $windows = ['NSClient++ Version','Uptime (windows)','CPU Load','Memory Usage','C Drive Space','W3SVC'];

        $linux = ['PING (linux)','Current Load','Total Processes','Current Users','SSH','HTTP','Root Partition','Swap Usage'];

        $router = ['PING (router)','Port n Link Status','Uptime (router)'];

        $switch = ['PING (swicth)','Port n Link Status','Uptime (switch)','Port n Bandwidth Usage'];

        $printer = ['PING (printer)','Printer Status'];

        return view('config.add.service', compact('hosts','windows','linux','router','switch','printer'));
    }

    public function addService()
    {
        return 'hello';
    }
}
