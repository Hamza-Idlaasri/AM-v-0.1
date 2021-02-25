<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class EquipementsController extends Controller
{
    public function show()
    {
        $search = request()->query('search');

        if($search)
        {
            $equipements = DB::table('nagios_hosts')
            ->where('alias','box')
            ->where('nagios_hosts.display_name','like', '%'.$search.'%')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_services.display_name as service_name','nagios_services.*','nagios_servicestatus.*')
            ->paginate(10);

        } else {

            $equipements = DB::table('nagios_hosts')
            ->where('alias','box')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_services.display_name as service_name','nagios_services.*','nagios_servicestatus.*')
            ->paginate(10);
        }
        

        
        return view('monitoring.equipements',compact('equipements'));
    }
    
    public function problems()
    {
        $search = request()->query('search');

        if($search)
        {
            $equipement_problems = DB::table('nagios_hosts')
            ->where('alias','box')
            ->where('nagios_hosts.display_name','like', '%'.$search.'%')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->where('current_state','<>','0')
            ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_services.display_name as service_name','nagios_services.*','nagios_servicestatus.*')
            ->paginate(10);

        } else {

            $equipement_problems = DB::table('nagios_hosts')
            ->where('alias','box')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->where('current_state','<>','0')
            ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_services.display_name as service_name','nagios_services.*','nagios_servicestatus.*')
            ->paginate(10);
        }
        
        
        return view('problems.equipements',compact('equipement_problems'));
    }

    public function historic()
    {
       

        $search = request()->query('search');

        if($search)
        {
            $equipements_history = DB::table('nagios_hosts')
            ->where('alias','box')
            ->where('nagios_hosts.display_name','like', '%'.$search.'%')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_statehistory','nagios_services.service_object_id','=','nagios_statehistory.object_id')
            ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_services.display_name as service_name','nagios_services.*','nagios_statehistory.*')
            ->paginate(10);

        } else{

            $equipements_history = DB::table('nagios_hosts')
            ->where('alias','box')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_statehistory','nagios_services.service_object_id','=','nagios_statehistory.object_id')
            ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_services.display_name as service_name','nagios_services.*','nagios_statehistory.*')
            ->paginate(10);

            
        }
        
        return view('historique.equipements',compact('equipements_history'));
    
    }

    public function statistic()
    {
        $equipements = DB::table('nagios_hosts')
            ->where('alias','box')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->get();
        
        $equipements_ok = 0;
        $equipements_warning = 0;
        $equipements_critical = 0;
        $equipements_unknown = 0;

        foreach ($equipements as $equipement) {
            
            // Servcies :
            
                switch ($equipement->current_state) {
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

            
        };

        $Piechart = app()->chartjs
        ->name('equipements')
        ->type('pie')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['Ok', 'Warning', 'Critical', 'Unknown'])
        ->datasets([
            [
                
                'backgroundColor' => ['#6ccf01', 'yellow', 'crimson', '#C200FF'],
                'hoverBackgroundColor' => ['#519b01', 'rgb(255, 208, 0)', 'red', 'rgb(151, 4, 230)'],
                'data' => [$equipements_ok, $equipements_warning, $equipements_critical, $equipements_unknown],
                
            ]
        ])
        ->options([
            // 'title'=> [
            //     'display' => true,
            //     'text' => 'Porcentage des alarmes equipements',
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
            ],

            
                
            
        ]);

        // Barchart : 

        $Barchart = app()->chartjs
        ->name('barChartTest')
        ->type('bar')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['Ok', 'Warning', 'Critical', 'Unknown'])
        ->datasets([
            [
                //  "label" => ['dataset'],
                'backgroundColor' => ['#6ccf01', 'yellow', 'crimson', '#C200FF'],
                'data' =>  [$equipements_ok, $equipements_warning, $equipements_critical, $equipements_unknown],
            
            ],
        
        ])
        ->options([
            'responsive'=> true,
            'scales'=> [
                'yAxes'=> [[
                    'ticks'=> [
                        'beginAtZero'=> true,
                        'stepSize'=> 1,
                        // 'max' => 4,
                    ]
                ]],
                'xAxes'=> [[
                    'barPercentage'=> 0.4
                ]]
            ],

            'layouts'=>[
                'padding'=> [
                    'left'=> 0,
                    'right'=> 0,
                    'top'=> 30,
                    'bottom'=> 0
                ]
            ]

        
        ]);

        return view('statistique.equipements',compact('Piechart','Barchart'));
    }

    public function download()
    {

        // $equipements_history = DB::table('nagios_hosts')
        //     ->where('alias','box')
        //     ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
        //     ->join('nagios_statehistory','nagios_services.service_object_id','=','nagios_statehistory.object_id')
        //     ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_services.display_name as service_name','nagios_services.*','nagios_statehistory.*')
        //     ->get();
        set_time_limit(300);
        $pdf = PDF::loadView('download');
        return $pdf->download('equip.pdf');
        
    }
}
