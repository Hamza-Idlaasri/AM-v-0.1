<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BoxsController extends Controller
{
    public function show()
    {
        $search = request()->query('search');

        if($search)
        {
            $boxs = DB::table('nagios_hosts')
            ->where('alias','box')
            ->where('nagios_hosts.display_name','like', '%'.$search.'%')
            ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id')
            ->paginate(10);

        } else {

            $boxs = DB::table('nagios_hosts')
            ->where('alias','box')
            ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id')
            ->paginate(10);
        }        

        

        
        return view('monitoring.boxs',compact('boxs'));
    }

    public function problems()
    {
        $search = request()->query('search');

        if($search)
        {
            $boxs_problems = DB::table('nagios_hosts')
            ->where('alias','box')
            ->where('nagios_hosts.display_name','like', '%'.$search.'%')
            ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id')
            ->where('current_state','<>','0')
            ->paginate(10);
            
        } else {
            
            $boxs_problems = DB::table('nagios_hosts')
            ->where('alias','box')
            ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id')
            ->where('current_state','<>','0')
            ->paginate(10);
            
        }
        


        
        return view('problems.boxs',compact('boxs_problems'));
    }

    public function historic()
    {
        $search = request()->query('search');

        if($search)
        {
            $boxs_history = DB::table('nagios_hosts')
            ->where('alias','box')
            ->where('nagios_hosts.display_name','like', '%'.$search.'%')
            ->join('nagios_statehistory','nagios_hosts.host_object_id','=','nagios_statehistory.object_id')
            ->paginate(10);

        } else {

            $boxs_history = DB::table('nagios_hosts')
            ->where('alias','box')
            ->join('nagios_statehistory','nagios_hosts.host_object_id','=','nagios_statehistory.object_id')
            ->paginate(10);

        }

        return view('historique.boxs',compact('boxs_history'));
    }

    public function statistic()
    {
        $boxs_statistic = DB::table('nagios_hosts')
        ->where('alias','box')
        ->join('nagios_statehistory','nagios_hosts.host_object_id','=','nagios_statehistory.object_id')
        ->get();

        return view('example',compact('boxs_statistic'));
    }

}
