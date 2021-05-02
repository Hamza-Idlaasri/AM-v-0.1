<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class ExportCsvHosts implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('nagios_hosts')
            ->where('alias','host')
            ->join('nagios_statehistory','nagios_hosts.host_object_id','=','nagios_statehistory.object_id')
            ->select('nagios_hosts.display_name','nagios_hosts.address','nagios_statehistory.state','nagios_statehistory.state_time','nagios_statehistory.long_output')
            ->orderByDesc('state_time')
            ->get();
    }
}
