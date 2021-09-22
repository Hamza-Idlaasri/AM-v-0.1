<?php

namespace App\Http\Controllers\Config\Add;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddService extends Controller
{
    public function manage()
    {
        $hosts = DB::table('nagios_hosts')
        ->where('alias','host')
        ->select('nagios_hosts.display_name as host_name','nagios_hosts.*')
        ->get();

        $windows = ['NSClient++ Version','CPU Load','Memory Usage','C Drive Space','W3SVC'];

        $linux = ['Current Load','Total Processes','Current Users','SSH','HTTP','Root Partition','Swap Usage'];

        $printer = ['PING (printer)','Printer Status'];

        return view('config.add.service', compact('hosts','windows','linux'));
    }

    public function add(Request $request)
    {
        // validation
        $this->validate($request,[

            'service' => 'required',
            'host' => 'required',
            'community' => 'nullable|max:100',
            'portNbr' => 'nullable'
        ]);

        switch ($request->service) {
            
            // Windows
            case 'NSClient++ Version':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;
                
                file_put_contents($path."\NSClient++ Version.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tNSClient++ Version\n\tcheck_command\t\tcheck_nt!CLIENTVERSION\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/NSClient++ Version.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;

            case 'CPU Load':
                
                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\CPU Load.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tCPU Load\n\tcheck_command\t\tcheck_nt!CPULOAD!-l 5,80,90\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/CPU Load.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;

            case 'Memory Usage':
                
                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\Memory Usage.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tMemory Usage\n\tcheck_command\t\tcheck_nt!MEMUSE!-w 80 -c 90\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/Memory Usage.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;

            case 'C Drive Space':
                
                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\C Drive Space.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tC Drive Space\n\tcheck_command\t\tcheck_nt!USEDDISKSPACE!-l c -w 80 -c 90\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/C Drive Space.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;

            case 'W3SVC':
                
                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\W3SVC.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tW3SVC\n\tcheck_command\t\tcheck_nt!SERVICESTATE!-d SHOWALL -l W3SVC\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/W3SVC.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;

            case 'Uptime(windows)':
                
                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\Uptime.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tUptime\n\tcheck_command\t\tcheck_nt!UPTIME\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/Uptime.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;

            // Linux
            case 'PING(linux)':
                
                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\PING.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tPING\n\tcheck_command\t\tcheck_ping!100.0,20%!500.0,60%\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/PING.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;
            
            case 'Current Load':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\Current Load.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tCurrent Load\n\tcheck_command\t\tcheck_local_load!5.0,4.0,3.0!10.0,6.0,4.0\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/Current Load.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;
            
            case 'Total Processes':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\Total Processes.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tTotal Processes\n\tcheck_command\t\tcheck_nrpe!check_total_procs\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/Total Processes.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;
            
            case 'Current Users':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\Current Users.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tCurrent Users\n\tcheck_command\t\tcheck_nrpe!check_users\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/Current Users.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;
            
            case 'SSH':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\SSH.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tSSH\n\tcheck_command\t\tcheck_nrpe!check_ssh\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/SSH.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;
            
            case 'HTTP':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\HTTP.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tHTTP\n\tcheck_command\t\tcheck_http\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/HTTP.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;
            
            case 'Root Partition':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\Root Partition.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tRoot Partition\n\tcheck_command\t\tcheck_local_disk!20%!10%!/\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/Root Partition.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;
            
            case 'Swap Usage':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\Swap Usage.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tSwap Usage\n\tcheck_command\t\tcheck_local_swap!20!10\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/Swap Usage.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;
            
            // Router
            case 'PING(router)':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\PING.txt","define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tPING\n\tcheck_command\t\tcheck_ping!200.0,20%!600.0,60%\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/PING.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;
            
            case 'Port n Link Status(router)':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\Port 1 Link Status.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tPort ".$request->portNbr." Link Status\n\tcheck_command\t\tcheck_snmp!-C ".$request->community." -o ifOperStatus.".$request->portNbr." -r 1 -m RFC1213-MIB\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}\Port ".$request->portNbr." Link Status.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;
            
            case 'Uptime(router)':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\Uptime.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tUptime\n\tcheck_command\t\tcheck_snmp!-C ".$request->community." -o sysUpTime.0\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/Uptime.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;
            
            // Switch
            case 'PING(switch)':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\PING.txt","define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tPING\n\tcheck_command\t\tcheck_ping!200.0,20%!600.0,60%\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/PING.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;
            
            case 'Port n Link Status(switch)':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\Port ".$request->portNbr." Link Status.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tPort ".$request->portNbr." Link Status\n\tcheck_command\t\tcheck_snmp!-C ".$request->community." -o ifOperStatus.".$request->portNbr." -r 1 -m RFC1213-MIB\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/Port ".$request->portNbr." Link Status.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;
            
            case 'Port n Bandwidth Usage':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                foreach (DB::table('nagios_hosts')->where('display_name',$request->host)->select('address')->get() as $key) {
                    $addressIP = $key->address;
                }

                file_put_contents($path."\Port ".$request->portNbr." Bandwidth Usage.txt","define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tPort ".$request->portNbr." Bandwidth Usage\n\tcheck_command\t\tcheck_local_mrtgtraf!/var/lib/mrtg/".$addressIP."_".$request->portNbr.".log!AVG!1000000,1000000!5000000,5000000!10\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/Port ".$request->portNbr." Bandwidth Usage.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;
            
            case 'Uptime(switch)':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\Uptime.txt","define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tUptime\n\tcheck_command\t\tcheck_snmp!-C ".$request->community." -o sysUpTime.0\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/Uptime.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;
            
            // Printer
            case 'PING(printer)':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\PING.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tPING\n\tcheck_command\t\tcheck_ping!3000.0,80%!5000.0,100%\n\tnormal_check_interval\t\t5\nretry_check_interval\t\t1\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/PING.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;

            case 'Printer Status':

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->host;

                file_put_contents($path."\Printer Status.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->host."\n\tservice_description\tPrinter Status\n\tcheck_command\t\tcheck_hpjd!-C ".$request->community." \n\tnormal_check_interval\t5\n\tretry_check_interval\t1\n}\n\n");
                $cfg_file = "\ncfg_file=C:\Users\pc\Desktop\Laravel\objects\hosts\\{$request->host}/Printer Status.txt";
                file_put_contents("C:\Users\pc\Desktop\Laravel\objects\\nagios_cfg.txt", $cfg_file, FILE_APPEND);

                break;
        }
        
        shell_exec('sudo service nagios restart');

        // return redirect()->route('monitoring.services');
        return back();
    }
}
