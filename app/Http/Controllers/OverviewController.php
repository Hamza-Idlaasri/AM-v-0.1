<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OverviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    public function overview()
    {
        $hosts_summary = DB::table('nagios_hoststatus')
            ->join('nagios_hosts','nagios_hoststatus.host_object_id','=','nagios_hosts.host_object_id')
            ->get();

        $services_summary = DB::table('nagios_hosts')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->get();

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
        
        $hosts = app()->chartjs
        ->name('hosts')
        ->type('pie')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['Up', 'Down', 'Unreachable'])
        ->datasets([
            [
                
                'backgroundColor' => ['#6ccf01', 'crimson', '#C200FF'],
                // 'hoverBackgroundColor' => ['#519b01', 'red', 'rgb(151, 4, 230)'],
                'data' => [$hosts_up, $hosts_down, $hosts_unreachable],
                
            ]
        ])
        ->options([
            // 'title'=> [
            //     'display' => true,
            //     'text' => 'Porcentage des alarmes Host',
            //     'position' => 'top',
            // ],
            'legend' => [
                'position' => 'right',
                'labels' => [
                    'boxWidth' => 15,
                ]
            ],
            
          
            'plugins' => [
                'labels' => [
                    'fontColor' => '#fff',
                    'fontSize' => 13,
                ]
            ]
            
        
        ]);

        $equipements = app()->chartjs
        ->name('equipements')
        ->type('pie')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['Ok', 'Warning', 'Critical', 'Unknown'])
        ->datasets([
            [
                
                'backgroundColor' => ['#6ccf01', 'yellow', 'crimson', '#C200FF'],
                // 'hoverBackgroundColor' => ['#519b01', 'rgb(255, 208, 0)', 'red', 'rgb(151, 4, 230)'],
                'data' => [$equipements_ok, $equipements_warning, $equipements_critical, $equipements_unknown],
                
            ]
        ])
        ->options([
            // 'title'=> [
            //     'display' => true,
            //     'text' => 'Porcentage des alarmes Equipements',
            //     'position' => 'top',
            // ],
            'legend' => [
                'position' => 'right',
                'labels' => [
                    'boxWidth' => 15,
                ]
            ],
            
            
            'plugins' => [
                'labels' => [
                    'fontColor' => ['#fff','#212529','#fff','#fff'],
                    'fontSize' => 13,
                ]
            ]

            
            
        ]);

        $services = app()->chartjs
        ->name('services')
        ->type('pie')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['Ok', 'Warning', 'Critical', 'Unknown'])
        ->datasets([
            [
                
                'backgroundColor' => ['#6ccf01', 'yellow', 'crimson', '#C200FF'],
                // 'hoverBackgroundColor' => ['#519b01', 'rgb(255, 208, 0)', 'red', 'rgb(151, 4, 230)'],
                'data' => [$services_ok, $services_warning, $services_critical, $services_unknown],
                
            ]
        ])
        ->options([
            // 'title'=> [
            //     'display' => true,
            //     'text' => 'Porcentage des alarmes Services',
            //     'position' => 'top',
            // ],
            'legend' => [
                'position' => 'right',
                'labels' => [
                    'boxWidth' => 15,
                ]
            ],
            
            
            'plugins' => [
                'labels' => [
                    'fontColor' => ['#fff','#212529','#fff','#fff'],
                    'fontSize' => 13,
                ]
            ]
                
            
        ]);

        $contacts = app()->chartjs
        ->name('contacts')
        ->type('pie')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['Ok', 'Warning', 'Critical', 'Unknown'])
        ->datasets([
            [
                
                'backgroundColor' => ['#6ccf01', 'yellow', 'crimson', '#C200FF'],
                // 'hoverBackgroundColor' => ['#519b01', 'rgb(255, 208, 0)', 'red', 'rgb(151, 4, 230)'],
                'data' => [0,0 , 0, 0],
                
            ]
        ])
        ->options([
            // 'title'=> [
            //     'display' => true,
            //     'text' => 'Porcentage des alarmes Contacts',
            //     'position' => 'top',
            // ],
            'legend' => [
                'position' => 'right',
                'labels' => [
                    'boxWidth' => 15,
                ]
            ],
            
            'animation' => [
                'duration' => 1
            ],
            'plugins' => [
                'labels' => [
                    'fontColor' => ['#fff','#212529','#fff','#fff'],
                    'fontSize' => 13,
                ]
            ]
                
            
        ]);

        return view('overview',compact('hosts','services','equipements','contacts'));
       
    }
}
