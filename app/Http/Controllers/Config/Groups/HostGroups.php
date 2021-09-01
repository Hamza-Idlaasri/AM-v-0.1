<?php

namespace App\Http\Controllers\Config\Groups;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class HostGroups extends Controller
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

    // Add hostgroup
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

        $path = "/usr/local/nagios/etc/objects/hostgroups/".$request->hostgroup_name.".cfg";

        // $file = fopen($path, 'a');
        
        // fwrite($file, $define_hostgroup);

        // fclose($file);

        file_put_contents($path, $define_hostgroup);
        $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hostgroups/".$request->hostgroup_name.".cfg";
        file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

        shell_exec('sudo service nagios restart');

        return redirect('/configuration/hostgroups');
    }

    // Delete Hostgroups
    public function deleteHG($hostgroup_id)
    {
        $HG_deleted = DB::table('nagios_hostgroups')
        ->where('hostgroup_id', $hostgroup_id)
        ->get();

        $path = "/usr/local/nagios/etc/objects/hostgroups/".$HG_deleted[0]->alias.".cfg";

        unlink($path);

        $nagios_file_content = file_get_contents("/usr/local/nagios/etc/nagios.cfg");
        $nagios_file_content = str_replace("cfg_file=/usr/local/nagios/etc/objects/hostgroups/".$HG_deleted[0]->alias.".cfg", '', $nagios_file_content);
        file_put_contents("/usr/local/nagios/etc/nagios.cfg", $nagios_file_content);

        shell_exec('sudo service nagios restart');

        return back();
    }

    // Manage Hostgroups
    public function manageHG($hostgroup_id)
    {
        $hostgroup = DB::table('nagios_hostgroups')
        ->where('nagios_hostgroups.hostgroup_id', $hostgroup_id)
        ->join('nagios_hostgroup_members','nagios_hostgroups.hostgroup_id','=','nagios_hostgroup_members.hostgroup_id')
        ->select('nagios_hostgroups.alias as hostgroup_name','nagios_hostgroups.hostgroup_id')
        ->first();
        
        $members = DB::table('nagios_hostgroups')
        ->where('nagios_hostgroups.hostgroup_id', $hostgroup_id)
        ->join('nagios_hostgroup_members','nagios_hostgroups.hostgroup_id','=','nagios_hostgroup_members.hostgroup_id')
        ->join('nagios_hosts','nagios_hostgroup_members.host_object_id','=','nagios_hosts.host_object_id')
        ->select('nagios_hosts.display_name as host_name','nagios_hosts.host_object_id')
        ->get();

        $hosts = DB::table('nagios_hosts')
        ->where('alias','host')
        ->select('nagios_hosts.host_object_id','nagios_hosts.display_name as host_name')
        ->get();
        
        $all_members = [];

        foreach ($members as $member) {
            array_push($all_members, $member->host_object_id);    
        }

        return view('config.groups.editHG', compact('hostgroup', 'all_members', 'hosts'));
    }

    // Edit Hostgroups
    public function editHG($hostgroup_id, Request $request)
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

        $path = "/usr/local/nagios/etc/objects/hostgroups";

        $old_hostgroup = DB::table('nagios_hostgroups')
        ->where('hostgroup_id',$hostgroup_id)
        ->get();

        file_put_contents($path."/".$old_hostgroup[0]->alias.".cfg", $define_hostgroup);

        if($old_hostgroup[0]->alias != $request->hostgroup_name)
        {
            $nagios_file_content = file_get_contents("/usr/local/nagios/etc/nagios.cfg");
            $nagios_file_content = str_replace("cfg_file=".$path."/".$old_hostgroup[0]->alias.".cfg", "cfg_file=".$path."/".$request->hostgroup_name.".cfg", $nagios_file_content);
            file_put_contents("/usr/local/nagios/etc/nagios.cfg", $nagios_file_content);

            rename($path."/".$old_hostgroup[0]->alias.".cfg", $path."/".$request->hostgroup_name.".cfg");
        }

        shell_exec('sudo service nagios restart');

        return redirect('/configuration/hostgroups');   
    }
}
