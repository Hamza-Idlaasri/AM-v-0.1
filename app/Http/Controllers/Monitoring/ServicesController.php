<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicesController extends Controller
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
            $services = $this->getServices()
            ->where('nagios_hosts.display_name','like', '%'.$search.'%')
            ->paginate(10);

        } else {

            $services = $this->getServices()->paginate(10);
        }
       
        
        return view('monitoring.services',compact('services'));

    }

    public function problems()
    {
        $search = request()->query('search');

        if($search)
        {
            $service_problems = $this->getServcies()
            ->where('nagios_hosts.display_name','like', '%'.$search.'%')
            ->where('current_state','<>','0')
            ->paginate(10);

        } else {

            $service_problems = $this->getServices()
            ->where('current_state','<>','0')
            ->paginate(10);

        }
        
        
        return view('problems.services',compact('service_problems'));

    }

    public function historic()
    {
        $search = request()->query('search');
        $status = request()->query('status');
        $name = request()->query('name');
        $dateFrom = request()->query('from');
        $dateTo = request()->query('to');

        if($status || $name || $dateFrom || $dateTo)
        {
            $services_history = $this->getServicesHistory();

            if($name)
            {
                $services_history = $services_history->where('nagios_services.display_name', $name);
            }

            if($dateFrom || $dateTo)
            {
                if(!$dateFrom)
                {
                    $dateFrom = json_encode(DB::table('nagios_statehistory')->select('state_time')->first(),true);

                }


                if(!$dateTo)
                    $dateTo = date('Y-m-d');

                $services_history = $services_history
                    ->where('nagios_statehistory.state_time','>=', $dateFrom)
                    ->where('nagios_statehistory.state_time','<=', $dateTo);
            }

            if($status)
            {
                switch ($status) {
                    case 'ok':
                        $services_history = $services_history->where('state','0');
                        break;
                    
                    case 'warning':
                        $services_history = $services_history->where('state','1');
                        break;
                    
                    case 'critical':
                        $services_history = $services_history->where('state','2');
                        break;
                    
                    case 'unreachable':
                        $services_history = $services_history->where('state','3');
                        break;
                    
                }
            }

            $services_history = $services_history->paginate(10);

        } else{

            $services_history = $this->getServicesHistory()->paginate(10);

        }
        
        $services_name = DB::table('nagios_hosts')
            ->where('alias','host')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->select('nagios_services.display_name')
            ->get();
            
        return view('historique.services',compact('services_history','services_name'));

    }

    public function statistic()
    {
        $services = DB::table('nagios_hosts')
            ->where('alias','host')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->get();
        
        $services_ok = 0;
        $services_warning = 0;
        $services_critical = 0;
        $services_unknown = 0;

        foreach ($services as $service) {
            
            // Servcies :
             
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
    
            
        };

        $Piechart = app()->chartjs
        ->name('services')
        ->type('pie')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['Ok', 'Warning', 'Critical', 'Unknown'])
        ->datasets([
            [
                
                'backgroundColor' => ['#6ccf01', 'yellow', 'crimson', '#C200FF'],
                'hoverBackgroundColor' => ['#519b01', 'rgb(255, 208, 0)', 'red', 'rgb(151, 4, 230)'],
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
                'data' =>  [$services_ok, $services_warning, $services_critical, $services_unknown],
              
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

        return view('statistique.services',compact('Piechart','Barchart'));
    }

    public function details($service_id)
    {
        $details = $this->getServices()
        ->where('service_id','=',$service_id)
        ->get();

        return view('details.serviceORequip',compact('details'));
    }

    public function getServicesHistory()
    {
        return DB::table('nagios_hosts')
            ->where('alias','host')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_statehistory','nagios_services.service_object_id','=','nagios_statehistory.object_id')
            ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_services.display_name as service_name','nagios_services.*','nagios_statehistory.*')
            ->orderByDesc('state_time');
    }

    public function getServices()
    {
        return DB::table('nagios_hosts')
            ->where('alias','host')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_services.display_name as service_name','nagios_services.*','nagios_servicestatus.*');
    }

    public function index()
    {
        $search = request()->query('search');

        if ($search) {
            $services = $this->getServices()->where('nagios_hosts.display_name','like','%'.$search.'%')->paginate(10);
        } else {
            $services = $this->getServices()->paginate(10);
        }
    
        return view('config.services', compact('services'));
    }

    public function delete($service_id)
    {
        dd($service_id);
    }
}
