@extends('layouts.app')

@section('content')

<div class="container">
    <!-- Search bar -->
    {{-- <div class="float-right">
        @include('inc.searchbar',['route' => 'historic.equipements'])
    </div> --}}
    
    {{-- Filter --}}
    <div class="float-right text-primary">
        @include('inc.filter',['names' => $equipements_name,'route' => 'historic.equipements','type' => 'Equipement','from' => 'historic'])
    </div>

    <!-- Download button -->
    <div class="float-left">
        @include('inc.download')
    </div>

</div>

<div class="container">
    
    
    <table class="table table-striped table-bordered">

        <tr  class="bg-primary text-light text-center">

            <th>Box</th>
            <th>Equipement</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>

    
    
        @forelse ($equipements_history as $equipement_history)

        <tr>
   
            <td>{{$equipement_history->host_name}}</td> 

            <td>{{$equipement_history->service_name}}</td>
            
            @switch($equipement_history->state)

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
            
            <td>{{$equipement_history->state_time}}</td>
            <td class="description">{{$equipement_history->output}}</td>
        </tr>
 
        @empty

            <tr>
                <td colspan="5">No result found</td>
            </tr>

        @endforelse
        
    </table>

    {{$equipements_history->appends(['status' => request()->query('status'),'from' => request()->query('from'),'to' => request()->query('to'),'name' => request()->query('name')])->links('vendor.pagination.bootstrap-4')}}

</div>

@endsection