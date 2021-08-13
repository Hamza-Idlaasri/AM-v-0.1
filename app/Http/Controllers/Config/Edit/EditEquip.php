<?php

namespace App\Http\Controllers\Config\Edit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditEquip extends Controller
{
    public function index($equip_id)
    {
        $equip = DB::table('nagios_services')
        ->where('service_id', $equip_id)
        ->get();

        return view('config.edit.equip', compact('equip'));
    }

    public function editEquip(Request $request, $equip_id)
    {
        // validation
        $this->validate($request,[
         
            'equipName' => 'required',
            'check_interval' => 'required',
            'retry_interval' => 'required',
            'max_attempts' => 'required',
            'notif_interval' => 'required',
            // 'inputNbr' => 'required',
        ]);

        $old_equip_details = DB::table('nagios_services')
            ->where('service_id', $equip_id)
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->select('nagios_hosts.display_name as host_name','nagios_services.display_name as service_name','nagios_services.*','nagios_servicestatus.check_command')
            ->get();

        $define_service = "define service {\n\tuse\t\t\t\t\tbox-service\n\thost_name\t\t\t\t".$old_equip_details[0]->host_name."\n\tservice_description\t\t\t".$request->equipName."\n\tcheck_command\t\t\t\t".$old_equip_details[0]->check_command;

        // Normal Check Interval
        if($old_equip_details[0]->check_interval != $request->check_interval)
            $define_service = $define_service."\n\tcheck_interval\t\t\t\t".$request->check_interval;
        
        // Retry Check Interval
        if($old_equip_details[0]->retry_interval != $request->retry_interval)
            $define_service = $define_service."\n\tretry_interval\t\t\t\t".$request->retry_interval;

        // Max Check Attempts
        if($old_equip_details[0]->max_check_attempts != $request->max_attempts)
            $define_service = $define_service."\n\tmax_check_attempts\t\t\t".$request->max_attempts;
        
        // Notification Interval
        if($old_equip_details[0]->notification_interval != $request->notif_interval)
            $define_service = $define_service."\n\tnotification_interval\t\t\t".$request->notif_interval;

        $define_service = $define_service."\n}\n\n";

        if($old_equip_details[0]->service_name == $request->equipName)
        {
            $path = "C:\Users\pc\Desktop\Laravel\objects\boxs\\".$old_equip_details[0]->host_name."\\".$request->equipName.".txt";

            $file = fopen($path, 'w');

            fwrite($file, $define_service);

            fclose($file);

        } else {

            $path = "C:\Users\pc\Desktop\Laravel\objects\boxs\\".$old_equip_details[0]->host_name."\\".$old_equip_details[0]->service_name.".txt";

            $file = fopen($path, 'w');

            fwrite($file, $define_service);

            fclose($file);

            rename("C:\Users\pc\Desktop\Laravel\objects\boxs\\".$old_equip_details[0]->host_name."\\".$old_equip_details[0]->service_name.".txt", "C:\Users\pc\Desktop\Laravel\objects\boxs\\".$old_equip_details[0]->host_name."\\".$request->equipName.".txt");

            // Editing in nagios.cfg file
            $nagios_file_content = file_get_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt");
            $nagios_file_content = str_replace($old_equip_details[0]->display_name, $request->equipName, $nagios_file_content);
            file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $nagios_file_content);
        }

        return back();
    }

    public function deleteEquip($equip_id)
    {
        $equip_deleted = DB::table('nagios_services')
            ->where('service_id',$equip_id)
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->select('nagios_hosts.display_name as box_name','nagios_services.display_name as equip_name')
            ->get();

        $path = "C:\Users\pc\Desktop\Laravel\objects\boxs\\".$equip_deleted[0]->box_name."\\".$equip_deleted[0]->equip_name.".txt";

        if (is_file($path)) 
        {
            unlink($path);

            // Editing in nagios.cfg file
            $nagios_file_content = file_get_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt");
            $nagios_file_content = str_replace("cfg_file=C:\Users\pc\Desktop\Laravel\objects\boxs\\{$equip_deleted[0]->box_name}/{$equip_deleted[0]->equip_name}.cfg", '', $nagios_file_content);
            file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $nagios_file_content);
            
        } else
            return 'WORNING: No equipment found';
        

        return back();
    }
}
