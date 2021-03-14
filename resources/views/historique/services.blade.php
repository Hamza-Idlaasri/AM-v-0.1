@extends('layouts.app')

@section('content')

<div class="container">
    <!-- Search bar -->
    {{-- <div class="float-right">
        @include('inc.searchbar',['route' => 'historic.services'])
    </div> --}}
    
    <!-- Filter -->
    <div class="float-right text-primary">
        @include('inc.filter',['names' => $services_name ,'route' => 'historic.services','type' => 'Service'])
    </div>

    <!-- Download button -->
    <div class="float-left">
        @include('inc.download')
    </div>

</div>

<div class="container">    

    <table class="table table-striped table-bordered">

        <tr  class="bg-primary text-light text-center">
            <th>Host</th>
            <th>Service</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>
    

        @forelse ($services_history as $service_history)

        
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
                @default
                    
            @endswitch
            
            <td>{{$service_history->state_time}}</td>
            <td class="description">{{$service_history->output}}</td>
        </tr>
 
        @empty

            <tr>
                <td colspan="5">No result found</td>
            </tr>

        @endforelse
        
    </table>

    {{$services_history->appends(['status' => request()->query('status'),'from' => request()->query('from'),'to' => request()->query('to'),'name' => request()->query('name')])->links('vendor.pagination.bootstrap-4')}}

</div>

@endsection