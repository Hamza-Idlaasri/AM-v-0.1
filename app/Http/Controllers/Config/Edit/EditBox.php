<?php

namespace App\Http\Controllers\Config\Edit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EditBox extends Controller
{
    public function index($box_id)
    {
        $box = DB::table('nagios_hosts')
        ->where('alias','box')
        ->where('host_id','=',$box_id)
        ->get();

        $parent_boxs = DB::table('nagios_hosts')
        ->join('nagios_host_parenthosts','nagios_hosts.host_id','=','nagios_host_parenthosts.host_id')
        ->select('nagios_hosts.display_name as host_name','nagios_host_parenthosts.*')
        ->where('nagios_host_parenthosts.host_id','=', $box_id)
        ->get();

        $all_boxs = DB::table('nagios_hosts')
        ->where('alias','host')
        ->where('host_id','!=',$box_id)
        ->select('nagios_hosts.display_name as host_name','nagios_hosts.host_object_id')
        ->get();
        
        return view('config.edit.box', compact('box','parent_boxs','all_boxs'));
    }

    public function editBox(Request $request, $box_id)
    {

        // validation
        $this->validate($request,[

            'boxName' => 'required',
            'addressIP' => 'required',
            'normal_interval' => 'required',
            'retry_interval' => 'required',
            'max_attempts' => 'required',
            'notif_interval' => 'required'

        ],[
            'addressIP.required' => 'the IP address field is empty',
        ]);

        $old_box_details = DB::table('nagios_hosts')
            ->where('host_id', $box_id)
            ->get();

        $equips = DB::table('nagios_hosts')
            ->where('host_id', $box_id)
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->select('nagios_services.display_name as equip_name')
            ->get();

        // Parent relationship
        if($request->input('hosts'))
            $define_host = "define host {\n\tuse\t\t\t\t\tlinux-server\n\thost_name\t\t".$request->boxName."\n\talias\t\t\tbox\n\taddress\t\t\t".$request->addressIP."\n\tparents\t\t\t".$request->input('hosts');
        else
            $define_host = "define host {\n\tuse\t\t\t\t\tlinux-server\n\thost_name\t\t\t\t".$request->boxName."\n\talias\t\t\t\t\tbox\n\taddress\t\t\t\t\t".$request->addressIP;

        // Normal Check Interval
        if($old_box_details[0]->check_interval != $request->normal_interval)
            $define_host = $define_host."\n\tcheck_interval\t\t\t\t".$request->normal_interval;
        
        // Retry Check Interval
        if($old_box_details[0]->retry_interval != $request->retry_interval)
            $define_host = $define_host."\n\tretry_interval\t\t\t\t".$request->retry_interval;

        // Max Check Attempts
        if($old_box_details[0]->max_check_attempts != $request->max_attempts)
            $define_host = $define_host."\n\tmax_check_attempts\t\t\t".$request->max_attempts;
        
        // Notification Interval
        if($old_box_details[0]->notification_interval != $request->notif_interval)
            $define_host = $define_host."\n\tnotification_interval\t\t\t".$request->notif_interval;

        $define_host = $define_host."\n}\n\n";

        if($old_box_details[0]->display_name == $request->boxName) {

            $path = "C:\Users\pc\Desktop\Laravel\objects\boxs\\".$request->boxName."\\".$request->boxName.".txt";  
            
            file_put_contents($path, $define_host);

        } else {

            $path = "C:\Users\pc\Desktop\Laravel\objects\boxs\\".$old_box_details[0]->display_name."\\".$old_box_details[0]->display_name.".txt";
            
            file_put_contents($path, $define_host);

            rename("C:\Users\pc\Desktop\Laravel\objects\boxs\\".$old_box_details[0]->display_name."\\".$old_box_details[0]->display_name.".txt", "C:\Users\pc\Desktop\Laravel\objects\boxs\\".$old_box_details[0]->display_name."\\".$request->boxName.".txt");

            rename("C:\Users\pc\Desktop\Laravel\objects\boxs\\".$old_box_details[0]->display_name, "C:\Users\pc\Desktop\Laravel\objects\boxs\\".$request->boxName);

            foreach ($equips as $equip) {
            
                $content = file_get_contents("C:\Users\pc\Desktop\Laravel\objects\boxs\\".$request->boxName."\\".$equip->equip_name.".txt");
            
                $content = str_replace($old_box_details[0]->display_name, $request->boxName, $content);
    
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\boxs\\".$request->boxName."\\".$equip->equip_name.".txt", $content);
    
            }

            // Editing in nagios.cfg file
            $nagios_file_content = file_get_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt");
            $nagios_file_content = str_replace($old_box_details[0]->display_name, $request->boxName, $nagios_file_content);
            file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $nagios_file_content);
        }

        return back();
    }

    public function deleteBox($box_id)
    {
        $box_deleted = DB::table('nagios_hosts')
            ->where('host_id', $box_id)
            ->select('nagios_hosts.display_name')
            ->get();
        
        $path = "C:\Users\pc\Desktop\Laravel\objects\boxs\\".$box_deleted[0]->display_name;

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
            $nagios_file_content = str_replace("cfg_file=C:\Users\pc\Desktop\Laravel\objects\boxs\\{$box_deleted[0]->display_name}\\{$box_deleted[0]->display_name}.txt", '', $nagios_file_content);
            file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $nagios_file_content);

        } else {
            return 'WORNING: No box found';
        }

        return back();
    }
}
