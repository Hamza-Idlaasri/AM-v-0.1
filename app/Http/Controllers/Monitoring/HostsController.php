<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

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

    // Statistic
    
    public function statistic()
    {
                
        $name = request()->query('name');
        $dateFrom = request()->query('from');
        $dateTo = request()->query('to');

        $all_hosts_names = $this->getHostsName()->get();

        $last_24_h = date('Y-m-d', strtotime('-1 day'));
        $last_week = date('Y-m-d', strtotime('-1 week'));
        $last_month = date('Y-m-d', strtotime('-1 month'));
        $last_year = date('Y-m-d', strtotime('-1 year'));

        $hosts_up = 0;
        $hosts_down = 0;
        $hosts_unreachable = 0;

        $hosts_name = $this->getHostsName();

        if($name)
        {
            $hosts_name = $hosts_name->where('display_name',$name);
        }


        $hosts_name = $hosts_name->get();
       
        $cas = [];
        $hosts_status = [];
        $range = [];

        foreach ($hosts_name as $host) {

            $hosts_checks = $this->getHostsChecks()
                ->where('nagios_hostchecks.host_object_id','=',$host->host_object_id);

            if($dateFrom || $dateTo)
            {
                if(!$dateFrom)
                {
                    $dateFrom = "2000-01-01";
                }

                if(!$dateTo)
                    $dateTo = date('Y-m-d');

                $hosts_checks = $hosts_checks
                    ->where('nagios_hostchecks.start_time','>=',$dateFrom)
                    ->where('nagios_hostchecks.end_time','<=',$dateTo);

            }

            $hosts_checks = $hosts_checks->get();

            foreach ($hosts_checks as $host_checks) {
                array_push($cas,$host_checks->state);
                array_push($range,$host_checks->end_time);
            }

            
            if(sizeof($cas) == 0)
            {
                $case = 'No data found';
                return view('statistique.hosts', compact('all_hosts_names','case','cas'));

            } else
                array_push($hosts_status,$this->getStatus($cas, $host->display_name));
           
        }

        foreach ($hosts_status as $status) {
            
            $hosts_up += $status->up;
            $hosts_down += $status->down;
            $hosts_unreachable += $status->unreachable;
        }
        

        return view('statistique.hosts', compact('all_hosts_names','cas','range','hosts_up','hosts_down','hosts_unreachable'));
    }

    public function download()
    {

        $hosts_history = $this->getHostsHistory()->get();
        
        $pdf = PDF::loadView('download.hosts', compact('hosts_history'))->setPaper('a4', 'landscape');

        return $pdf->stream('hosts_history.pdf');
        
    }

    public function details($host_id)
    {
        $details = $this->getHosts()
            ->where('host_id','=',$host_id)
            ->get();
        
        return view('details.hostORbox',compact('details'));
        
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

    /************************ Helpers ****************************/

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
            ->select('nagios_hosts.display_name as host_name','nagios_hosts.*','nagios_services.display_name as service_name','nagios_services.*','nagios_servicestatus.*');
    }

    public function getHostsChecks()
    {

        return DB::table('nagios_hostchecks')
            ->select('nagios_hosts.display_name','nagios_hosts.alias','nagios_hosts.host_object_id','nagios_hostchecks.*')
            ->join('nagios_hosts','nagios_hosts.host_object_id','=','nagios_hostchecks.host_object_id')
            ->where('alias','host')
            ->where('is_raw_check','=',0);
        
    }

    public function getHostsName()
    {
        return DB::table('nagios_hosts')
            ->where('alias','host')
            ->select('nagios_hosts.display_name','nagios_hosts.host_object_id');
    }

    public function getStatus($cas, $name)
    {
        $hosts_up = 0;
        $hosts_down = 0;
        $hosts_unreachable = 0;
        
        for ($i=0; $i < sizeof($cas) ; $i++) { 

            if (sizeof($cas) != $i+1) {
            
                if($cas[$i] == $cas[$i+1])
                {
                    continue;

                } else {

                    switch ($cas[$i]) {
                        
                        case 0:
                            $hosts_up++;
                            break;

                        case 1:
                            $hosts_down++;
                            break;

                        case 2:
                            $hosts_unreachable++;
                            break;
                        
                    }
                }
            }
        }

        switch ($cas[sizeof($cas)-1]) {
                        
            case 0:
                $hosts_up++;
                break;

            case 1:
                $hosts_down++;
                break;

            case 2:
                $hosts_unreachable++;
                break;
            
        }


        return (object)['host'=>$name,'up'=>$hosts_up,'down'=>$hosts_down,'unreachable'=>$hosts_unreachable];
        
    }

}
