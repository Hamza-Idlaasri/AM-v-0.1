<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" />

<style>

body{
    font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif
}

@page{
    margin: 10px 50px;
    padding: 0;
}

</style>

    <h4 class="text-center">Services History</h4>
    <br>
    <table class="table table-striped table-bordered table-hover text-center">
        <tr class="bg-primary text-light">

            <th>Host</th>
            <th>Service</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>

    
    
        @foreach ($services_history as $service_history)

        <tr>
   
            <td>{{$service_history->host_name}}</td> 

            <td>{{$service_history->service_name}}</td>
            
            @switch($service_history->state)

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
            
            <td>{{$service_history->state_time}}</td>
            <td class="description">{{$service_history->output}}</td>
        </tr>
        @endforeach
    </table>
 

