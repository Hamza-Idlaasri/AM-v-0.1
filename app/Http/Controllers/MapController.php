<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    public function automap()
    {

        $hosts = DB::table('nagios_hosts')
        ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id')
        ->get();

        $parent_hosts = DB::table('nagios_hosts')
        ->join('nagios_host_parenthosts','nagios_hosts.host_id','=','nagios_host_parenthosts.host_id')
        ->get();

        return view('cartes.automap')->with('hosts',$hosts)->with('parent_hosts',$parent_hosts);

        // return dd($parent_hosts);
    }

    public function carte()
    {
        return view('cartes.carte');
    }
}
