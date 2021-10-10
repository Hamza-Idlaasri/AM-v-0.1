<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\BoxMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Nexmo\Laravel\Facade\Nexmo;

class SendBoxEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notif:box';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications about boxes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        $boxs = DB::table('nagios_notifications')
            ->join('nagios_hosts','nagios_hosts.host_object_id','=','nagios_notifications.object_id')
            ->where('nagios_hosts.alias','box')
            ->select('nagios_hosts.display_name as box_name','nagios_hosts.*','nagios_notifications.*')
            ->orderByDesc('start_time')
            ->get();
            
        $boxs_notified = [];

        foreach ($boxs as $box) {
        
            // Get current time
            date_default_timezone_set('Africa/Casablanca');
            $current_time = date('y-m-d H:i:s', time());

            // Difference CT and Host start time
            $diff = abs(strtotime($box->start_time) - strtotime($current_time)); 

            // To get the year divide the resultant date into
            // total seconds in a year (365*60*60*24)
            $years = floor($diff / (365*60*60*24)); 
            
            
            // To get the month, subtract it with years and
            // divide the resultant date into
            // total seconds in a month (30*60*60*24)
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
            
            
            // To get the day, subtract it with years and 
            // months and divide the resultant date into
            // total seconds in a days (60*60*24)
            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24) / (60*60*24));
            
            
            // To get the hour, subtract it with years, 
            // months & seconds and divide the resultant
            // date into total seconds in a hours (60*60)
            $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60)); 
            
            
            // To get the minutes, subtract it with years,
            // months, seconds and hours and divide the 
            // resultant date into total seconds i.e. 60
            $minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
            
            
            // To get the minutes, subtract it with years,
            // months, seconds, hours and minutes 
            $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60)); 

            if ($years == 0 && $months == 0 && $days == 0 && $hours == 0 && $minutes <= 5) {
                
                array_push($boxs_notified, $box);

            }

        }

        
        if(sizeof($boxs_notified))
        {
            $boxs_notified = (object) $boxs_notified;
            
            $users = User::all();

            foreach ($users as $user) {
                
                if ($user->notified) {

                    Mail::to($user->email)->send(new BoxMail($boxs_notified));
                    $send = new BoxMail($boxs_notified);

                    foreach ($boxs_notified as $box) {

                        switch ($box->state) {
                            case 0:
                                $box->state = 'Ok';
                                break;
                            case 1:
                                $box->state = 'Down';
                                break;
                            case 2:
                                $box->state = 'Unreachable';
                                break;
                        }

                        switch($box->notification_reason)
                        {
                            case 0: 
                                $box->notification_reason = 'Normal notification';  
                                break; 
                            case 1: 
                                $box->notification_reason = 'Problem acknowledgement';
                                break; 
                            case 2: 
                                $box->notification_reason = 'Flapping started';
                                break; 
                            case 3: 
                                $box->notification_reason = 'Flapping stopped'; 
                                break; 
                            case 4: 
                                $box->notification_reason = 'Flapping was disabled';
                                break; 
                            case 5: 
                                $box->notification_reason = 'Downtime started';
                                break; 
                            case 6: 
                                $box->notification_reason = 'Downtime ended';
                                break; 
                            case 7: 
                                $box->notification_reason = 'Downtime was cancelled'; 
                                break; 
                            
                        } 


                        Nexmo::message()->send([
                            'to' => '212659846118',
                            'from' => '212676268079',
                            'text' => 'Box name : '.$box->box_name.'Address IP : '.$box->address.'State : '.$box->state.'Date/Time : '.$box->start_time.'Info : '.$box->long_output.'Notif Type : '.$box->notification_reason,
                        ]);
                    }
                    
                }
            }

        }

        return 0;

    }
}
