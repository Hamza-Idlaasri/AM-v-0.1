<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Boxs extends Controller
{
    public function index()
    {
        $search = request()->query('search');

        if ($search) {
            $boxs = $this->getBoxs()->where('display_name','like','%'.$search.'%')->paginate(10);
        } else {
            $boxs = $this->getBoxs()->paginate(10);
        }
        
        return view('config.boxs', compact('boxs'));
    }

    public function manage()
    {
        $hosts = DB::table('nagios_hosts')
        ->select('display_name as host_name')
        ->get();

        $host_groups = DB::table('nagios_hostgroups')
        ->select('alias')
        ->get();

        return view('config.add.boxs',compact('hosts','host_groups'));    
    }

    public function add(Request $request)
    {
        $equipNames = $request->input('equipName');
        $equiINnbr = $request->input('inputNbr');
               
        // validation
        $this->validate($request,[

            'boxName' => 'required',
            'addressIP' => 'required',
            'equipName.*' => 'required',
            'inputNbr.*' => 'required',
            
        ],[
            'addressIP.required' => 'the IP address field is empty',
            'equipName.*.required' => 'the equipement name field is empty',
            'inputNbr.*.required' => 'the input number field is empty',
        ]);

        $box_dir = "C:\Users\pc\Desktop\Laravel\objects\boxs\\".$request->boxName;

        if(!is_dir($box_dir))
            mkdir($box_dir);
    
        $box_file = fopen($box_dir."\\".$request->boxName.".txt", 'w');    
        
        // Parent relationship
        if($request->input('hosts'))
            $define_host = "define host {\n\tuse\t\t\tlinux-server\n\thost_name\t\t".$request->boxName."\n\talias\t\t\tbox\n\taddress\t\t\t".$request->addressIP."\n\tparents\t\t\t".$request->input('hosts')."\n}\n\n";
        else
            $define_host = "define host {\n\tuse\t\t\tlinux-server\n\thost_name\t\t".$request->boxName."\n\talias\t\t\tbox\n\taddress\t\t\t".$request->addressIP."\n}\n\n";

        fwrite($box_file, $define_host);

        fclose($box_file);

        // if($request->input('hostgroupName') || $request->input('groups'))
        // {
        //     if($request->input('hostgroupName'))    
        //         $define_hostgroup = "define hostgroup{\nhostgroup_name\t".$request->input('hostgroupName')."\nalias\t".$request->input('hostgroupName')."\nmembers\t".$request->boxName."\n}\n\n";
        //     else if($request->input('groups'))
        //         $define_hostgroup = "define hostgroup{\nhostgroup_name\t".$request->input('groups')."\nalias\t".$request->input('groups')."\nmembers\t".$request->boxName."\n}\n\n";
            
        //     fwrite($file, $define_hostgroup);
        // }

        // Define services
        for ($i=0; $i < sizeof($equipNames); $i++) {

            $define_service = "define service {\n\tuse\t\t\tbox-service\n\thost_name\t\t".$request->boxName."\n\tservice_description\t".$equipNames[$i]."\n\tcheck_command\t\tIN".$equiINnbr[$i]."\n}\n\n"; 
 
            $equip_file = fopen($box_dir."\\".$equipNames[$i].".txt", "w");
 
            fwrite($equip_file, $define_service);
            
            fclose($equip_file);

        }

        return redirect()->route('configBoxs');
    }

    public function getBoxs()
    {
        return DB::table('nagios_hosts')
        ->where('alias','box')
        ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id');
    }
}
