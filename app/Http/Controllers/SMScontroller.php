<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nexmo\Laravel\Facade\Nexmo;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SMScontroller extends Controller
{
    public function index()
    {
        $users = User::all();

        $services = DB::table('nagios_notifications')
            ->join('nagios_services','nagios_services.service_object_id','=','nagios_notifications.object_id')
            ->join('nagios_hosts','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->where('nagios_hosts.alias','host')
            ->select('nagios_services.display_name as service_name','nagios_hosts.display_name as host_name','nagios_services.*','nagios_notifications.*')
            ->orderByDesc('start_time')
            ->get();

        foreach ($users as $user) {
            
            if ($user->notified) {

                foreach ($services as $service) {

                    switch ($service->state) {
                        case 0:
                            $service->state = 'Ok';
                            break;
                        case 1:
                            $service->state = 'Down';
                            break;
                        case 2:
                            $service->state = 'Unreachable';
                            break;
                    }

                    switch($service->notification_reason)
                    {
                        case 0: 
                            $service->notification_reason = 'Normal notification';  
                            break; 
                        case 1: 
                            $service->notification_reason = 'Problem acknowledgement';
                            break; 
                        case 2: 
                            $service->notification_reason = 'Flapping started';
                            break; 
                        case 3: 
                            $service->notification_reason = 'Flapping stopped'; 
                            break; 
                        case 4: 
                            $service->notification_reason = 'Flapping was disabled';
                            break; 
                        case 5: 
                            $service->notification_reason = 'Downtime started';
                            break; 
                        case 6: 
                            $service->notification_reason = 'Downtime ended';
                            break; 
                        case 7: 
                            $service->notification_reason = 'Downtime was cancelled'; 
                            break; 
                        
                    } 


                    Nexmo::message()->send([
                        'to' => $user->phone_number,
                        'from' => '212676268079',
                        'text' => 'Host name : '.$service->host_name.' | Service : '.$service->service_name.' | State : '.$service->state.' | Date/Time : '.$service->start_time.' | Info : '.$service->long_output.' | Notif Type : '.$service->notification_reason,
                    ]);
                }
                
            }

        }

        /*Nexmo::message()->send([
            'to' => '212659846118',
            'from' => '212676268079',
            'text' => 'Test SMS'
        ]);*/

        return 'Message sent';
    }
}
