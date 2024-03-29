
<div id="top_left" class="float-left">
    {{-- <div id="logo"> --}}
        <img src="{{asset('images/interface/AlarmManager.png')}}" alt="Logo" />
    {{-- </div> --}}
    
</div>

<span style="position:relative; top: 35px; left:155px; " class="text-light"><i>Beta</i></span>
<div id="top_right" class="float-right">
    
    <table>

        <tr>
            
            <!-- Username -->

            <td colspan="3" class="text-primary font-weight-bold">{{ auth()->user()->name }}</td>

            <td></td>
            
            <!-- Hosts summary -->

            <td title="Hosts">
                <i class="fas fa-desktop text-muted"></i>
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

            <td  title="Boxes">
                <i class="far fa-microchip fa-lg text-muted"></i>
            </td>

            <td  title="total des boxes : {{ $total_boxs }}">
                <span class="badge">{{ $total_boxs }}</span>  
            </td>

            <td  title="{{ $boxs_up }} {{ Str::plural('box',$boxs_up)}} Up">
                <span class="badge badge-success">{{ $boxs_up }}</span> 
            </td>

            <td  title="{{ $boxs_down }} {{ Str::plural('box',$boxs_down)}} Down">
                <span class="badge badge-danger">{{ $boxs_down }}</span>  
            </td>

            <td  title="{{ $boxs_unreachable }} {{ Str::plural('box',$boxs_unreachable)}} Unreachable">
                <span class="badge badge-unknown">{{ $boxs_unreachable }}</span>  
            </td>

            <td></td>
        </tr>

        <tr>

            <!-- User profile -->
            <td title="User Profile" class="usertools">
                <a href="/user/profile"><i class="fas fa-user fa-lg"></i></a>
            </td>

            <!-- Edit password -->
            <td title="Modify Password" class="usertools">
                <a href="/user/edit-password"><i class="fas fa-key fa-lg"></i></a>
            </td>

            <!-- Logout -->
            <td title="Logout">
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" style="border:0;outline: none" class="usertools">
                        <i class="fas fa-power-off fa-lg"></i>
                    </button>
                </form>
            </td>

            <td></td>
            

            <!-- Services summary -->

            <td title="Services">
                <i class="fas fa-cog fa-lg text-muted"></i>
            </td>

            <td title="total des services : {{ $total_services }}">
                <span class="badge">{{ $total_services }}</span> 
            </td>
            
            <td title="{{ $services_ok }} {{ Str::plural('service',$services_ok)}} ok">
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

            <td  title="Equipements">
                <img src="{{asset('images/interface/pins.png')}}" alt="">
            </td>

            <td  title="total des equipements : {{ $total_equipements }}">
                <span class="badge">{{ $total_equipements }}</span>  
            </td>

            <td  title="{{ $equipements_ok }} {{ Str::plural('equipement',$equipements_ok)}} ok">
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
    </table>
</div>

<div class="clearfix"></div>
