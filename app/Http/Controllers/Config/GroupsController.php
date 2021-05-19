<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['agent']);
    }
    
    public function hostgroups()
    {
        $hostgroups = DB::table('nagios_hostgroups')->get();

        $members = DB::table('nagios_hostgroups')
        ->join('nagios_hostgroup_members','nagios_hostgroup_members.hostgroup_id','=','nagios_hostgroups.hostgroup_id')
        ->join('nagios_hosts','nagios_hosts.host_object_id','=','nagios_hostgroup_members.host_object_id')
        ->join('nagios_hoststatus','nagios_hoststatus.host_object_id','=','nagios_hosts.host_object_id')
        ->select('nagios_hosts.alias as type','nagios_hostgroups.alias as hostgroup','nagios_hosts.display_name as host_name','nagios_hosts.host_object_id','nagios_hoststatus.current_state','nagios_hostgroups.hostgroup_id')
        ->get();

        $member_services = DB::table('nagios_hosts')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->select('nagios_services.display_name as service_name','nagios_servicestatus.current_state','nagios_hosts.host_object_id')
            ->get();

        // dd($member_services,$members,$hostgroups);

        return view('config.groups.hostgroups', compact('hostgroups','members','member_services'));
    }

    public function HGdetails($hostgroup_id)
    {
        $members = DB::table('nagios_hostgroups')
            ->join('nagios_hostgroup_members','nagios_hostgroup_members.hostgroup_id','=','nagios_hostgroups.hostgroup_id')
            ->join('nagios_hosts','nagios_hosts.host_object_id','=','nagios_hostgroup_members.host_object_id')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->select('nagios_hosts.alias as type','nagios_hostgroups.alias as hostgroup','nagios_hosts.display_name as host_name','nagios_hosts.host_object_id','nagios_hosts.host_id','nagios_hostgroups.hostgroup_id','nagios_services.display_name as service_name','nagios_servicestatus.current_state','nagios_servicestatus.output','nagios_servicestatus.last_check','nagios_hosts.host_object_id')
            ->where('nagios_hostgroups.hostgroup_id',$hostgroup_id)
            ->get();

        $hostgroup = DB::table('nagios_hostgroups')
            ->where('hostgroup_id',$hostgroup_id)
            ->get();

        return view('config.groups.HGdetails', compact('members','hostgroup'));
    }

    public function addHG()
    {
        $hosts = DB::table('nagios_hosts')
        ->where('alias','host')
        ->select('nagios_hosts.display_name as host_name')
        ->get();

        return view('config.groups.addHG', compact('hosts'));
    }

    public function createHG(Request $request)
    {
        // validation
        $this->validate($request,[
            'hostgroup_name' => 'required',
            'members' => 'required',
        ],[
            'members.required' => 'Please check hosts members for your hostgroup',
        ]);

        $members = [];

        foreach ($request->members as $member) {
           array_push($members,$member);
        }

        $define_hostgroup = "\ndefine hostgroup {\n\thostgroup_name\t\t".$request->hostgroup_name."\n\talias\t\t\t".$request->hostgroup_name."\n\tmembers\t\t\t".implode(',',$members)."\n}\n";

        $path = "C:\Users\pc\Desktop\Laravel\objects\hostgroups\hostgroups.txt";

        $file = fopen($path, 'a');
        
        fwrite($file, $define_hostgroup);

        fclose($file);

        return redirect('/configuration/hostgroups');
    }

    public function servicegroups()
    {
        $servicegroups = DB::table('nagios_servicegroups')
            ->join('nagios_servicegroup_members','nagios_servicegroups.servicegroup_id','=','nagios_servicegroup_members.servicegroup_id')
            ->join('nagios_services','nagios_servicegroup_members.service_object_id','=','nagios_services.service_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->select('nagios_servicegroups.alias as servicegroup','nagios_servicegroups.servicegroup_id')
            ->where('nagios_hosts.alias','host')
            ->take(1)
            ->get();

        $members = DB::table('nagios_servicegroups')
            ->join('nagios_servicegroup_members','nagios_servicegroups.servicegroup_id','=','nagios_servicegroup_members.servicegroup_id')
            ->join('nagios_services','nagios_servicegroup_members.service_object_id','=','nagios_services.service_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id')
            ->select('nagios_hosts.alias as type','nagios_hosts.display_name as host_name','nagios_hoststatus.current_state as host_state','nagios_services.display_name as service_name','nagios_servicestatus.current_state as service_state','nagios_servicegroups.alias as servicegroup_name','nagios_servicegroups.servicegroup_id')
            ->where('nagios_hosts.alias','host')
            ->get();

        // dd($servicegroups, $members);

        return view('config.groups.servicegroup', compact('servicegroups','members'));
    }

    public function SGdetails($servicegroup_id)
    {
        $servicegroup = DB::table('nagios_servicegroups')
            ->where('servicegroup_id',$servicegroup_id)
            ->get();

        $members = DB::table('nagios_servicegroups')
            ->join('nagios_servicegroup_members','nagios_servicegroups.servicegroup_id','=','nagios_servicegroup_members.servicegroup_id')
            ->join('nagios_services','nagios_servicegroup_members.service_object_id','=','nagios_services.service_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id')
            ->select('nagios_hosts.alias as type','nagios_hosts.display_name as host_name','nagios_hoststatus.current_state as host_state','nagios_services.display_name as service_name','nagios_servicestatus.current_state as service_state','nagios_servicestatus.last_check','nagios_servicestatus.output','nagios_servicegroups.alias as servicegroup_name','nagios_servicegroups.servicegroup_id')
            ->where('nagios_servicegroups.servicegroup_id',$servicegroup_id)
            ->get();

        return view('config.groups.SGdetails', compact('servicegroup','members'));
    }

    public function equipgroups()
    {
        $equipgroups = DB::table('nagios_servicegroups')
            ->join('nagios_servicegroup_members','nagios_servicegroups.servicegroup_id','=','nagios_servicegroup_members.servicegroup_id')
            ->join('nagios_services','nagios_servicegroup_members.service_object_id','=','nagios_services.service_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->select('nagios_servicegroups.alias as servicegroup','nagios_servicegroups.servicegroup_id')
            ->where('nagios_hosts.alias','box')
            ->take(1)
            ->get();

        $members = DB::table('nagios_servicegroups')
            ->join('nagios_servicegroup_members','nagios_servicegroups.servicegroup_id','=','nagios_servicegroup_members.servicegroup_id')
            ->join('nagios_services','nagios_servicegroup_members.service_object_id','=','nagios_services.service_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id')
            ->select('nagios_hosts.alias as type','nagios_hosts.display_name as host_name','nagios_hoststatus.current_state as host_state','nagios_services.display_name as service_name','nagios_servicestatus.current_state as service_state','nagios_servicegroups.alias as servicegroup_name','nagios_servicegroups.servicegroup_id')
            ->where('nagios_hosts.alias','box')
            ->get();

        // dd($servicegroups, $members);

        return view('config.groups.equipgroups', compact('equipgroups','members'));
    }

    public function EGdetails($servicegroup_id)
    {
        $equipgroup = DB::table('nagios_servicegroups')
            ->where('servicegroup_id',$servicegroup_id)
            ->get();

        $members = DB::table('nagios_servicegroups')
            ->join('nagios_servicegroup_members','nagios_servicegroups.servicegroup_id','=','nagios_servicegroup_members.servicegroup_id')
            ->join('nagios_services','nagios_servicegroup_members.service_object_id','=','nagios_services.service_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id')
            ->select('nagios_hosts.alias as type','nagios_hosts.display_name as host_name','nagios_hoststatus.current_state as host_state','nagios_services.display_name as service_name','nagios_servicestatus.current_state as service_state','nagios_servicestatus.last_check','nagios_servicestatus.output','nagios_servicegroups.alias as servicegroup_name','nagios_servicegroups.servicegroup_id')
            ->where('nagios_servicegroups.servicegroup_id',$servicegroup_id)
            ->get();

        return view('config.groups.EGdetails', compact('equipgroup','members'));
    }

}
