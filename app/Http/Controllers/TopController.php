<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopController extends Controller
{
    public function show()
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

    
        return view('/test',compact('equipements_ok','equipements_critical','equipements_warning','equipements_unknown'));
   
    }
}
