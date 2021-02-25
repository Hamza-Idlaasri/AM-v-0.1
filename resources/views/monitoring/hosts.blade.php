@extends('layouts.app')

@section('content')

<div class="container">
   
    @include('inc.searchbar',['route' => 'monitoring.hosts'])

    <table class="table table-striped table-bordered table-hover">
        <tr class="bg-primary text-light text-center">
            <th>Host</th>
            <th>Adresse IP</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>
    
        @forelse ($hosts as $host)

            <tr>
                <td>{{$host->display_name}}</td>
                <td>{{$host->address}}</td>
                
                @switch($host->current_state)
                
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
                
                <td>{{$host->last_check}}</td>
                <td class="description">{{$host->output}}</td>
            </tr>
        

        @empty

            <tr>
                <td colspan="5">No result found <strong>{{ request()->query('search') }}</strong></td>
            </tr>

        @endforelse
    </table>

    {{$hosts->appends(['search' => request()->query('search')])->links('vendor.pagination.bootstrap-4')}}

</div>

@endsection