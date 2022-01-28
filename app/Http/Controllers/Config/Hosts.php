<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Hosts extends Controller
{
    public function __construct()
    {
        $this->middleware(['agent']);
    }
    
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

    public function types()
    {
        return view('config.add.hosts_types');
    }

    public function manage($type)
    {
        $hosts = DB::table('nagios_hosts')
        ->select('display_name as host_name')
        ->get();

        $host_groups = DB::table('nagios_hostgroups')
        ->select('alias')
        ->get();

        return view('config.add.hosts',compact('hosts','host_groups','type')); 
    }

    public function add($type,Request $request)
    {
    
        switch ($type) {

            case 'windows':

                // validation
                $this->validate($request,[
                    'hostName' => 'required|min:2|max:20|unique:nagios_hosts,display_name|regex:/^[a-zA-Z0-9-_+ ]/',
                    'addressIP' => 'required|min:7|max:15',
                ],[
                    'addressIP.required' => 'the IP address field is empty',
                ]);

                $path = "/usr/local/nagios/etc/objects/hosts/".$request->hostName;

                if(!is_dir($path))
                    mkdir($path);
                
                // Parent relationship
                if($request->input('hosts'))
                    $define_host = "define host {\n\tuse\t\t\twindows-server\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n\tcheck_command\t\t\tcheck_ncpa!-t '' -P 5693 -M system/agent_version\n\tparents\t\t\t".$request->input('hosts')."\n}\n\n";
                else
                    $define_host = "define host {\n\tuse\t\t\twindows-server\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n\tcheck_command\t\t\tcheck_ncpa!-t '' -P 5693 -M system/agent_version\n}\n\n";

                // Host :
                file_put_contents($path."/".$request->hostName.".cfg", $define_host);
                $cfg_file = "\n\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/{$request->hostName}.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);


                // Services :
                // file_put_contents($path."/NSClient++ Version.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tNSClient++ Version\n\tcheck_command\t\tcheck_nt!CLIENTVERSION\n}\n\n");
                // $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/NSClient++ Version.cfg";
                // file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);
                
                // file_put_contents($path."/Uptime.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tUptime\n\tcheck_command\t\tcheck_nt!UPTIME\n}\n\n");
                // $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/Uptime.cfg";
                // file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                // file_put_contents($path."/CPU Load.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tCPU Load\n\tcheck_command\t\tcheck_nt!CPULOAD!-l 5,80,90\n}\n\n");
                // $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/CPU Load.cfg";
                // file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                // file_put_contents($path."/Memory Usage.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tMemory Usage\n\tcheck_command\t\tcheck_nt!MEMUSE!-w 80 -c 90\n}\n\n");
                // $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/Memory Usage.cfg";
                // file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                // file_put_contents($path."/C Drive Space.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tC Drive Space\n\tcheck_command\t\tcheck_nt!USEDDISKSPACE!-l c -w 80 -c 90\n}\n\n");
                // $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/C Drive Space.cfg";
                // file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                // file_put_contents($path."/W3SVC.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tW3SVC\n\tcheck_command\t\tcheck_nt!SERVICESTATE!-d SHOWALL -l W3SVC\n}\n\n");
                // $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/W3SVC.cfg";
                // file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                // file_put_contents($path."\Explorer.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tExplorer\n\tcheck_command\t\tcheck_nt!PROCSTATE!-d SHOWALL -l Explorer.exe\n}\n\n");
                // $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}\Explorer.cfg";
                // file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);
                
                file_put_contents($path."/CPU Usage.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tCPU Usage\n\tcheck_command\t\tcheck_ncpa!-t '' -P 5693 -M cpu/percent -w 60 -c 80 -q 'aggregate=avg'\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/CPU Usage.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);
                
                file_put_contents($path."/RAM.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tRAM\n\tcheck_command\t\tcheck_ncpa!-t '' -P 5693 -M memory/virtual -w 50 -c 80 -u G\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/RAM.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);
                
                file_put_contents($path."/Process Count.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tProcess Count\n\tcheck_command\t\tcheck_ncpa! -P 5693 -M 'disk/logical/C:|' --units G\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/Process Count.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);
                
                file_put_contents($path."/Disk C.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tDisk C\n\tcheck_command\t\tcheck_ncpa!-t '' -P 5693 -M processes -w 150 -c 200\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/Disk C.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);
            

                break;

            case 'linux':

                // validation
                $this->validate($request,[
                    'hostName' => 'required|min:2|max:20|unique:nagios_hosts,display_name|regex:/^[a-zA-Z0-9-_+ ]/',
                    'addressIP' => 'required|min:7|max:15',
                ],[
                    'addressIP.required' => 'the IP address field is empty',
                ]);

                $path = "/usr/local/nagios/etc/objects/hosts/".$request->hostName;

                if(!is_dir($path))
                    mkdir($path);

                // Parent relationship
                if($request->input('hosts'))
                    $define_host = "define host {\n\tuse\t\t\tlinux-server\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n\tparents\t\t\t".$request->input('hosts')."\n}\n\n";
                else
                    $define_host = "define host {\n\tuse\t\t\tlinux-server\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n}\n\n";

                // Hosts : 
                file_put_contents($path."/".$request->hostName.".cfg", $define_host);
                $cfg_file = "\n\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/{$request->hostName}.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                // Services :
                file_put_contents($path."/PING.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tPING\n\tcheck_command\t\tcheck_ping!100.0,20%!500.0,60%\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/PING.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                file_put_contents($path."/Current Load.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tCurrent Load\n\tcheck_command\t\tcheck_local_load!5.0,4.0,3.0!10.0,6.0,4.0\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/Current Load.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                file_put_contents($path."/Total Processes.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tTotal Processes\n\tcheck_command\t\tcheck_nrpe!check_total_procs\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/Total Processes.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                file_put_contents($path."/Current Users.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tCurrent Users\n\tcheck_command\t\tcheck_nrpe!check_users\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/Current Users.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                file_put_contents($path."/SSH.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tSSH\n\tcheck_command\t\tcheck_nrpe!check_ssh\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/SSH.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                file_put_contents($path."/HTTP.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tHTTP\n\tcheck_command\t\tcheck_http\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/HTTP.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                file_put_contents($path."/Root Partition.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tRoot Partition\n\tcheck_command\t\tcheck_local_disk!20%!10%!/\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/Root Partition.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                file_put_contents($path."/Swap Usage.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tSwap Usage\n\tcheck_command\t\tcheck_local_swap!20!10\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/Swap Usage.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);
            
                break;

            case 'switch':

                // validation
                $this->validate($request,[
                    'hostName' => 'required|min:2|max:20|unique:nagios_hosts,display_name|regex:/^[a-zA-Z0-9-_+ ]/',
                    'addressIP' => 'required|min:7|max:15',
                    'community' => 'required|max:25',
                    'portsNbr' => 'required'
                ],[
                    'addressIP.required' => 'the IP address field is empty',
                ]);

                $path = "/usr/local/nagios/etc/objects/hosts/".$request->hostName;

                if(!is_dir($path))
                    mkdir($path);
                
                // Parent relationship
                if($request->input('hosts'))
                    $define_host = "define host {\n\tuse\t\t\tgeneric-switch\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n\tparents\t\t\t".$request->input('hosts')."\n}\n\n";
                else
                    $define_host = "define host {\n\tuse\t\t\tgeneric-switch\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n}\n\n";
                
                // Hosts : 
                file_put_contents($path."/".$request->hostName.".cfg", $define_host);
                $cfg_file = "\n\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/{$request->hostName}.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                // Services :
                file_put_contents($path."/PING.cfg","define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tPING\n\tcheck_command\t\tcheck_ping!200.0,20%!600.0,60%\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/PING.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                for ($i = 1; $i <= $request->portsNbr; $i++) { 
                    
                    file_put_contents($path."/Port ".$i." Link Status.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tPort ".$i." Link Status\n\tcheck_command\t\tcheck_snmp!-C ".$request->community." -o ifOperStatus.".$i." -r ".$i." -m RFC1213-MIB\n}\n\n");
                    $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/Port ".$i." Link Status.cfg";
                    file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);
    
                    file_put_contents($path."/Port ".$i." Bandwidth Usage.cfg","define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tPort ".$i." Bandwidth Usage\n\tcheck_command\t\tcheck_local_mrtgtraf!/var/lib/mrtg/".$request->addressIP."_".$i.".log!AVG!1000000,1000000!5000000,5000000!10\n}\n\n");
                    $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/Port ".$i." Bandwidth Usage.cfg";
                    file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);
                }

                file_put_contents($path."/Uptime.cfg","define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tUptime\n\tcheck_command\t\tcheck_snmp!-C ".$request->community." -o sysUpTime.0\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/Uptime.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);


                break;

            case 'router':

                // validation
                $this->validate($request,[
                    'hostName' => 'required|min:2|max:20|unique:nagios_hosts,display_name|regex:/^[a-zA-Z0-9-_+ ]/',
                    'addressIP' => 'required|min:7|max:15',
                    'community' => 'required|max:25',
                    'portsNbr' => 'required'
                ],[
                    'addressIP.required' => 'the IP address field is empty',
                ]);

                $path = "/usr/local/nagios/etc/objects/hosts/".$request->hostName;

                if(!is_dir($path))
                    mkdir($path);

                // Parent relationship
                if($request->input('hosts'))
                    $define_host = "define host {\n\tuse\t\t\tgeneric-switch\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n\tparents\t\t\t".$request->input('hosts')."\n}\n\n";
                else
                    $define_host = "define host {\n\tuse\t\t\tgeneric-switch\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n}\n\n";
                
                // Host :
                file_put_contents($path."/".$request->hostName.".cfg", $define_host);
                $cfg_file = "\n\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/{$request->hostName}.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                // Services :
                file_put_contents($path."/PING.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tPING\n\tcheck_command\t\tcheck_ping!200.0,20%!600.0,60%\n\tnormal_check_interval\t5\n\tretry_check_interval\t1\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/PING.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);
                
                for ($i= 1; $i <= $request->portsNbr; $i++) { 
                    
                    file_put_contents($path."/Port ".$i." Link Status.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tPort ".$i." Link Status\n\tcheck_command\t\tcheck_snmp!-C ".$request->community." -o ifOperStatus.".$i." -r ".$i." -m RFC1213-MIB\n}\n\n");
                    $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/Port ".$i." Link Status.cfg";
                    file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);
                }
                
                file_put_contents($path."/Uptime.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tUptime\n\tcheck_command\t\tcheck_snmp!-C ".$request->community." -o sysUpTime.0\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/Uptime.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);
                 
                break;

            case 'printer':

                // validation
                $this->validate($request,[
                    'hostName' => 'required|min:2|max:20|unique:nagios_hosts,display_name|regex:/^[a-zA-Z0-9-_+ ]/',
                    'addressIP' => 'required|min:7|max:15',
                    'community' => 'required|max:25'
                ],[
                    'addressIP.required' => 'the IP address field is empty',
                ]);
                
                $path = "/usr/local/nagios/etc/objects/hosts/".$request->hostName;

                if(!is_dir($path))
                    mkdir($path);

                // Parent relationship
                if($request->input('hosts'))
                    $define_host = "define host {\n\tuse\t\t\tgeneric-printer\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n\tparents\t\t\t".$request->input('hosts')."\n}\n\n";
                else
                    $define_host = "define host {\n\tuse\t\t\tgeneric-printer\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n}\n\n";
                
                // Host :
                file_put_contents($path."/".$request->hostName.".cfg", $define_host);
                $cfg_file = "\n\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/{$request->hostName}.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                // Service :
                file_put_contents($path."/PING.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tPING\n\tcheck_command\t\tcheck_ping!3000.0,80%!5000.0,100%\n\tnormal_check_interval\t\t5\nretry_check_interval\t\t1\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/PING.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);

                file_put_contents($path."/Printer Status.cfg", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tPrinter Status\n\tcheck_command\t\tcheck_hpjd!-C ".$request->community."\n\tnormal_check_interval\t5\n\tretry_check_interval\t1\n}\n\n");
                $cfg_file = "\ncfg_file=/usr/local/nagios/etc/objects/hosts/{$request->hostName}/Printer Status.cfg";
                file_put_contents("/usr/local/nagios/etc/nagios.cfg", $cfg_file, FILE_APPEND);
                                
                break;
            
        }

        // $file = fopen($path, 'w') or die("Unable to open file!");  

        // fwrite($file, $define_host);

        // if($request->input('hostgroupName') || $request->input('groups'))
        // {
        //     if($request->input('hostgroupName'))    
        //         $define_hostgroup = "define hostgroup{\nhostgroup_name\t".$request->input('hostgroupName')."\nalias\t".$request->input('hostgroupName')."\nmembers\t".$request->hostName."\n}\n\n";
        //     if($request->input('groups'))
        //         $define_hostgroup = "define hostgroup{\nhostgroup_name\t".$request->input('groups')."\nalias\t".$request->input('groups')."\nmembers\t".$request->hostName."\n}\n\n";
        //     fwrite($file, $define_hostgroup);
        // }

        // for ($i=0; $i < sizeof($define_services) ; $i++) { 
        //     fwrite($file, $define_services[$i]);
        // }

        // fclose($file);
        
        shell_exec('sudo service nagios restart');

        return redirect()->route('configHosts');

    }

    public function getHosts()
    {
        return DB::table('nagios_hosts')
            ->where('alias','host')
            ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id')
            ->orderBy('display_name');
            
    }
}

