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

        $path = "C:\Users\pc\Desktop\Laravel\objects\hostgroups\\".$request->hostgroup_name.".txt";

        // $file = fopen($path, 'a');
        
        // fwrite($file, $define_hostgroup);

        // fclose($file);

        file_put_contents($path, $define_hostgroup);
        $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hostgroups\\".$request->hostgroup_name.".cfg";
        file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

        return redirect('/configuration/hostgroups');
    }

    // Delete Hostgroups
    public function deleteHG($hostgroup_id)
    {
        $HG_deleted = DB::table('nagios_hostgroups')
        ->where('hostgroup_id', $hostgroup_id)
        ->get();

        $path = "C:\Users\pc\Desktop\Laravel\objects\hostgroups\\".$HG_deleted[0]->alias.".txt";

        unlink($path);

        $nagios_file_content = file_get_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt");
        $nagios_file_content = str_replace("cfg_file=C:\Users\pc\Desktop\Laravel\objects\hostgroups\\".$HG_deleted[0]->alias.".cfg", '', $nagios_file_content);
        file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $nagios_file_content);

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

        $path = "C:\Users\pc\Desktop\Laravel\objects\hostgroups";

        $old_hostgroup = DB::table('nagios_hostgroups')
        ->where('hostgroup_id',$hostgroup_id)
        ->get();

        file_put_contents($path."\\".$old_hostgroup[0]->alias.".txt", $define_hostgroup);

        if($old_hostgroup[0]->alias != $request->hostgroup_name)
        {
            $nagios_file_content = file_get_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt");
            $nagios_file_content = str_replace("cfg_file=".$path."\\".$old_hostgroup[0]->alias.".cfg", "cfg_file=".$path."\\".$request->hostgroup_name.".cfg", $nagios_file_content);
            file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $nagios_file_content);

            rename($path."\\".$old_hostgroup[0]->alias.".txt", $path."\\".$request->hostgroup_name.".txt");
        }

        return redirect('/configuration/hostgroups');   
    }

    // Show Servicegroups
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

    // Add servicegroup
    public function addSG()
    {
        $services = DB::table('nagios_hosts')
        ->where('alias','host')
        ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
        ->select('nagios_hosts.display_name as host_name','nagios_services.display_name as service_name','nagios_services.service_object_id')
        ->get();

        return view('config.groups.addSG', compact('services'));
    }

    // Create New ServiceGroup
    public function createSG(Request $request)
    {
        // validation
        $this->validate($request,[
            'servicegroup_name' => 'required',
            'members' => 'required',
        ],[
            'members.required' => 'Please check hosts members for your servicegroup',
        ]);

        $members = [];

        foreach ($request->members as $member) {
            
            $element = DB::table('nagios_services')
            ->where('service_object_id', $member)
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->select('nagios_hosts.display_name as host_name','nagios_services.display_name as service_name')
            ->get();

            array_push($members, $element[0]->host_name);
            array_push($members, $element[0]->service_name);
        }

        $define_servicegroup = "\ndefine servicegroup {\n\tservicegroup_name\t\t".$request->servicegroup_name."\n\talias\t\t\t\t".$request->servicegroup_name."\n\tmembers\t\t\t\t".implode(',',$members)."\n}\n";

        $path = "C:\Users\pc\Desktop\Laravel\objects\servicegroups\\".$request->servicegroup_name.".txt";

        file_put_contents($path, $define_servicegroup);
        $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\servicegroups\\".$request->servicegroup_name.".cfg";
        file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

        return redirect('/configuration/servicegroups');
    }

    // Manage Servicegroup
    public function manageSG($servicegroup_id)
    {
        $servicegroup = DB::table('nagios_servicegroups')
            ->where('servicegroup_id',$servicegroup_id)
            ->select('nagios_servicegroups.alias as servicegroup_name','nagios_servicegroups.servicegroup_id')
            ->first();

        $members = DB::table('nagios_servicegroups')
            ->where('nagios_servicegroups.servicegroup_id',$servicegroup_id)
            ->join('nagios_servicegroup_members','nagios_servicegroups.servicegroup_id','=','nagios_servicegroup_members.servicegroup_id')
            ->join('nagios_services','nagios_servicegroup_members.service_object_id','=','nagios_services.service_object_id')
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->select('nagios_hosts.alias as type','nagios_hosts.display_name as host_name','nagios_services.display_name as service_name','nagios_servicegroups.alias as servicegroup_name','nagios_servicegroups.servicegroup_id','nagios_services.service_object_id')
            ->get();
        
        $services = DB::table('nagios_services')
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->where('nagios_hosts.alias','host')
            ->select('nagios_services.display_name as service_name','nagios_services.service_object_id','nagios_hosts.display_name as host_name')
            ->get();

        $all_members = [];

        foreach ($members as $member) {
            array_push($all_members, $member->service_object_id);
        }

        return view('config.groups.editSG', compact('servicegroup','all_members','services'));  
    }

    // Delete Servicegroup
    public function deleteSG($servicegroup_id)
    {
        $SG_deleted = DB::table('nagios_servicegroups')
        ->where('servicegroup_id', $servicegroup_id)
        ->get();

        $path = "C:\Users\pc\Desktop\Laravel\objects\servicegroups\\".$SG_deleted[0]->alias.".txt";

        unlink($path);

        $nagios_file_content = file_get_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt");
        $nagios_file_content = str_replace("cfg_file=C:\Users\pc\Desktop\Laravel\objects\servicegroups\\".$SG_deleted[0]->alias.".cfg", '', $nagios_file_content);
        file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $nagios_file_content);

        return back();
    }

    // Edit Servicegroup
    public function editSG(Request $request,$servicegroup_id)
    {
        // validation
        $this->validate($request,[
            'servicegroup_name' => 'required',
            'members' => 'required',
        ],[
            'members.required' => 'Please check hosts members for your servicegroup',
        ]);

        $members = [];

        foreach ($request->members as $member) {
            
            $element = DB::table('nagios_services')
            ->where('service_object_id', $member)
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->select('nagios_hosts.display_name as host_name','nagios_services.display_name as service_name')
            ->get();

            array_push($members, $element[0]->host_name);
            array_push($members, $element[0]->service_name);
        }

        $define_servicegroup = "\ndefine servicegroup {\n\tservicegroup_name\t\t".$request->servicegroup_name."\n\talias\t\t\t\t".$request->servicegroup_name."\n\tmembers\t\t\t\t".implode(',',$members)."\n}\n";

        $path = "C:\Users\pc\Desktop\Laravel\objects\servicegroups";

        $old_servicegroup = DB::table('nagios_servicegroups')
        ->where('nagios_servicegroups.servicegroup_id', $servicegroup_id)
        ->select('nagios_servicegroups.alias as servicegroup_name')
        ->get();

        file_put_contents($path."\\".$old_servicegroup[0]->servicegroup_name.'.txt', $define_servicegroup);

        if ($old_servicegroup[0]->servicegroup_name != $request->servicegroup_name) {

            $nagios_file_content = file_get_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt");
            $nagios_file_content = str_replace("cfg_file=".$path."\\".$old_servicegroup[0]->servicegroup_name.".cfg", "cfg_file=".$path."\\".$request->servicegroup_name.".cfg", $nagios_file_content);
            file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $nagios_file_content);

            rename($path."\\".$old_servicegroup[0]->servicegroup_name.'.txt', $path."\\".$request->servicegroup_name.'.txt');
        }

        return redirect('/configuration/servicegroups');
    }

    // Show Equipgroups
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

    // Add equipementgroup
    public function addEG()
    {
        $equips = DB::table('nagios_hosts')
        ->where('alias','box')
        ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
        ->select('nagios_hosts.display_name as box_name','nagios_services.display_name as equip_name','nagios_services.service_object_id')
        ->get();

        return view('config.groups.addEG', compact('equips'));
    }

    // Create New equipementGroup
    public function createEG(Request $request)
    {
        // validation
        $this->validate($request,[
            'equipgroup_name' => 'required',
            'members' => 'required',
        ],[
            'members.required' => 'Please check hosts members for your equipgroup',
        ]);

        $members = [];

        foreach ($request->members as $member) {
            
            $element = DB::table('nagios_services')
            ->where('service_object_id', $member)
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->select('nagios_hosts.display_name as box_name','nagios_services.display_name as equip_name')
            ->get();

            array_push($members, $element[0]->box_name);
            array_push($members, $element[0]->equip_name);
        }

        $define_equipgroup = "\ndefine servicegroup {\n\tservicegroup_name\t\t".$request->equipgroup_name."\n\talias\t\t\t\t".$request->equipgroup_name."\n\tmembers\t\t\t\t".implode(',',$members)."\n}\n";

        $path = "C:\Users\pc\Desktop\Laravel\objects\\equipgroups\\".$request->equipgroup_name.".txt";

        file_put_contents($path, $define_equipgroup);
        $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\\equipgroups\\".$request->equipgroup_name.".cfg";
        file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

        return redirect('/configuration/equipgroups');
    }

    // Manage Equipement
    public function manageEG($equipgroup_id)
    {
        $equipgroup = DB::table('nagios_servicegroups')
            ->where('servicegroup_id',$equipgroup_id)
            ->select('nagios_servicegroups.alias as equipgroup_name','nagios_servicegroups.servicegroup_id')
            ->first();

        $members = DB::table('nagios_servicegroups')
            ->where('nagios_servicegroups.servicegroup_id',$equipgroup_id)
            ->join('nagios_servicegroup_members','nagios_servicegroups.servicegroup_id','=','nagios_servicegroup_members.servicegroup_id')
            ->join('nagios_services','nagios_servicegroup_members.service_object_id','=','nagios_services.service_object_id')
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->select('nagios_hosts.alias as type','nagios_hosts.display_name as host_name','nagios_services.display_name as service_name','nagios_servicegroups.alias as servicegroup_name','nagios_servicegroups.servicegroup_id','nagios_services.service_object_id')
            ->get();
        
        $equips = DB::table('nagios_services')
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->where('nagios_hosts.alias','box')
            ->select('nagios_services.display_name as equip_name','nagios_services.service_object_id','nagios_hosts.display_name as box_name')
            ->get();

        $all_members = [];

        foreach ($members as $member) {
            array_push($all_members, $member->service_object_id);
        }

        return view('config.groups.editEG', compact('equipgroup','all_members','equips'));  
    }

    // Delete Equipmentgroup
    public function deleteEG($equipgroup_id)
    {
        $EG_deleted = DB::table('nagios_servicegroups')
        ->where('servicegroup_id', $equipgroup_id)
        ->get();

        $path = "C:\Users\pc\Desktop\Laravel\objects\\equipgroups\\".$EG_deleted[0]->alias.".txt";

        unlink($path);

        $nagios_file_content = file_get_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt");
        $nagios_file_content = str_replace("cfg_file=C:\Users\pc\Desktop\Laravel\objects\\equipgroups\\".$EG_deleted[0]->alias.".cfg", '', $nagios_file_content);
        file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $nagios_file_content);

        return back();
    }

    // Edit Equipmentgroup
    public function editEG(Request $request,$equipgroup_id)
    {
        // validation
        $this->validate($request,[
            'equipgroup_name' => 'required',
            'members' => 'required',
        ],[
            'members.required' => 'Please check hosts members for your equipgroup',
        ]);

        $members = [];

        foreach ($request->members as $member) {
            
            $element = DB::table('nagios_services')
            ->where('service_object_id', $member)
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->select('nagios_hosts.display_name as box_name','nagios_services.display_name as equip_name')
            ->get();

            array_push($members, $element[0]->box_name);
            array_push($members, $element[0]->equip_name);
        }

        $define_servicegroup = "\ndefine servicegroup {\n\tservicegroup_name\t\t".$request->equipgroup_name."\n\talias\t\t\t\t".$request->equipgroup_name."\n\tmembers\t\t\t\t".implode(',',$members)."\n}\n";

        $path = "C:\Users\pc\Desktop\Laravel\objects\\equipgroups";

        $old_equipgroup = DB::table('nagios_servicegroups')
        ->where('nagios_servicegroups.servicegroup_id', $equipgroup_id)
        ->select('nagios_servicegroups.alias as equipgroup_name')
        ->get();

        file_put_contents($path."\\".$old_equipgroup[0]->equipgroup_name.'.txt', $define_servicegroup);

        if ($old_equipgroup[0]->equipgroup_name != $request->equipgroup_name) {

            $nagios_file_content = file_get_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt");
            $nagios_file_content = str_replace("cfg_file=".$path."\\".$old_equipgroup[0]->equipgroup_name.".cfg", "cfg_file=".$path."\\".$request->equipgroup_name.".cfg", $nagios_file_content);
            file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $nagios_file_content);

            rename($path."\\".$old_equipgroup[0]->equipgroup_name.'.txt', $path."\\".$request->equipgroup_name.'.txt');
        }

        return redirect('/configuration/equipgroups');
    }

}
