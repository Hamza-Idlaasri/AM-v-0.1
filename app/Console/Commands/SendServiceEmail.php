<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\ServiceMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SendServiceEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notif:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $services = DB::table('nagios_notifications')
            ->join('nagios_services','nagios_services.service_object_id','=','nagios_notifications.object_id')
            ->join('nagios_hosts','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->where('nagios_hosts.alias','host')
            ->select('nagios_services.display_name as service_name','nagios_hosts.display_name as host_name','nagios_services.*','nagios_notifications.*')
            ->orderByDesc('start_time')
            ->get();
            
        $services_notified = [];

        foreach ($services as $service) {
        
            // Get current time
            date_default_timezone_set('Africa/Casablanca');
            $current_time = date('y-m-d H:i:s', time());

            // Difference CT and Host start time
            $diff = abs(strtotime($service->start_time) - strtotime($current_time)); 

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
                
                array_push($services_notified, $service);

            }
        
        }

        if(sizeof($services_notified))
        {
            $services_notified = (object) $services_notified;

            $users = User::all();

            foreach ($users as $user) {
                
                if ($user->notified) {
                    
                    Mail::to($user->email)->send(new ServiceMail($services_notified));
                    $send = new ServiceMail($services_notified);
                }
            }

        }

        return 0;
    }
}
