<?php

namespace App\Http\Controllers\Config\Groups;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EquipGroups extends Controller
{
    public function __construct()
    {
        $this->middleware(['agent']);
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
