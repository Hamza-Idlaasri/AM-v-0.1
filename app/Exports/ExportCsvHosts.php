<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ExportCsvHosts implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $hosts =  DB::table('nagios_hosts')
                    ->where('alias','host')
                    ->join('nagios_statehistory','nagios_hosts.host_object_id','=','nagios_statehistory.object_id')
                    ->select('nagios_hosts.display_name','nagios_hosts.address','nagios_statehistory.state','nagios_statehistory.state_time','nagios_statehistory.long_output')
                    ->orderByDesc('state_time')
                    ->get();

        
        foreach ($hosts as $host) {

            switch ($host->state) {
                
                case 0:
                    $host->state = 'Up';
                    break;
                case 1:
                    $host->state = 'Down';
                    break;
                case 2:
                    $host->state = 'Unreachable';
                    break;

            }

        }

        return $hosts;
    }

    public function headings(): array
    {
        return [
            'Host',
            'Address IP',
            'State',
            'Dernier verification',
            'Description',
        ];
    }
}
