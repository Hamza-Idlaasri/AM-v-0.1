@extends('layouts.app')

@section('content')
    <div class="container p-3">
        <table class="table table-bordered text-center">
        
            <thead class="bg-primary text-white">
                <tr>
                    <th>Host</th>
                    <th>Adresse IP</th>
                    <th>Status</th>
                    <th>Dernier verification</th>
                    <th>Description</th>
                </tr>
            </thead>
        
    
            <tr>
                <td>
                    localhost                         
                </td>
                <td>Ping</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            <tr>
                <td>
                                          
                </td>
                <td>Total Processes</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PROCS OK: 55 processes with STATE = RSZDT</td>
            </tr>
            <tr>
                <td>
                                             
                </td>
                <td>Root Partition</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">DISK OK - free space: / 12257 MB (71% inode=98%):</td>
            </tr>
            <tr>
                <td>
                                             
                </td>
                <td>Swap Usage</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">SWAP OK - 100% free (2027 MB out of 2027 MB)</td>
            </tr>
            <tr>
                <td>
                                             
                </td>
                <td>SSH</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">SSH OK - OpenSSH_7.4 (protocol 2.0)</td>
            </tr>
            <tr>
                <td>
                                             
                </td>
                <td>HTTP</td>
                
                <td><span class="badge badge-success">Up</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">HTTP WARNING: HTTP/1.1 403 Forbidden - 5179 bytes in 0.002 second response time</td>
            </tr>
            
            <tr>
                <td>
                    netis-router                         
                </td>
                <td>Ping</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            <tr>
                <td>
                                             
                </td>
                <td>Oktime</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">(No output on stdout) stderr: execvp(/usr/local/nagios/libexec/check_snmp, ...) failed. errno is 2: No such file or directory</td>
            </tr>
            <tr>
                <td>
                                             
                </td>
                <td>Port 1 Link Status</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">	(No output on stdout) stderr: execvp(/usr/local/nagios/libexec/check_snmp, ...) failed. errno is 2: No such file or directory</td>
            </tr>
            <tr>
                <td>
                                            
                </td>
                <td>Port 1 Bandwidth Usage</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">check_mrtgtraf: Unable to open MRTG log file</td>
            </tr>
            <tr>
                <td>
                    linux-1                         
                </td>
                <td>Ping</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            <tr>
                <td>
                                          
                </td>
                <td>Total Processes</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PROCS OK: 55 processes with STATE = RSZDT</td>
            </tr>
            <tr>
                <td>
                                             
                </td>
                <td>Root Partition</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">DISK OK - free space: / 12257 MB (71% inode=98%):</td>
            </tr>
            <tr>
                <td>
                                             
                </td>
                <td>Swap Usage</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">SWAP OK - 100% free (2027 MB out of 2027 MB)</td>
            </tr>
            <tr>
                <td>
                                             
                </td>
                <td>SSH</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">SSH OK - OpenSSH_7.4 (protocol 2.0)</td>
            </tr>
            <tr>
                <td>
                                             
                </td>
                <td>HTTP</td>
                
                <td><span class="badge badge-warning">Warning</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">HTTP WARNING: HTTP/1.1 403 Forbidden - 5179 bytes in 0.002 second response time</td>
            </tr>
            </tr>
            

            
            
            <tr>
                <td>
                    printer hp-M162                        
                </td>
                <td>Ping</td>
                
                <td><span class="badge badge-danger">Critical</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            <tr>
                <td>
                                           
                </td>
                <td>Printer Status</td>
                
                <td><span class="badge badge-danger">Critical</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
        
    
        </table>
    </div>


{{-- 
    <td><span class="badge badge-success">Ok</span></td>
                    
    <td><span class="badge badge-danger">Critical</span></td>
    
    <td><span class="badge badge-unknown">Ureachable</span></td> --}}
@endsection