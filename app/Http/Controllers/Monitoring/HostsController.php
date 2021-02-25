<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HostsController extends Controller
{

    public function show()
    {
        $search = request()->query('search');

        if($search)
        {
            $hosts = DB::table('nagios_hosts')
            ->where('alias','host')
            ->where('nagios_hosts.display_name','like', '%'.$search.'%')
            ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id')
            ->paginate(10);

        } else {

            $hosts = DB::table('nagios_hosts')
            ->where('alias','host')
            ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id')
            ->paginate(10);
        }

        

        return view('monitoring.hosts',compact('hosts'));
    }

    public function problems()
    {
        $search = request()->query('search');

        if($search)
        {
            $host_problems = DB::table('nagios_hosts')
            ->where('alias','host')
            ->where('nagios_hosts.display_name','like', '%'.$search.'%')
            ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id')
            ->where('current_state','<>','0')
            ->paginate(10);

        } else {

            $host_problems = DB::table('nagios_hosts')
            ->where('alias','host')
            ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id')
            ->where('current_state','<>','0')
            ->paginate(10);

        }
        

        return view('problems.hosts',compact('host_problems'));
    }

    public function historic()
    {
        $search = request()->query('search');

        if($search)
        {
            $hosts_history = DB::table('nagios_hosts')
            ->where('alias','host')
            ->where('nagios_hosts.display_name','like', '%'.$search.'%')
            ->join('nagios_statehistory','nagios_hosts.host_object_id','=','nagios_statehistory.object_id')
            ->paginate(10);

        }else{

            $hosts_history = DB::table('nagios_hosts')
            ->where('alias','host')
            ->join('nagios_statehistory','nagios_hosts.host_object_id','=','nagios_statehistory.object_id')
            ->paginate(10);
            
        }
        
        return view('historique.hosts',compact('hosts_history'));
    }
    
    public function statistic()
    {
        $hosts = DB::table('nagios_hosts')
            ->where('alias','host')
            ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id')
            ->get();

        $hosts_up = 0;
        $hosts_down = 0;
        $hosts_unreachable = 0;

        foreach ($hosts as $host) {        
    
            
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
        
                 
        
        }

        $Piechart = app()->chartjs
        ->name('hosts')
        ->type('pie')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['Up', 'Down', 'Unreachable'])
        ->datasets([
            [
             
                'backgroundColor' => ['#6ccf01', 'crimson', '#C200FF'],
                'hoverBackgroundColor' => ['#519b01', 'red', 'rgb(151, 4, 230)'],
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


        // Barchart : 

        $Barchart = app()->chartjs
         ->name('barChartTest')
         ->type('bar')
         ->size(['width' => 400, 'height' => 200])
         ->labels(['Up','Down','Unreachable'])
         ->datasets([
             [
                //  "label" => ['dataset'],
                'backgroundColor' => ['#6ccf01','crimson','#C200FF'],
                'data' => [$hosts_up, $hosts_down, $hosts_unreachable],
              
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

         
         ]);

        // Line chart

        $lineChart = app()->chartjs
        ->name('lineChartTest')
        ->type('line')
        ->size(['width' => 400, 'height' => 120])
        ->labels(['January', 'February', 'March', 'April', 'May', 'June', 'July'])
        ->datasets([
            [
                "label" => "My First dataset",
                // 'backgroundColor' => "rgba(38, 185, 154, 0.31)",
                'borderColor' => "rgba(38, 185, 154, 0.7)",
                "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                'data' => [0,1,2,3,1,0,2],
                'lineTension' => 0,
                
            ],
            [
                "label" => "My Second dataset",
                // 'backgroundColor' => "rgba(38, 185, 154, 0.31)",
                'borderColor' => "rgba(38, 185, 154, 0.7)",
                "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                'data' => [1, 3, 4, 4, 5, 2, 0],
                'lineTension' => 0,
                
            ]
        ])
        ->options([]);

        return view('statistique.hosts',compact('Piechart','Barchart'));
    }
}
