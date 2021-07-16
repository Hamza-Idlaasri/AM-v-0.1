@extends('layouts.app')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="container">

    @include('inc.searchbar',['route' => 'monitoring.services'])

    <table class="table table-striped table-bordered table-hover">

        <tr  class="bg-primary text-light text-center">
            <th>Host</th>
            <th>Service</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>

        <?php $check = 0 ?>

        
        @forelse ($services as $service)        
        
        <tr>

            @if ($check == 0 || $service->host_object_id != $check)       
                
                    <td><a href="/monitoring/hosts/{{$service->host_id}}">{{$service->host_name}}</a></td> 

                    <?php $check = $service->host_object_id ?>
                
            @else
                <td></td>
            @endif
            

            <td><a href="/monitoring/services/{{$service->service_id}}">{{$service->service_name}}</a></td>
            
            @switch($service->current_state)
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
                @default
                    
            @endswitch
            
            <td>{{$service->last_check}}</td>
            <td class="description">{{$service->output}}</td>
        </tr>
            

        @empty

            <tr>
                <td colspan="5">No result found <strong>{{ request()->query('search') }}</strong></td>
            </tr>

        @endforelse

        
    </table>

    
    {{$services->appends(['search' => request()->query('search')])->links('vendor.pagination.bootstrap-4')}}

</div>


@endsection
