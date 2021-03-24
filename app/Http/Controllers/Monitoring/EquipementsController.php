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
        // dd($this->getServicesName()->get());

        $name = request()->query('name');
        $dateFrom = request()->query('from');
        $dateTo = request()->query('to');

        $all_equips_names = $this->getEquipsName()->get();
        
        // $last_24_h = date('Y-m-d', strtotime('-1 day'));
        // $last_week = date('Y-m-d', strtotime('-1 week'));
        // $last_month = date('Y-m-d', strtotime('-1 month'));
        // $last_year = date('Y-m-d', strtotime('-1 year'));

        $equips_ok = 0;
        $equips_warning = 0;
        $equips_critical = 0;
        $equips_unknown = 0;

        $equips_name = $this->getEquipsName();

        if($name)
        {
            $equips_name = $equips_name->where('nagios_services.display_name',$name);
        }


        $equips_name = $equips_name->get();
       
        $cas = [];
        $equips_status = [];
        $range = [];

        foreach ($equips_name as $equip) {

            $equips_checks = $this->getEquipsChecks()
                ->where('nagios_servicechecks.service_object_id','=',$equip->service_object_id);

            if($dateFrom || $dateTo)
            {
                if(!$dateFrom)
                {
                    $dateFrom = "2000-01-01";
                }

                if(!$dateTo)
                    $dateTo = date('Y-m-d');

                $equips_checks = $equips_checks
                    ->where('nagios_servicechecks.start_time','>=',$dateFrom)
                    ->where('nagios_servicechecks.end_time','<=',$dateTo);

            }

            $equips_checks = $equips_checks->get();

            foreach ($equips_checks as $equip_checks) {
                array_push($cas,$equip_checks->state);
                array_push($range,$equip_checks->end_time);
            }

            
            if(sizeof($cas) == 0)
            {
                $cas_is_empty = 'No data found';
                return view('statistique.equipements', compact('all_equips_names','cas_is_empty','cas'));

            } else
                array_push($equips_status,$this->getStatus($cas, $equip->display_name));
           
        }

        foreach ($equips_status as $status) {
            
            $equips_ok += $status->ok;
            $equips_warning += $status->warning;
            $equips_critical += $status->critical;
            $equips_unknown += $status->unknown;
        }
        

        return view('statistique.equipements', compact('all_equips_names','cas','range','equips_ok','equips_warning','equips_critical','equips_unknown'));
    
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

    public function getEquipsName()
    {
        return DB::table('nagios_hosts')
            ->where('alias','box')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->select('nagios_services.display_name','nagios_services.service_object_id');
    }

    public function getEquipsChecks()
    {
        return DB::table('nagios_servicechecks')
        ->select('nagios_hosts.alias','nagios_hosts.host_object_id','nagios_services.display_name','nagios_services.service_object_id','nagios_servicechecks.*')
        ->join('nagios_services','nagios_services.service_object_id','=','nagios_servicechecks.service_object_id')
        ->join('nagios_hosts','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
        ->where('alias','box');
    }

    public function getStatus($cas, $name)
    {
        $equips_ok = 0;
        $equips_warning = 0;
        $equips_critical = 0;
        $equips_unknown = 0;
        
        for ($i=0; $i < sizeof($cas) ; $i++) { 

            if (sizeof($cas) != $i+1) {
            
                if($cas[$i] == $cas[$i+1])
                {
                    continue;

                } else {

                    switch ($cas[$i]) {
                        
                        case 0:
                            $equips_ok++;
                            break;

                        case 1:
                            $equips_warning++;
                            break;

                        case 2:
                            $equips_critical++;
                            break;
                        case 3:
                            $equips_unknown++;
                            break;
                        
                    }
                }
            }
        }

        switch ($cas[sizeof($cas)-1]) {
                        
            case 0:
                $equips_ok++;
                break;

            case 1:
                $equips_warning++;
                break;

            case 2:
                $equips_critical++;
                break;
            case 3:
                $equips_unknown++;
                break;
            
        }


        return (object)['equip'=>$name,'ok'=>$equips_ok,'warning'=>$equips_warning,'critical'=>$equips_critical, 'unknown' => $equips_unknown];
        
    }
}
