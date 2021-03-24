<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BoxsController extends Controller
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
            $boxs = $this->getBoxs()
                ->where('nagios_hosts.display_name','like', '%'.$search.'%')
                ->paginate(10);

        } else {

            $boxs = $this->getBoxs()->paginate(10);
        }        

        

        
        return view('monitoring.boxs',compact('boxs'));
    }

    public function problems()
    {
        $search = request()->query('search');

        if($search)
        {
            $boxs_problems = $this->getBoxs()
            ->where('nagios_hosts.display_name','like', '%'.$search.'%')
            ->where('current_state','<>','0')
            ->paginate(10);
            
        } else {
            
            $boxs_problems = $this->getBoxs()
            ->where('current_state','<>','0')
            ->paginate(10);
            
        }
        
        
        return view('problems.boxs',compact('boxs_problems'));
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

            $boxs_history = $this->getBoxsHistory();

            if($name)
            {
                $boxs_history = $boxs_history->where('nagios_hosts.display_name', $name);
            }

            if($dateFrom || $dateTo)
            {
                if(!$dateFrom)
                {
                    $dateFrom = json_encode(DB::table('nagios_statehistory')->select('state_time')->first(),true);
                }


                if(!$dateTo)
                    $dateTo = date('Y-m-d');
                
                $boxs_history = $boxs_history
                    ->where('nagios_statehistory.state_time','>=', $dateFrom)
                    ->where('nagios_statehistory.state_time','<=', $dateTo);
            }

            if($status)
            {
                switch ($status) {
                    case 'up':
                        $boxs_history = $boxs_history->where('state','0');
                        break;
                    
                    case 'down':
                        $boxs_history = $boxs_history->where('state','1');
                        break;
                    
                    case 'unknown':
                        $boxs_history = $boxs_history->where('state','2');
                        break;
                    
                }
            }

            $boxs_history = $boxs_history->paginate(10);
            
        } else {

            $boxs_history = $this->getBoxsHistory()->paginate(10);

        }

        $boxs_name = DB::table('nagios_hosts')
            ->where('alias','box')
            ->select('nagios_hosts.display_name')
            ->get();

        return view('historique.boxs',compact('boxs_history','boxs_name'));
    }

    // public function statistic()
    // {
    //     $boxs_statistic = DB::table('nagios_hosts')
    //     ->where('alias','box')
    //     ->join('nagios_statehistory','nagios_hosts.host_object_id','=','nagios_statehistory.object_id')
    //     ->get();

    //     return view('example',compact('boxs_statistic'));
    // }

    public function details($host_id)
    {
        $details = $this->getBoxs()->where('host_id','=',$host_id)->get();

        return view('details.hostORbox',compact('details'));
    }

    public function getBoxsHistory()
    {
        return DB::table('nagios_hosts')
        ->where('alias','box')
        ->join('nagios_statehistory','nagios_hosts.host_object_id','=','nagios_statehistory.object_id')
        ->orderByDesc('state_time');
    }

    public function getBoxs()
    {
        return DB::table('nagios_hosts')
        ->where('alias','box')
        ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id');
        
    }

    public function index()
    {
        $search = request()->query('search');

        if ($search) {
            $boxs = $this->getBoxs()->where('display_name','like','%'.$search.'%')->paginate(10);
        } else {
            $boxs = $this->getBoxs()->paginate(10);
        }
        
        return view('config.boxs', compact('boxs'));
    }

    public function delete($box_id)
    {
        // 1: get the box you want delete
        // 2: delete its cfg file from /usr/local/nagios/etc/objects/file_name.cfg
        // 3: remove the line of its declaration from /usr/local/nagios/etc/nagios.cfg (who's like this: "cfg_file=/usr/local/nagios/etc/objects/file_name.cfg")
        // 4: restart nagios by run this command line "service nagios restart"

        return '1: get the box you want delete (this is its id in nagios_hosts table : '.$box_id.')
                <br>
                2: delete its cfg file from /usr/local/nagios/etc/objects/file_name.cfg
                <br>
                3: remove the line of its declaration from /usr/local/nagios/etc/nagios.cfg (who is like this: "cfg_file=/usr/local/nagios/etc/objects/file_name.cfg")
                <br>
                4: restart nagios by run this command line "service nagios restart"';

    }
}
