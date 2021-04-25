@extends('layouts.app')

@section('content')
    <div class="container p-3">
        <table class="table table-bordered text-center">
        
            <thead class="bg-primary text-white">
                <tr>
                    <th>Box</th>
                    <th>Equipements</th>
                    <th>Status</th>
                    <th>Dernier verification</th>
                    <th>Description</th>
                </tr>
            </thead>
    
            <tr>
                <td>
                    box-1                         
                </td>
                <td>Equip-1</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            <tr>
                <td>
                                            
                </td>
                <td>Equip-2</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            <tr>
                <td>
                                          
                </td>
                <td>Equip-3</td>
                
                <td><span class="badge badge-warning">Warning</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
            <tr>
                <td>
                    box-2                         
                </td>
                <td>Equip-1</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            <tr>
                <td>
                                             
                </td>
                <td>Equip-2</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            <tr>
                <td>
                                        
                </td>
                <td>Equip-3</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            
            <tr>
                <td>
                    box-3                        
                </td>
                <td>Equip-1</td>
                
                <td><span class="badge badge-success">Ok</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            <tr>
                <td>
                                            
                </td>
                <td>Equip-1</td>
                
                <td><span class="badge badge-unknown">Unknown</span></td>
                    
                
                <td>2021-01-26 15:27:50</td>
                <td class="description">PING OK - Packet loss = 0%, RTA = 0.05 ms</td>
            </tr>
            <tr>
                <td>
                                          
                </td>
                <td>Equip-2</td>
                
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