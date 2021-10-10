<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\ServiceMail;
use App\Mail\BoxMail;
use App\Mail\HostMail;
use App\Mail\EquipMail;


class TestController extends Controller
{
    public function index()
    {
        $boxs = DB::table('nagios_notifications')
            ->join('nagios_hosts','nagios_hosts.host_object_id','=','nagios_notifications.object_id')
            ->where('nagios_hosts.alias','box')
            ->select('nagios_hosts.display_name as box_name','nagios_hosts.*','nagios_notifications.*')
            ->orderByDesc('start_time')
            ->take(1)->get();

        
        $hosts = DB::table('nagios_notifications')
            ->join('nagios_hosts','nagios_hosts.host_object_id','=','nagios_notifications.object_id')
            ->where('nagios_hosts.alias','host')
            ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_notifications.*')
            ->orderByDesc('start_time')
            ->take(1)->get();

        $equips = DB::table('nagios_notifications')
            ->join('nagios_services','nagios_services.service_object_id','=','nagios_notifications.object_id')
            ->join('nagios_hosts','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->where('nagios_hosts.alias','box')
            ->select('nagios_services.display_name as equip_name','nagios_hosts.display_name as box_name','nagios_notifications.*')
            ->orderByDesc('start_time')
            ->take(1)->get();

        $services = DB::table('nagios_notifications')
            ->join('nagios_services','nagios_services.service_object_id','=','nagios_notifications.object_id')
            ->join('nagios_hosts','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->where('nagios_hosts.alias','host')
            ->select('nagios_services.display_name as service_name','nagios_hosts.display_name as host_name','nagios_services.*','nagios_notifications.*')
            ->orderByDesc('start_time')
            ->take(1)->get();

       
        if(sizeof($hosts))
        {
            // $services = (object) $services;

            $users = User::all();

            foreach ($users as $user) {
                
                if ($user->notified) {
                    
                    Mail::to($user->email)->send(new HostMail($hosts));
                    $send = new HostMail($hosts);
                }
            }

        }
        
        if(sizeof($services))
        {
            $services = (object) $services;

            $users = User::all();

            foreach ($users as $user) {
                
                if ($user->notified) {
                    
                    Mail::to($user->email)->send(new ServiceMail($services));
                    $send = new ServiceMail($services);
                }
            }

        }

        if(sizeof($boxs))
        {
            // $boxs = (object) $boxs;

            $users = User::all();

            foreach ($users as $user) {
                
                if ($user->notified) {
                    
                    Mail::to($user->email)->send(new BoxMail($boxs));
                    $send = new BoxMail($boxs);
                }
            }

        }

        if(sizeof($equips))
        {
            // $equip = (object) $equip;

            $users = User::all();

            foreach ($users as $user) {
                
                if ($user->notified) {
                    
                    Mail::to($user->email)->send(new EquipMail($equips));
                    $send = new EquipMail($equips);
                }
            }

        }

        return 'Email sent';
    }
}
