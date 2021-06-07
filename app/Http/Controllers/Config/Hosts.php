<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Hosts extends Controller
{
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
                    'hostName' => 'required',
                    'addressIP' => 'required',
                ],[
                    'addressIP.required' => 'the IP address field is empty',
                ]);

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->hostName;

                if(!is_dir($path))
                    mkdir($path);
                
                // Parent relationship
                if($request->input('hosts'))
                    $define_host = "define host {\n\tuse\t\t\twindows-server\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n\tparents\t\t\t".$request->input('hosts')."\n}\n\n";
                else
                    $define_host = "define host {\n\tuse\t\t\twindows-server\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n}\n\n";

                file_put_contents($path."\\".$request->hostName.".txt", $define_host);

                file_put_contents($path."\NSClient++ Version.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tNSClient++ Version\n\tcheck_command\t\tcheck_nt!CLIENTVERSION\n}\n\n");
                file_put_contents($path."\Uptime.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tUptime\n\tcheck_command\t\tcheck_nt!UPTIME\n}\n\n");
                file_put_contents($path."\CPU Load.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tCPU Load\n\tcheck_command\t\tcheck_nt!CPULOAD!-l 5,80,90\n}\n\n");
                file_put_contents($path."\Memory Usage.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tMemory Usage\n\tcheck_command\t\tcheck_nt!MEMUSE!-w 80 -c 90\n}\n\n");
                file_put_contents($path."\C Drive Space.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tC:\ Drive Space\n\tcheck_command\t\tcheck_nt!USEDDISKSPACE!-l c -w 80 -c 90\n}\n\n");
                file_put_contents($path."\W3SVC.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tW3SVC\n\tcheck_command\t\tcheck_nt!SERVICESTATE!-d SHOWALL -l W3SVC\n}\n\n");
                file_put_contents($path."\Explorer.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tExplorer\n\tcheck_command\t\tcheck_nt!PROCSTATE!-d SHOWALL -l Explorer.exe\n}\n\n");
                

                break;

            case 'linux':

                // validation
                $this->validate($request,[
                    'hostName' => 'required',
                    'addressIP' => 'required',
                ],[
                    'addressIP.required' => 'the IP address field is empty',
                ]);

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->hostName;

                if(!is_dir($path))
                    mkdir($path);

                // Parent relationship
                if($request->input('hosts'))
                    $define_host = "define host {\n\tuse\t\t\tlinux-server\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n\tparents\t\t\t".$request->input('hosts')."\n}\n\n";
                else
                    $define_host = "define host {\n\tuse\t\t\tlinux-server\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n}\n\n";

                file_put_contents($path."\\".$request->hostName.".txt", $define_host);
                
                file_put_contents($path."\PING.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tPING\n\tcheck_command\t\tcheck_ping!100.0,20%!500.0,60%\n}\n\n");
                file_put_contents($path."\Current Load.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tCurrent Load\n\tcheck_command\t\tcheck_local_load!5.0,4.0,3.0!10.0,6.0,4.0\n}\n\n");
                file_put_contents($path."\Total Processes.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tTotal Processes\n\tcheck_command\t\tcheck_nrpe!check_total_procs\n}\n\n");
                file_put_contents($path."\Current Users.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tCurrent Users\n\tcheck_command\t\tcheck_nrpe!check_users\n}\n\n");
                file_put_contents($path."\SSH.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tSSH\n\tcheck_command\t\tcheck_nrpe!check_ssh\n}\n\n");
                file_put_contents($path."\HTTP.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tHTTP\n\tcheck_command\t\tcheck_http\n}\n\n");
                file_put_contents($path."\Root Partition.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tRoot Partition\n\tcheck_command\t\tcheck_local_disk!20%!10%!/\n}\n\n");
                file_put_contents($path."\Swap Usage.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tSwap Usage\n\tcheck_command\t\tcheck_local_swap!20!10\n}\n\n");
                
                break;

            case 'switch':

                // validation
                $this->validate($request,[
                    'hostName' => 'required',
                    'addressIP' => 'required',
                    'community' => 'required'
                ],[
                    'addressIP.required' => 'the IP address field is empty',
                ]);

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->hostName;

                if(!is_dir($path))
                    mkdir($path);
                
                $file = fopen($path."\\".$request->hostName.".txt", 'w');

                // Parent relationship
                if($request->input('hosts'))
                    $define_host = "define host {\n\tuse\t\t\tswitch-server\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n\tparents\t\t\t".$request->input('hosts')."\n}\n\n";
                else
                    $define_host = "define host {\n\tuse\t\t\tswitch-server\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n}\n\n";
                
                file_put_contents($path."\\".$request->hostName.".txt", $define_host);

                file_put_contents($path."\PING.txt","define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tPING\n\tcheck_command\t\tcheck_ping!200.0,20%!600.0,60%\n}\n\n");
                file_put_contents($path."\Port 1 Link Status.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tPort 1 Link Status\n\tcheck_command\t\tcheck_snmp!-C ".$request->community." -o ifOperStatus.1 -r 1 -m RFC1213-MIB\n}\n\n");
                file_put_contents($path."\Uptime.txt","define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tUptime\n\tcheck_command\t\tcheck_snmp!-C ".$request->community." -o sysUpTime.0\n}\n\n");
                file_put_contents($path."\Port 1 Bandwidth Usage.txt","define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tPort 1 Bandwidth Usage\n\tcheck_command\t\tcheck_local_mrtgtraf!/var/lib/mrtg/192.168.1.253_1.log!AVG!1000000,1000000!5000000,5000000!10\n}\n\n");

                break;

            case 'router':

                // validation
                $this->validate($request,[
                    'hostName' => 'required',
                    'addressIP' => 'required',
                    'community' => 'required'
                ],[
                    'addressIP.required' => 'the IP address field is empty',
                ]);

                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->hostName;

                if(!is_dir($path))
                    mkdir($path);

                // Parent relationship
                if($request->input('hosts'))
                    $define_host = "define host {\n\tuse\t\t\trouter-server\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n\tparents\t\t\t".$request->input('hosts')."\n}\n\n";
                else
                    $define_host = "define host {\n\tuse\t\t\trouter-server\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n}\n\n";
                
                file_put_contents($path."\\".$request->hostName.".txt", $define_host);

                file_put_contents($path."\PING.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tPING\n\tcheck_command\t\tcheck_ping!200.0,20%!600.0,60%\n\tnormal_check_interval\t5\n\tretry_check_interval\t1\n}\n\n");
                file_put_contents($path."\Port 1 Link Status.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tPort 1 Link Status\n\tcheck_command\t\tcheck_snmp!-C ".$request->community." -o ifOperStatus.1 -r 1 -m RFC1213-MIB\n}\n\n");
                file_put_contents($path."\Uptime.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tUptime\n\tcheck_command\t\tcheck_snmp!-C ".$request->community." -o sysUpTime.0\n}\n\n");
                 
                break;

            case 'printer':

                // validation
                $this->validate($request,[
                    'hostName' => 'required',
                    'addressIP' => 'required',
                    'community' => 'required'
                ],[
                    'addressIP.required' => 'the IP address field is empty',
                ]);
                
                $path = "C:\Users\pc\Desktop\Laravel\objects\hosts\\".$request->hostName;

                if(!is_dir($path))
                    mkdir($path);

                // Parent relationship
                if($request->input('hosts'))
                    $define_host = "define host {\n\tuse\t\t\tprinter-server\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n\tparents\t\t\t".$request->input('hosts')."\n}\n\n";
                else
                    $define_host = "define host {\n\tuse\t\t\tprinter-server\n\thost_name\t\t".$request->hostName."\n\talias\t\t\thost\n\taddress\t\t\t".$request->addressIP."\n}\n\n";
                    
                file_put_contents($path."\\".$request->hostName.".txt", $define_host);
                
                file_put_contents($path."\PING.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tPING\n\tcheck_command\t\tcheck_ping!3000.0,80%!5000.0,100%\n\tnormal_check_interval\t\t5\nretry_check_interval\t\t1\n}\n\n");
                file_put_contents($path."\Printer Status.txt", "define service {\n\tuse\t\t\tgeneric-service\n\thost_name\t\t".$request->hostName."\n\tservice_description\tPrinter Status\n\tcheck_command\t\tcheck_hpjd!-C ".$request->community."\n\tnormal_check_interval\t5\n\tretry_check_interval\t1\n}\n\n");
                                
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
        
        return redirect()->route('configHosts');

    }

    public function edit($host_object_id)
    {
        // $host = $this->getHosts()->where('nagios_hosts.host_object_id', $host_object_id)->get();
        // $services = $this->getServices()->where('nagios_services.host_object_id', $host_object_id)->get();

        // return view('config.edit.host', compact('host','services'));
        return back();
    }

    public function getHosts()
    {
        return DB::table('nagios_hosts')
            ->where('alias','host')
            ->join('nagios_hoststatus','nagios_hosts.host_object_id','=','nagios_hoststatus.host_object_id');
    }
}

