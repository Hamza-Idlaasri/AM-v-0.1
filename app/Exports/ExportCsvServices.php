<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ExportCsvServices implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $services = DB::table('nagios_hosts')
            ->where('alias','host')
            ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
            ->join('nagios_statehistory','nagios_services.service_object_id','=','nagios_statehistory.object_id')
            ->select('nagios_hosts.display_name as host_name','nagios_services.display_name as service_name','nagios_statehistory.state','nagios_statehistory.state_time','nagios_statehistory.long_output')
            ->orderByDesc('state_time')
            ->get();

        foreach ($services as $service) {
            
            // $host->state_time = strval($host->state_time);

            switch ($service->state) {
                case 0:
                    $service->state = 'Ok';
                    break;
                case 1:
                    $service->state = 'Warning';
                    break;
                case 2:
                    $service->state = 'Critical';
                    break;
                case 3:
                    $service->state = 'Uknown';
                    break;
            }

        }
        
        return $services;
    }

    public function headings(): array
    {
        return [
            'Host',
            'Service',
            'State',
            'Dernier verification',
            'Description',
        ];
    }
}
