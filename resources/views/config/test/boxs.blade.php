@extends('layouts.app')

@section('content')
    <div class="container p-3">
        <table class="table table-bordered text-center">
        
            <thead class="bg-primary text-white">
                <tr>
                    <th>Box</th>
                    <th>Adresse IP</th>
                    <th>Status</th>
                    <th>Dernier verification</th>
                    <th>Description</th>
                </tr>
            </thead>
        
    
            <tr>
                <td>
                    box-1                         
                </td>
                <td>192.168.1.200</td>
                
                <td><span class="badge badge-success">Up</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
            <tr>
                <td>
                    box-2                         
                </td>
                <td>192.168.1.1</td>
                
                <td><span class="badge badge-success">Up</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
            <tr>
                <td>
                    box-3                        
                </td>
                <td>192.168.56.1</td>
                
                <td><span class="badge badge-danger">Down</span></td>
                    
                
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