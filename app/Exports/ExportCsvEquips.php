<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ExportCsvEquips implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $equips = DB::table('nagios_hosts')
                    ->where('alias','box')
                    ->join('nagios_services','nagios_hosts.host_object_id','=','nagios_services.host_object_id')
                    ->join('nagios_statehistory','nagios_services.service_object_id','=','nagios_statehistory.object_id')
                    ->select('nagios_hosts.display_name as host_name','nagios_services.display_name as service_name','nagios_statehistory.state','nagios_statehistory.state_time','nagios_statehistory.long_output')
                    ->orderByDesc('state_time')
                    ->get();
        
        foreach ($equips as $equip) {
    
            switch ($equip->state) {
                case 0:
                    $equip->state = 'Ok';
                    break;
                case 1:
                    $equip->state = 'Warning';
                    break;
                case 2:
                    $equip->state = 'Critical';
                    break;
                case 3:
                    $equip->state = 'Uknown';
                    break;
            }

        }
        
        return $equips;
    }

    public function headings(): array
    {
        return [
            'Box',
            'Equipement',
            'State',
            'Dernier verification',
            'Description',
        ];
    }
}
