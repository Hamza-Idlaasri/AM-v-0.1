<?php

namespace App\Http\Controllers\Config\Edit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditHost extends Controller
{
    public function index($host_id)
    {
        $host = DB::table('nagios_hosts')
        ->where('alias','host')
        ->where('host_id','=',$host_id)
        ->get();

        $parent_hosts = DB::table('nagios_hosts')
        ->join('nagios_host_parenthosts','nagios_hosts.host_id','=','nagios_host_parenthosts.host_id')
        ->select('nagios_hosts.display_name as host_name','nagios_host_parenthosts.*')
        ->where('nagios_host_parenthosts.host_id','=', $host_id)
        ->get();

        $all_hosts = DB::table('nagios_hosts')
        ->where('alias','host')
        ->where('host_id','!=',$host_id)
        ->select('nagios_hosts.display_name as host_name','nagios_hosts.host_object_id')
        ->get();
        
        return view('config.edit.host', compact('host','parent_hosts','all_hosts'));
    }

    public function editHost(Request $request, $host_id)
    {
        
        // validation
        $this->validate($request,[

            'hostName' => 'required',
            'addressIP' => 'required',
            'check_interval' => 'required',
            'retry_interval' => 'required',
            'max_attempts' => 'required',
            'notif_interval' => 'required'

        ],[
            'addressIP.required' => 'the IP address field is empty',
        ]);
        
        $old_host_details = DB::table('nagios_hosts')
            ->where('host_id', $host_id)
            ->get();
        
        $services = DB::table('nagios_hosts')
            ->where('host_id', $host_id)
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->select('nagios_services.display_name as service_name')
            ->get();

        // Parent relationship
        if($request->input('hosts'))
            $define_host = "define host {\n\tuse\t\t\t\t\tlinux-server\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n\tparents\t\t\t".$request->input('hosts');
        else
            $define_host = "define host {\n\tuse\t\t\t\t\tlinux-server\n\thost_name\t\t\t\t".$request->hostName."\n\talias\t\t\t\t\thost\n\taddress\t\t\t\t\t".$request->addressIP;

        // Normal Check Interval
        if($old_host_details[0]->check_interval != $request->check_interval)
            $define_host = $define_host."\n\tcheck_interval\t\t\t\t".$request->check_interval;
        
        // Retry Check Interval
        if($old_host_details[0]->retry_interval != $request->retry_interval)
            $define_host = $define_host."\n\tretry_interval\t\t\t\t".$request->retry_interval;

        // Max Check Attempts
        if($old_host_details[0]->max_check_attempts != $request->max_attempts)
            $define_host = $define_host."\n\tmax_check_attempts\t\t\t".$request->max_attempts;
        
        // Notification Interval
        if($old_host_details[0]->notification_interval != $request->notif_interval)
            $define_host = $define_host."\n\tnotification_interval\t\t\t".$request->notif_interval;

        $define_host = $define_host."\n}\n\n";

        if($old_host_details[0]->display_name == $request->hostName) {

            $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->hostName."\\".$request->hostName.".txt";  
            
            $file = fopen($path, 'w');

            fwrite($file, $define_host);
    
            fclose($file);

        } else {

            $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$old_host_details[0]->display_name."\\".$old_host_details[0]->display_name.".txt";
            
            file_put_contents($path, $define_host);

            rename("C:\Users\pc\Desktop\Laravel\objects\hosts\\".$old_host_details[0]->display_name."\\".$old_host_details[0]->display_name.".txt", "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$old_host_details[0]->display_name."\\".$request->hostName.".txt");

            rename("C:\Users\pc\Desktop\Laravel\objects\hosts\\".$old_host_details[0]->display_name, "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->hostName);
          
            foreach ($services as $service) {
            
                $content = file_get_contents("C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->hostName."\\".$service->service_name.".txt");
            
                $content = str_replace($old_host_details[0]->display_name, $request->hostName, $content);
    
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->hostName."\\".$service->service_name.".txt", $content);
    
            }

            // Editing in nagios.cfg file
            $nagios_file_content = file_get_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt");
            $nagios_file_content = str_replace($old_host_details[0]->display_name, $request->hostName, $nagios_file_content);
            file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $nagios_file_content);

        }

        return back();

    }

    public function deleteHost($host_id)
    {
        $host_deleted = DB::table('nagios_hosts')
            ->where('host_id', $host_id)
            ->select('nagios_hosts.display_name')
            ->get();
        
        $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$host_deleted[0]->display_name;

        if(is_dir($path))
        {
            $objects = scandir($path);

            foreach ($objects as $object) { 
                if ($object != "." && $object != "..") { 
                    unlink($path. DIRECTORY_SEPARATOR .$object); 
                } 
            }

            rmdir($path);

            // Editing in nagios.cfg file
            $nagios_file_content = file_get_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt");
            $nagios_file_content = str_replace("cfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$host_deleted[0]->display_name}\\{$host_deleted[0]->display_name}.txt", '', $nagios_file_content);
            file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $nagios_file_content);

        } else {
            return 'WORNING: No host found';
        }

        return back();
    }

}
