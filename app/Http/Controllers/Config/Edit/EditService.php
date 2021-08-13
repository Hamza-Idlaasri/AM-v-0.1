<?php

namespace App\Http\Controllers\Config\Edit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditService extends Controller
{
    public function index($service_id)
    {
        $service = DB::table('nagios_services')
        ->where('service_id', $service_id)
        ->get();

        return view('config.edit.service', compact('service'));
    }

    public function editService(Request $request, $service_id)
    {
        // validation
        $this->validate($request,[
         
            'serviceName' => 'required',
            'check_interval' => 'required',
            'retry_interval' => 'required',
            'max_attempts' => 'required',
            'notif_interval' => 'required',
        
        ]);


        $old_service_details = DB::table('nagios_services')
            ->where('service_id', $service_id)
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->select('nagios_hosts.display_name as host_name','nagios_services.display_name as service_name','nagios_services.*','nagios_servicestatus.check_command')
            ->get();

        $define_service = "define service {\n\tuse\t\t\t\t\tgeneric-service\n\thost_name\t\t\t\t".$old_service_details[0]->host_name."\n\tservice_description\t\t\t".$request->serviceName."\n\tcheck_command\t\t\t\t".$old_service_details[0]->check_command;

        // Normal Check Interval
        if($old_service_details[0]->check_interval != $request->check_interval)
            $define_service = $define_service."\n\tcheck_interval\t\t\t\t".$request->check_interval;
        
        // Retry Check Interval
        if($old_service_details[0]->retry_interval != $request->retry_interval)
            $define_service = $define_service."\n\tretry_interval\t\t\t\t".$request->retry_interval;

        // Max Check Attempts
        if($old_service_details[0]->max_check_attempts != $request->max_attempts)
            $define_service = $define_service."\n\tmax_check_attempts\t\t\t".$request->max_attempts;
        
        // Notification Interval
        if($old_service_details[0]->notification_interval != $request->notif_interval)
            $define_service = $define_service."\n\tnotification_interval\t\t\t".$request->notif_interval;

        $define_service = $define_service."\n}\n\n";

        if($old_service_details[0]->service_name == $request->serviceName)
        {
            $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$old_service_details[0]->host_name."\\".$request->serviceName.".txt";

            file_put_contents($path, $define_service);

        } else {

            $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$old_service_details[0]->host_name."\\".$old_service_details[0]->service_name.".txt";

            file_put_contents($path, $define_service);

            rename("C:\Users\pc\Desktop\Laravel\objects\hosts\\".$old_service_details[0]->host_name."\\".$old_service_details[0]->service_name.".txt", "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$old_service_details[0]->host_name."\\".$request->serviceName.".txt");

            // Editing in nagios.cfg file
            $nagios_file_content = file_get_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt");
            $nagios_file_content = str_replace($old_service_details[0]->display_name, $request->serviceName, $nagios_file_content);
            file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $nagios_file_content);
        }

        return back();
    }

    public function deleteService($service_id)
    {
        $service_deleted = DB::table('nagios_services')
            ->where('service_id',$service_id)
            ->join('nagios_hosts','nagios_services.host_object_id','=','nagios_hosts.host_object_id')
            ->select('nagios_hosts.display_name as host_name','nagios_services.display_name as service_name')
            ->get();

        $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$service_deleted[0]->host_name."\\".$service_deleted[0]->service_name.".txt";

        if (is_file($path)) 
        {
            unlink($path);

            // Editing in nagios.cfg file
            $nagios_file_content = file_get_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt");
            $nagios_file_content = str_replace("cfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$service_deleted[0]->host_name}/{$service_deleted[0]->service_name}.cfg", '', $nagios_file_content);
            file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $nagios_file_content);

        } else
            return 'WORNING: No service found';
        
        return back();
    }
}
