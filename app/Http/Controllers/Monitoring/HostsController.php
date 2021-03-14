<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HostsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function show()
    {
        $search = request()->query('search');

        if($search)
        {
            $hosts =$this->getHosts()
                ->where('nagios_hosts.display_name','like', '%'.$search.'%')
                ->paginate(10);

        } else {

            $hosts = $this->getHosts()->paginate(10);
        }
        

        return view('monitoring.hosts',compact('hosts'));
    }

    public function problems()
    {
        $search = request()->query('search');

        if($search)
        {
            $host_problems = $this->getHosts()
                ->where('nagios_hosts.display_name','like', '%'.$search.'%')
                ->where('current_state','<>','0')
                ->paginate(10);

        } else {

            $host_problems = $this->getHosts()
                ->where('current_state','<>','0')
                ->paginate(10);

        }
        

        return view('problems.hosts',compact('host_problems'));
    }

    public function historic()
    {
        // $search = request()->query('search');
        $status = request()->query('status');
        $name = request()->query('name');
        $dateFrom = request()->query('from');
        $dateTo = request()->query('to');

        if($status || $name || $dateFrom || $dateTo)
        {
            // if($search)
            // {
            //     $hosts_history = DB::table('nagios_hosts')
            //         ->where('alias','host')
            //         ->where('nagios_hosts.display_name','like', '%'.$search.'%')
            //         ->join('nagios_statehistory','nagios_hosts.host_object_id','=','nagios_statehistory.object_id')
            //         ->paginate(10);
            // }

            $hosts_history = $this->getHostsHistory();

            if($name)
            {
                $hosts_history = $hosts_history->where('nagios_hosts.display_name', $name);
            }

            if($dateFrom || $dateTo)
            {
                if(!$dateFrom)
                {
                    $dateFrom = json_encode(DB::table('nagios_statehistory')->select('state_time')->first(),true);

                }

                if(!$dateTo)
                    $dateTo = date('Y-m-d');

                $hosts_history = $hosts_history
                    ->where('nagios_statehistory.state_time','>=', $dateFrom)
                    ->where('nagios_statehistory.state_time','<=', $dateTo);

            }
            
            if($status)
            {
                switch ($status) {
                    case 'up':
                        $hosts_history = $hosts_history->where('state','0');
                        break;
                    
                    case 'down':
                        $hosts_history = $hosts_history->where('state','1');
                        break;
                    
                    case 'unknown':
                        $hosts_history = $hosts_history->where('state','2');
                        break;
                    
                }
            }

            $hosts_history = $hosts_history->paginate(10);
            
        } else{

            $hosts_history = $this->getHostsHistory()->paginate(10);
            
        }

        $hosts_name = DB::table('nagios_hosts')
            ->where('alias','host')
            ->select('nagios_hosts.display_name')
            ->get();
        
        return view('historique.hosts',compact('hosts_history','hosts_name'));
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

    public function details($host_id)
    {
        $details = $this->getHosts()
            ->where('host_id','=',$host_id)
            ->get();
        
        return view('details.hostORbox',compact('details'));
        
    }

    public function getHostsHistory()
    {
        return DB::table('nagios_hosts')
            ->where('alias','host')
            ->join('nagios_statehistory','nagios_hosts.host_object_id','=','nagios_statehistory.object_id')
            ->orderByDesc('state_time');
    }

    public function getHosts()
    {
        return DB::table('nagios_hosts')
            ->where('alias','host')
            ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id');
    }

    public function getServices()
    {
        return DB::table('nagios_hosts')
            ->where('alias','host')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_services.display_name as service_name','nagios_services.*','nagios_servicestatus.*')
            ;
    }

    // Configuration :

    public function index()
    {
        $search = request()->query('search');
        
        if ($search) {
            $hosts = $this->getHosts()->where('display_name','like','%'.$search.'%')->paginate(10);
        } else{
            $hosts = $this->getHosts()->paginate(10);
        }
        
        return view('config.hosts', compact('hosts'));
    }

    public function edit($host_object_id)
    {
        $host = $this->getHosts()->where('nagios_hosts.host_object_id', $host_object_id)->get();
        $services = $this->getServices()->where('nagios_services.host_object_id', $host_object_id)->get();

        return view('config.edit.host', compact('host','services'));
    }

    public function delete($host_id)
    {
        // 1: get the host you want delete
        // 2: delete its cfg file from /usr/local/nagios/etc/objects/file_name.cfg
        // 3: remove the line of its declaration from /usr/local/nagios/etc/nagios.cfg (who's like this: "cfg_file=/usr/local/nagios/etc/objects/file_name.cfg")
        // 4: restart nagios by run this command line "service nagios restart"

        return '1: get the host you want delete (this is its id in nagios_hosts table : '.$host_id.')
                <br>
                2: delete its cfg file from /usr/local/nagios/etc/objects/file_name.cfg
                <br>
                3: remove the line of its declaration from /usr/local/nagios/etc/nagios.cfg (who is like this: "cfg_file=/usr/local/nagios/etc/objects/file_name.cfg")
                <br>
                4: restart nagios by run this command line "service nagios restart"';

    }
}
