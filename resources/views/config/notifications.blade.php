@extends('layouts.app')

<style>
    .hide{
        display: none;
    }
</style>

@section('content')

    <div class="container d-flex justify-content-around w-50 my-3">
        <button class="btn btn-primary font-weight-bold" id="Hs">Hosts</button>
        <button class="btn btn-light font-weight-bold" id="Ss">Services</button>
        <button class="btn btn-light font-weight-bold" id="Bs">Boxs</button>
        <button class="btn btn-light font-weight-bold" id="Es">Equipements</button>
    </div>

    {{-- Hosts --}}
    <div class="container py-3 my-3" id="hosts">
        <table class="table table-bordered table-striped">
            <thead class="bg-primary text-white text-center">
                <th>Hosts</th>
                <th>State</th>
                <th>Time</th>
                <th>Notification Reason</th>
                <th>Escaleted</th>
                <th>Information</th>
            </thead>

            <tbody>
                @forelse ($hosts as $host)
                    <tr>
                        <td>{{ $host->host_name }}</td>

                        @switch($host->state)

                            @case(0)
                                <td><span class="badge badge-success">Up</span></td>
                            @break

                            @case(1)
                                <td><span class="badge badge-danger">Down</span></td>
                            @break

                            @case(2)
                                <td><span class="badge badge-unknown">Ureachable</span></td>
                            @break

                            @default

                        @endswitch

                        <td>{{ $host->start_time }}</td>
                        
                        @switch($host->notification_reason)
                            @case(0)
                                <td>Normal notification</td>
                                @break
                            @case(1)
                                <td>Problem acknowledgement</td>
                                @break
                            @case(2)
                                <td>Flapping started</td>
                                @break
                            @case(3)
                                <td>Flapping stopped</td>
                                @break
                            @case(4)
                                <td>Flapping was disabled</td>
                                @break
                            @case(5)
                                <td>Downtime started</td>
                                @break
                            @case(6)
                                <td>Downtime ended</td>
                                @break
                            @case(7)
                                <td>Downtime was cancelled</td>
                                @break
                                
                        @endswitch

                        @switch($host->escalated)
                            @case(0)
                                <td>No</td>
                                @break
                            @case(1)
                                <td>Yes</td>
                                @break
                            @default
                                
                        @endswitch


                    </tr>
                    @empty
                        <tr>
                            <td colspan="6">No notifications</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
    </div>

    {{-- Services --}}
    <div class="container py-3 my-3" id="services" style="display: none">
        <table class="table table-bordered table-striped">
            <thead class="bg-primary text-white text-center">
                <th>Hosts</th>
                <th>Service</th>
                <th>State</th>
                <th>Time</th>
                <th>Notification Reason</th>
                <th>Escaleted</th>
                <th>Information</th>
            </thead>

            <tbody>
                @forelse ($services as $service)
                    <tr>
                        <td>{{ $service->host_name }}</td>

                        <td>{{ $service->service_name }}</td>

                        @switch($service->state)

                        @case(0)
                            <td><span class="badge badge-success">Ok</span></td>
                            @break

                        @case(1)
                            <td><span class="badge badge-warning">Warning</span></td>
                            @break

                        @case(2)
                            <td><span class="badge badge-danger">Critical</span></td>
                            @break

                        @case(3)
                            <td><span class="badge badge-unknown">Ureachable</span></td>
                            @break

                        @endswitch

                        <td>{{ $service->start_time }}</td>
                        
                        @switch($service->notification_reason)
                            @case(0)
                                <td>Normal notification</td>
                                @break
                            @case(1)
                                <td>Problem acknowledgement</td>
                                @break
                            @case(2)
                                <td>Flapping started</td>
                                @break
                            @case(3)
                                <td>Flapping stopped</td>
                                @break
                            @case(4)
                                <td>Flapping was disabled</td>
                                @break
                            @case(5)
                                <td>Downtime started</td>
                                @break
                            @case(6)
                                <td>Downtime ended</td>
                                @break
                            @case(7)
                                <td>Downtime was cancelled</td>
                                @break
                                
                        @endswitch

                        @switch($service->escalated)
                            @case(0)
                                <td>No</td>
                                @break
                            @case(1)
                                <td>Yes</td>
                                @break
                            @default
                                
                        @endswitch

                        <td>{{ $service->long_output }}</td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7">No notifications</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
    </div>

    {{-- Boxs --}}
    <div class="container py-3 my-3" id="boxs" style="display: none">
            <table class="table table-bordered table-striped">
                <thead class="bg-primary text-white text-center">
                    <th>Boxs</th>
                    <th>State</th>
                    <th>Time</th>
                    <th>Notification Reason</th>
                    <th>Escaleted</th>
                    <th>Information</th>
                </thead>
    
                <tbody>
                    @forelse ($boxs as $box)
                        <tr>
                            <td>{{ $box->box_name }}</td>
    
                            @switch($box->state)
    
                                @case(0)
                                    <td><span class="badge badge-success">Up</span></td>
                                @break
    
                                @case(1)
                                    <td><span class="badge badge-danger">Down</span></td>
                                @break
    
                                @case(2)
                                    <td><span class="badge badge-unknown">Ureachable</span></td>
                                @break
    
                                @default
    
                            @endswitch
    
                            <td>{{ $box->start_time }}</td>
                            
                            @switch($box->notification_reason)
                                @case(0)
                                    <td>Normal notification</td>
                                    @break
                                @case(1)
                                    <td>Problem acknowledgement</td>
                                    @break
                                @case(2)
                                    <td>Flapping started</td>
                                    @break
                                @case(3)
                                    <td>Flapping stopped</td>
                                    @break
                                @case(4)
                                    <td>Flapping was disabled</td>
                                    @break
                                @case(5)
                                    <td>Downtime started</td>
                                    @break
                                @case(6)
                                    <td>Downtime ended</td>
                                    @break
                                @case(7)
                                    <td>Downtime was cancelled</td>
                                    @break
                                    
                            @endswitch
    
                            @switch($box->escalated)
                                @case(0)
                                    <td>No</td>
                                    @break
                                @case(1)
                                    <td>Yes</td>
                                    @break
                                @default
                                    
                            @endswitch
    
                            <td>{{ $box->long_output }}</td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6">No notifications</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
    </div>

    {{-- Equipements --}}
    <div class="container py-3 my-3" id="equips" style="display: none">
        <table class="table table-bordered table-striped">
            <thead class="bg-primary text-white text-center">
                <th>Boxs</th>
                <th>Equipements</th>
                <th>State</th>
                <th>Time</th>
                <th>Notification Reason</th>
                <th>Escaleted</th>
                <th>Information</th>
            </thead>

            <tbody>
                @forelse ($equips as $equip)
                    <tr>
                        <td>{{ $equip->box_name }}</td>

                        <td>{{ $equip->equip_name }}</td>

                        @switch($equip->state)

                        @case(0)
                            <td><span class="badge badge-success">Ok</span></td>
                            @break

                        @case(1)
                            <td><span class="badge badge-warning">Warning</span></td>
                            @break

                        @case(2)
                            <td><span class="badge badge-danger">Critical</span></td>
                            @break

                        @case(3)
                            <td><span class="badge badge-unknown">Ureachable</span></td>
                            @break

                        @endswitch

                        <td>{{ $equip->start_time }}</td>
                        
                        @switch($equip->notification_reason)
                            @case(0)
                                <td>Normal notification</td>
                                @break
                            @case(1)
                                <td>Problem acknowledgement</td>
                                @break
                            @case(2)
                                <td>Flapping started</td>
                                @break
                            @case(3)
                                <td>Flapping stopped</td>
                                @break
                            @case(4)
                                <td>Flapping was disabled</td>
                                @break
                            @case(5)
                                <td>Downtime started</td>
                                @break
                            @case(6)
                                <td>Downtime ended</td>
                                @break
                            @case(7)
                                <td>Downtime was cancelled</td>
                                @break
                                
                        @endswitch

                        @switch($equip->escalated)
                            @case(0)
                                <td>No</td>
                                @break
                            @case(1)
                                <td>Yes</td>
                                @break
                            @default
                                
                        @endswitch

                        <td>{{ $equip->long_output }}</td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7">No notifications</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
    </div>

<script>

    const Hs = document.getElementById('Hs');
    const Bs = document.getElementById('Bs');
    const Ss = document.getElementById('Ss');
    const Es = document.getElementById('Es');
    
    const Hosts = document.getElementById('hosts');
    const Boxs = document.getElementById('boxs');
    const Services = document.getElementById('services');
    const Equips = document.getElementById('equips');

    Hs.onclick = () =>{
        Hosts.style.display = 'block';
        Boxs.style.display = 'none';
        Services.style.display = 'none';
        Equips.style.display = 'none';

        Hs.classList.remove("btn-light");
        Hs.classList.add("btn-primary");

        Ss.classList.remove("btn-primary");
        Ss.classList.add("btn-light");
        
        Bs.classList.remove("btn-primary");
        Bs.classList.add("btn-light");
        
        Es.classList.remove("btn-primary");
        Es.classList.add("btn-light");

        

    }

    Ss.onclick = () =>  {
        Services.style.display = 'block';
        Boxs.style.display = 'none';
        Hosts.style.display = 'none';
        Equips.style.display = 'none';

        Ss.classList.remove("btn-light");
        Ss.classList.add("btn-primary");

        Hs.classList.remove("btn-primary");
        Hs.classList.add("btn-light");
        
        Bs.classList.remove("btn-primary");
        Bs.classList.add("btn-light");
        
        Es.classList.remove("btn-primary");
        Es.classList.add("btn-light");

    }

    Bs.onclick = () =>  {
        Boxs.style.display = 'block';
        Hosts.style.display = 'none';
        Services.style.display = 'none';
        Equips.style.display = 'none';

        Bs.classList.remove("btn-light");
        Bs.classList.add("btn-primary");

        Ss.classList.remove("btn-primary");
        Ss.classList.add("btn-light");
        
        Hs.classList.remove("btn-primary");
        Hs.classList.add("btn-light");
        
        Es.classList.remove("btn-primary");
        Es.classList.add("btn-light");
    }

    Es.onclick = () => {
        Equips.style.display = 'block';
        Boxs.style.display = 'none';
        Services.style.display = 'none';
        Hosts.style.display = 'none';

        Es.classList.remove("btn-light");
        Es.classList.add("btn-primary");

        Ss.classList.remove("btn-primary");
        Ss.classList.add("btn-light");
        
        Bs.classList.remove("btn-primary");
        Bs.classList.add("btn-light");
        
        Hs.classList.remove("btn-primary");
        Hs.classList.add("btn-light");

    }

</script>

@endsection
