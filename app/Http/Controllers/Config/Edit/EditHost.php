<?php

namespace App\Http\Controllers\Config\Edit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditHost extends Controller
{
    public function __construct()
    {
        $this->middleware(['agent']);
    }
    
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

            'hostName' => 'required|min:2|max:20|regex:/^[a-zA-Z0-9-_+ ]/',
            'addressIP' => 'required|min:7|max:15|regex:/^[0-9.]/',
            'check_interval' => 'required|min:1|max:100',
            'retry_interval' => 'required|min:1|max:100',
            'max_attempts' => 'required|min:1|max:100',
            'notif_interval' => 'required|min:1|max:1000',

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

        // Check this host
        if($request->query('check'))
            $define_host = $define_host."\n\tactive_checks_enabled\t\t\t".$request->query('check');
        
        // Enable notifications
        if($request->query('active_notif'))
            $define_host = $define_host."\n\tnotifications_enabled\t\t\t".$request->query('active_notif');


        $define_host = $define_host."\n}\n\n";

        if($old_host_details[0]->display_name == $request->hostName) {

            $path = "/usr/local/nagios/etc/objects/hosts/".$request->hostName."/".$request->hostName.".cfg";  
            
            $file = fopen($path, 'w');

            fwrite($file, $define_host);
    
            fclose($file);

        } else {

            $path = "/usr/local/nagios/etc/objects/hosts/".$old_host_details[0]->display_name."/".$old_host_details[0]->display_name.".cfg";
            
            file_put_contents($path, $define_host);

            rename("/usr/local/nagios/etc/objects/hosts/".$old_host_details[0]->display_name."/".$old_host_details[0]->display_name.".cfg", "/usr/local/nagios/etc/objects/hosts/".$old_host_details[0]->display_name."/".$request->hostName.".cfg");

            rename("/usr/local/nagios/etc/objects/hosts/".$old_host_details[0]->display_name, "/usr/local/nagios/etc/objects/hosts/".$request->hostName);
          
            foreach ($services as $service) {
            
                $content = file_get_contents("/usr/local/nagios/etc/objects/hosts/".$request->hostName."/".$service->service_name.".cfg");
            
                $content = str_replace($old_host_details[0]->display_name, $request->hostName, $content);
    
                file_put_contents("/usr/local/nagios/etc/objects/hosts/".$request->hostName."/".$service->service_name.".cfg", $content);
    
            }

            // Editing in nagios.cfg file
            $nagios_file_content = file_get_contents("/usr/local/nagios/etc/nagios.cfg");
            $nagios_file_content = str_replace($old_host_details[0]->display_name, $request->hostName, $nagios_file_content);
            file_put_contents("/usr/local/nagios/etc/nagios.cfg", $nagios_file_content);

        }

        shell_exec('sudo service nagios restart');

        return redirect()->route('configHosts');

    }

    public function deleteHost($host_id)
    {
        $host_deleted = DB::table('nagios_hosts')
            ->where('host_id', $host_id)
            ->select('nagios_hosts.display_name')
            ->get();

        $host_services = DB::table('nagios_hosts')
            ->where('host_id', $host_id)
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->select('nagios_hosts.display_name as host_name','nagios_services.display_name as service_name')
            ->get();

        $path = "/usr/local/nagios/etc/objects/hosts/".$host_deleted[0]->display_name;

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
            $nagios_file_content = file_get_contents("/usr/local/nagios/etc/nagios.cfg");
            $nagios_file_content = str_replace("cfg_file=/usr/local/nagios/etc/objects/hosts/{$host_deleted[0]->display_name}/{$host_deleted[0]->display_name}.cfg", '', $nagios_file_content);
            file_put_contents("/usr/local/nagios/etc/nagios.cfg", $nagios_file_content);

            // Remove host services
            foreach ($host_services as $service) {
                $nagios_file_content = file_get_contents("/usr/local/nagios/etc/nagios.cfg");
                $nagios_file_content = str_replace("cfg_file=/usr/local/nagios/etc/objects/hosts/{$service->host_name}/{$service->service_name}.cfg", '', $nagios_file_content);
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $nagios_file_content);
            }

        } else {
            return 'WORNING: No host found';
        }

        shell_exec('sudo service nagios restart');

        return redirect()->route('configHosts');
    }

}
