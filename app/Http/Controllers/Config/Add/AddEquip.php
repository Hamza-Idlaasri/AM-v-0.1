<?php

namespace App\Http\Controllers\Config\Add;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddEquip extends Controller
{
    public function index()
    {
        $boxs = DB::table('nagios_hosts')
        ->where('alias','box')
        ->select('nagios_hosts.display_name as box_name', 'nagios_hosts.host_object_id as box_id')
        ->get();
        
        return view('config.add.selectBox', compact('boxs'));
    }

    public function manage($box_id)
    {
        $box = DB::table('nagios_hosts')
        ->where('alias', 'box')
        ->where('host_object_id', $box_id)
        ->select('nagios_hosts.display_name as box_name', 'nagios_hosts.host_object_id as box_id')
        ->first();

        $inputs_used = DB::table('nagios_hosts')
        ->where('alias','box')
        ->where('nagios_hosts.host_object_id', $box_id)
        ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
        ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
        ->orderBy('nagios_servicestatus.check_command')
        ->select('nagios_hosts.display_name as box_name','nagios_services.display_name as equip_name','nagios_servicestatus.check_command as input_nbr')
        ->get();

        $inputs_not_used = ['IN1','IN2','IN3','IN4','IN5','IN6','IN7','IN8','IN9','IN10'];

        foreach ($inputs_used as $input) {
        
            if(in_array($input->input_nbr, $inputs_not_used))
                unset($inputs_not_used[array_search($input->input_nbr, $inputs_not_used)]);

        }

        $inputs_not_used = array_values($inputs_not_used);

        return view('config.add.equip', compact('inputs_used', 'inputs_not_used', 'box'));
    }

    public function addEquip(Request $request,$box_id)
    {
        // validation
        $this->validate($request,[

            'equipName.*' => 'required',
            'inputNbr.*' => 'required',
            
        ],[
            
            'equipName.*.required' => 'the equipement name field is empty',
            'inputNbr.*.required' => 'the input number field is empty',
        ]);

        $add_to_box = DB::table('nagios_hosts')
        ->where('nagios_hosts.alias','box')
        ->where('nagios_hosts.host_object_id', $box_id)
        ->select('nagios_hosts.display_name as box_name')
        ->first();


        $equipNames = $request->input('equipName');
        $equiINnbr = $request->input('inputNbr');

        // Define equip
        for ($i=0; $i < sizeof($equipNames); $i++) {

            $define_service = "define service {\n\tuse\t\t\tbox-service\n\thost_name\t\t".$add_to_box->box_name."\n\tservice_description\t".$equipNames[$i]."\n\tcheck_command\t\tIN".$equiINnbr[$i]."\n}\n\n"; 

            $box_dir = "/usr/local/nagios/etc/objects/box/".$add_to_box->box_name."/".$equipNames[$i].".cfg";

            file_put_contents($box_dir, $define_service);

            // Add equip path to nagios.cfg file
            $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/boxs/{$add_to_box->box_name}/{$equipNames[$i]}.cfg";
            file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

        }

        shell_exec('sudo service nagios restart');

        return redirect()->route('monitoring.equipements');
        
    }
}
