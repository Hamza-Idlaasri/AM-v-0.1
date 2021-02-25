@extends('layouts.app')

@section('content')

<div class="container">

    @include('inc.searchbar',['route' => 'historic.equipements'])

    <table class="table table-striped table-bordered table-hover">

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
                <td colspan="5">No result found <strong>{{ request()->query('search') }}</strong></td>
            </tr>

        @endforelse
        
    </table>

    {{$equipements_history->appends(['search' => request()->query('search')])->links('vendor.pagination.bootstrap-4')}}

</div>

@endsection