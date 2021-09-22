<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\EquipMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class SendEquipEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notif:equip';

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
        $equips = DB::table('nagios_notifications')
            ->join('nagios_services','nagios_services.service_object_id','=','nagios_notifications.object_id')
            ->join('nagios_hosts','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->where('nagios_hosts.alias','box')
            ->select('nagios_services.display_name as equip_name','nagios_hosts.display_name as box_name','nagios_notifications.*')
            ->orderByDesc('start_time')
            ->get();
            
        $equips_notified = [];

        foreach ($equips as $equip) {
        
            // Get current time
            date_default_timezone_set('Africa/Casablanca');
            $current_time = date('y-m-d H:i:s', time());

            // Difference CT and Host start time
            $diff = abs(strtotime($equip->start_time) - strtotime($current_time)); 

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

            if ($years == 0 && $months == 0 && $days == 0 && $hours == 0 && $minutes <= 30) {
                
                array_push($equips_notified, $equip);

            }
        
        }

        if(sizeof($equips_notified))
        {
            $equips_notified = (object) $equips_notified;
            Mail::to('vatoch1720@gmail.com')->send(new EquipMail($equips_notified));
            return new EquipMail($equips_notified);

        }

        return 0;
    }
}
