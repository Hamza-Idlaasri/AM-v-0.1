
<div id="top_left" class="float-left">
    <div id="logo">
        <img src="{{asset('images/interface/AlarmManager.png')}}" alt="Logo" />
    </div>
    
</div>

<div id="top_right" class="float-right">
    
    
    <table>

        <tr>
            
            <!-- Username -->

            <td colspan="3" class="text-primary">user name</td>

            <td></td>
            
            <!-- Hosts summary -->

            <td title="Hosts">
                <img src="{{asset('images/interface/Hosts.png')}}" alt="" >
            </td>
            
            <td title="total des hosts : {{ $total_hosts }}">
                <span class="badge">{{ $total_hosts }}</span>  
            </td>

            <td title="{{ $hosts_up }} {{ Str::plural('host',$hosts_up)}} Up">
                <span class="badge badge-success">{{ $hosts_up }}</span> 
            </td>

            <td title="{{ $hosts_down }} {{ Str::plural('host',$hosts_down)}} Down">
                <span class="badge badge-danger">{{ $hosts_down }}</span> 
            </td>

            <td title="{{ $hosts_unreachable }} {{ Str::plural('host',$hosts_unreachable)}} Unreachacble">
                <span class="badge badge-unknown">{{ $hosts_unreachable }}</span> 
            </td>

            <td></td>


            <td></td>

            <!-- Equipements summary -->

            <td  title="Equipements">
                <img src="{{asset('images/interface/Equipement.png')}}" alt="">
            </td>

            <td  title="total des equipements : {{ $total_equipements }}">
                <span class="badge">{{ $total_equipements }}</span>  
            </td>

            <td  title="{{ $equipements_ok }} {{ Str::plural('equipement',$equipements_ok)}} Ok">
                <span class="badge badge-success">{{ $equipements_ok }}</span> 
            </td>

            <td  title="{{ $equipements_warning }} {{ Str::plural('equipement',$equipements_warning)}} warning">
                <span class="badge badge-warning">{{ $equipements_warning }}</span>  
            </td>

            <td  title="{{ $equipements_critical }} {{ Str::plural('equipement',$equipements_critical)}} critical">
                <span class="badge badge-danger">{{ $equipements_critical }}</span>  
            </td>

            <td  title="{{ $equipements_unknown }} {{ Str::plural('equipement',$equipements_unknown)}} unknown">
                <span class="badge badge-unknown">{{ $equipements_unknown }}</span>  
            </td>
        </tr>

        <tr>

            <!-- User profile -->
            <td title="User Profile">
                <img src="{{asset('images/interface/user.png')}}" alt="" style="width: auto">
            </td>

            <!-- Edit password -->
            <td title="Modify Password">
                <img src="{{asset('images/interface/key.png')}}" alt="" style="width: auto">
            </td>

            <!-- Logout -->
            <td title="Logout">
                <img src="{{asset('images/interface/power-button.png')}}" alt="" style="width: auto">
            </td>

            <td></td>
            

            <!-- Services summary -->

            <td title="Services">
                <img src="{{asset('images/interface/settings-(1).png')}}" alt="services">
            </td>

            <td title="total des services : {{ $total_services }}">
                <span class="badge">{{ $total_services }}</span> 
            </td>
            
            <td title="{{ $services_ok }} {{ Str::plural('service',$services_ok)}} Ok">
                <span class="badge badge-success">{{ $services_ok }}</span> 
            </td>
            
            <td title="{{ $services_warning }} {{ Str::plural('service',$services_warning)}} warning">
                <span class="badge badge-warning">{{ $services_warning }}</span> 
            </td>
            
            <td title="{{ $services_critical }} {{ Str::plural('service',$services_critical)}} critical">
                <span class="badge badge-danger">{{ $services_critical }}</span> 
            </td>
            
            <td title="{{ $services_unknown }} {{ Str::plural('service',$services_unknown)}} unknown">
                <span class="badge badge-unknown">{{ $services_unknown }}</span> 
            </td>

            <td></td>
            
            <!-- Pins summary -->

            <td><img src="{{asset('images/interface/pins.png')}}" alt=""></td>
            <td >
                <span class="badge">0</span></td>
            <td >
                <span class="badge badge-success">0</span></td>
            <td >
                <span class="badge badge-warning">0</span></td>
            <td >
                <span class="badge badge-danger">0</span></td>
            <td >
                <span class="badge badge-unknown">0</span></td>
        </tr>
    </table>
</div>

<div class="clearfix"></div>


