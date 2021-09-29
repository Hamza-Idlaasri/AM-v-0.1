<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use App\Http\Controllers\TopController;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('inc.top', function($view){
            
            $hosts_summary = DB::table('nagios_hoststatus')
            ->join('nagios_hosts','nagios_hoststatus.host_object_id','=','nagios_hosts.host_object_id')
            ->get();

            $services_summary = DB::table('nagios_hosts')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->get();

                    
            // Hosts summary

            $total_hosts = 0;
            $hosts_up = 0;
            $hosts_down = 0;
            $hosts_unreachable = 0;
            
            foreach ($hosts_summary as $host) {        

                if($host->alias == "host")
                {
                
                    switch ($host->current_state) {
                        case 0:
                            $hosts_up++;
                            break;
                        
                        case 1:
                            $hosts_down++;
                            break;
                        
                        case 2:
                            $hosts_unreachable++;
                            break;
                        default:
                            
                            break;
                    }

                    $total_hosts++;
                }

            }

            // Services summary

            $total_services = 0;
            $services_ok = 0;
            $services_warning = 0;
            $services_critical = 0;
            $services_unknown = 0;
            
            // Equipements summary

            $total_equipements = 0;
            $equipements_ok = 0;
            $equipements_warning = 0;
            $equipements_critical = 0;
            $equipements_unknown = 0;
            
            foreach ($services_summary as $service) {
                
                // Servcies :

                if($service->alias == "host")
                {
                
                    switch ($service->current_state) {
                        case 0:
                            $services_ok++;
                            break;
                        
                        case 1:
                            $services_warning++;
                            break;
                        
                        case 2:
                            $services_critical++;
                            break;
                            
                        case 3:
                            $services_unknown++;
                            break;

                        default:
                            
                            break;
                    }

                    $total_services++;
                }

                // Equipement :

                if($service->alias == "box")
                {
                
                    switch ($service->current_state) {
                        case 0:
                            $equipements_ok++;
                            break;
                        
                        case 1:
                            $equipements_warning++;
                            break;
                        
                        case 2:
                            $equipements_critical++;
                            break;
                            
                        case 3:
                            $equipements_unknown++;
                            break;

                        default:
                            
                            break;
                    }

                    $total_equipements++;
                }

            }

            $test = 2;

            $view->with('total_hosts',$total_hosts)
            ->with('total_services',$total_services)
            ->with('total_equipements',$total_equipements)
            ->with('hosts_up',$hosts_up)
            ->with('hosts_down',$hosts_down)
            ->with('hosts_unreachable',$hosts_unreachable)
            ->with('services_ok',$services_ok)
            ->with('services_warning',$services_warning)
            ->with('services_critical',$services_critical)
            ->with('services_unknown',$services_unknown)
            ->with('equipements_ok',$equipements_ok)
            ->with('equipements_warning',$equipements_warning)
            ->with('equipements_critical',$equipements_critical)
            ->with('equipements_unknown',$equipements_unknown)
            ->with('test',$test);

        });

        view()->composer('inc.sidebar', function($view){

            $day_befor = date('Y-m-d H:i:s', strtotime('-1 day'));

            $notifs = DB::table('nagios_notifications')
                ->where('start_time','>=', $day_befor)
                ->get();

            
            $i = 0;

            foreach ($notifs as $notif) {
                $i++;
            }

            $view->with('total_notifs', $i);

        });
    }
}
