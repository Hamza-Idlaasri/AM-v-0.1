<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

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
                    $dateFrom = '2000-01-01';
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
        
        $services_name = $this->getServicesName()->get();
            
        return view('historique.services',compact('services_history','services_name'));

    }

    public function statistic()
    {
        // dd($this->getServicesName()->get());

        $name = request()->query('name');
        $dateFrom = request()->query('from');
        $dateTo = request()->query('to');

        $all_services_names = $this->getServicesName()->get();
        
        // $last_24_h = date('Y-m-d', strtotime('-1 day'));
        // $last_week = date('Y-m-d', strtotime('-1 week'));
        // $last_month = date('Y-m-d', strtotime('-1 month'));
        // $last_year = date('Y-m-d', strtotime('-1 year'));

        $services_ok = 0;
        $services_warning = 0;
        $services_critical = 0;
        $services_unknown = 0;

        $services_name = $this->getServicesName();

        if($name)
        {
            $services_name = $services_name->where('nagios_services.display_name',$name);
        }


        $services_name = $services_name->get();
       
        $cas = [];
        $services_status = [];
        $range = [];

        foreach ($services_name as $service) {

            $services_checks = $this->getServicesChecks()
                ->where('nagios_servicechecks.service_object_id','=',$service->service_object_id);

            if($dateFrom || $dateTo)
            {
                if(!$dateFrom)
                {
                    $dateFrom = "2000-01-01";
                }

                if(!$dateTo)
                    $dateTo = date('Y-m-d');

                $services_checks = $services_checks
                    ->where('nagios_servicechecks.start_time','>=',$dateFrom)
                    ->where('nagios_servicechecks.end_time','<=',$dateTo);

            }

            $services_checks = $services_checks->get();

            foreach ($services_checks as $service_checks) {
                array_push($cas,$service_checks->state);
                array_push($range,$service_checks->end_time);
            }

            
            if(sizeof($cas) == 0)
            {
                $cas_is_empty = 'No data found';
                return view('statistique.services', compact('all_services_names','cas_is_empty','cas'));

            } else
                array_push($services_status,$this->getStatus($cas, $service->display_name));
           
        }

        foreach ($services_status as $status) {
            
            $services_ok += $status->ok;
            $services_warning += $status->warning;
            $services_critical += $status->critical;
            $services_unknown += $status->unknown;
        }
        

        return view('statistique.services', compact('all_services_names','cas','range','services_ok','services_warning','services_critical','services_unknown'));
    
    }

    public function download()
    {

        $services_history = $this->getServicesHistory()->get();
        
        $pdf = PDF::loadView('download.services', compact('services_history'))->setPaper('a4', 'landscape');

        return $pdf->stream('services_history.pdf');
        
    }
    
    public function details($service_id)
    {
        $details = $this->getServices()
        ->where('service_id','=',$service_id)
        ->get();

        return view('details.serviceORequip',compact('details'));
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

    public function getServicesHistory()
    {
        return DB::table('nagios_hosts')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_statehistory','nagios_services.service_object_id','=','nagios_statehistory.object_id')
            ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_services.display_name as service_name','nagios_services.*','nagios_statehistory.*')
            ->where('alias','host')
            ->orderByDesc('state_time');
    }

    public function getServices()
    {
        return DB::table('nagios_hosts')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_servicestatus','nagios_services.service_object_id','=','nagios_servicestatus.service_object_id')
            ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_services.display_name as service_name','nagios_services.*','nagios_servicestatus.*')
            ->where('alias','host');
    }

    public function getServicesName()
    {
        return DB::table('nagios_hosts')
            ->where('alias','host')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->select('nagios_services.display_name','nagios_services.service_object_id');
    }

    public function getServicesChecks()
    {
        return DB::table('nagios_servicechecks')
        ->select('nagios_hosts.alias','nagios_hosts.host_object_id','nagios_services.display_name','nagios_services.service_object_id','nagios_servicechecks.*')
        ->join('nagios_services','nagios_services.service_object_id','=','nagios_servicechecks.service_object_id')
        ->join('nagios_hosts','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
        ->where('alias','host');
    }

    public function getStatus($cas, $name)
    {
        $services_ok = 0;
        $services_warning = 0;
        $services_critical = 0;
        $services_unknown = 0;
        
        for ($i=0; $i < sizeof($cas) ; $i++) { 

            if (sizeof($cas) != $i+1) {
            
                if($cas[$i] == $cas[$i+1])
                {
                    continue;

                } else {

                    switch ($cas[$i]) {
                        
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
                        
                    }
                }
            }
        }

        switch ($cas[sizeof($cas)-1]) {
                        
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
            
        }


        return (object)['service'=>$name,'ok'=>$services_ok,'warning'=>$services_warning,'critical'=>$services_critical, 'unknown' => $services_unknown];
        
    }
}
