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
                <td>127.0.0.1</td>
                
                <td><span class="badge badge-success">Up</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
            <tr>
                <td>
                    netis-router                         
                </td>
                <td>192.168.1.1</td>
                
                <td><span class="badge badge-success">Up</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
            <tr>
                <td>
                    linux-1                        
                </td>
                <td>192.168.56.1</td>
                
                <td><span class="badge badge-danger">Down</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
            <tr>
                <td>
                    linux-2                        
                </td>
                <td>192.168.56.2</td>
                
                <td><span class="badge badge-success">Up</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
            <tr>
                <td>
                    switch alpha                        
                </td>
                <td>192.168.20.1</td>
                
                <td><span class="badge badge-success">Up</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
            <tr>
                <td>
                    printer hp-M162                        
                </td>
                <td>192.168.1.120</td>
                
                <td><span class="badge badge-danger">Down</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
            <tr>
                <td>
                    windows-3                         
                </td>
                <td>192.168.1.30</td>
                
                <td><span class="badge badge-success">Up</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
            <tr>
                <td>
                    windows-1                         
                </td>
                <td>192.168.1.35</td>
                
                <td><span class="badge badge-success">Up</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
            <tr>
                <td>
                    linux-server                         
                </td>
                <td>192.168.120</td>
                
                <td><span class="badge badge-unknown">Ureachable</span>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
            <tr>
                <td>
                    r-TNCAP2D1C84                         
                </td>
                <td>192.168.1.23</td>

                <td><span class="badge badge-danger">Down</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
            <tr>
                <td>
                    r-TNCAPY6NP14                             
                </td>
                <td>192.168.1.25</td>
                
                <td><span class="badge badge-success">Up</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
            <tr>
                <td>
                    windows-2                      
                </td>
                <td>192.168.1.33</td>
                
                <td><span class="badge badge-unknown">Ureachable</span>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
    
    
        </table>
    </div>


{{-- 
    <td><span class="badge badge-success">Up</span></td>
                    
    <td><span class="badge badge-danger">Down</span></td>
    
    <td><span class="badge badge-unknown">Ureachable</span></td> --}}
@endsection