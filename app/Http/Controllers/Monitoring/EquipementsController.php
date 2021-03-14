<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Http\Controllers\Controller\DetailsController;

class EquipementsController extends Controller
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
            $equipements = $this->getEquipements()
                ->where('nagios_hosts.display_name','like', '%'.$search.'%')
                ->paginate(10);

        } else {

            $equipements = $this->getEquipements()->paginate(10);
        }
        

        
        return view('monitoring.equipements',compact('equipements'));
    }
    
    public function problems()
    {
        $search = request()->query('search');

        if($search)
        {
            $equipement_problems = $this->getEquipements()
            ->where('nagios_hosts.display_name','like', '%'.$search.'%')
            ->where('current_state','<>','0')
            ->paginate(10);

        } else {

            $equipement_problems = $this->getEquipements()
            ->where('current_state','<>','0')
            ->paginate(10);
        }
        
        
        return view('problems.equipements',compact('equipement_problems'));
    }

    public function historic()
    {
        $status = request()->query('status');
        $name = request()->query('name');
        $dateFrom = request()->query('from');
        $dateTo = request()->query('to');

        if($status || $name || $dateFrom || $dateTo)
        {
            $equipements_history = $this->getEquipHistory();

            // Filter by Name
            if($name)
            {
                $equipements_history = $equipements_history->where('nagios_services.display_name', $name);
            }
            
            // Filter by Date
            if($dateFrom || $dateTo)
            {
                if(!$dateFrom) 
                {
                    $dateFrom = json_encode(DB::table('nagios_statehistory')->select('state_time')->first(),true);
                }


                if(!$dateTo)
                    $dateTo = date('Y-m-d');

                $equipements_history = $equipements_history
                    ->where('nagios_statehistory.state_time','>=', $dateFrom)
                    ->where('nagios_statehistory.state_time','<=', $dateTo);

               
            }


            // Filter by State
            if($status)
            {
                switch ($status) {
                    case 'ok':
                        $equipements_history = $equipements_history->where('state','0');
                        break;
                 
                    case 'warning':
                        $equipements_history = $equipements_history->where('state','1');
                            break;
                 
                    case 'critical':
                        $equipements_history = $equipements_history->where('state','2');
                                break;
                 
                    case 'unreachable':
                        $equipements_history = $equipements_history->where('state','3');
                                break;
                 
                }
            }

            $equipements_history = $equipements_history->paginate(10);

        } else{

            $equipements_history = $this->getEquipHistory()->paginate(10);
            
        }

        $equipements_name = DB::table('nagios_hosts')
            ->where('alias','box')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->select('nagios_services.display_name')
            ->get();

        return view('historique.equipements',compact('equipements_history','equipements_name'));
    
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
        
        $pdf = PDF::loadView('download');
        return $pdf->stream('equip.pdf');
        
    }

    public function details($service_id)
    {
        $details = $this->getEquipements()
            ->where('service_id','=',$service_id)
            ->get();

        return view('details.serviceORequip',compact('details'));
        
    
    }

    public function getEquipHistory()
    {
        return DB::table('nagios_hosts')
                ->where('alias','box')
                ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
                ->join('nagios_statehistory','nagios_services.service_object_id','=','nagios_statehistory.object_id')
                ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_services.display_name as service_name','nagios_services.*','nagios_statehistory.*')
                ->orderByDesc('state_time');
    }

    public function getEquipements()
    {
        return  DB::table('nagios_hosts')
        ->where('alias','box')
        ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
        ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
        ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_services.display_name as service_name','nagios_services.*','nagios_servicestatus.*');
    }

    public function index()
    {
        $search = request()->query('search');

        if ($search) {
            $equipements = $this->getEquipements()->where('nagios_hosts.display_name','like','%'.$search.'%')->paginate(10);
        } else {
            $equipements = $this->getEquipements()->paginate(10);
        }
        
        

        return view('config.equipements', compact('equipements'));
    }
}
