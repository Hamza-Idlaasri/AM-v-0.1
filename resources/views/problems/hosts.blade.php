@extends('layouts.app')

@section('content')

<div class="container">
    
    @include('inc.searchbar',['route' => 'problems.hosts'])

    <table class="table table-striped table-bordered table-hover">
        <tr class="bg-primary text-light text-center">
            <th>Host</th>
            <th>Adresse IP</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>
    
        @forelse ($host_problems as $host_problem)

            <tr>
                <td>{{$host_problem->display_name}}</td>
                <td>{{$host_problem->address}}</td>
                
                @switch($host_problem->current_state)

                    @case(1)
                        <td><span class="badge badge-danger">Down</span></td>
                        @break
                            
                    @case(2)
                        <td><span class="badge badge-unknown">Ureachable</span></td>
                        @break
                    
                    @default
                        
                @endswitch
                
                <td>{{$host_problem->last_check}}</td>
                <td class="description">{{$host_problem->output}}</td>
            </tr>

        @empty

            <tr>
                <td colspan="5">No result found <strong>{{ request()->query('search') }}</strong></td>
            </tr>

        @endforelse

    </table>

    {{$host_problems->appends(['search' => request()->query('search')])->links('vendor.pagination.bootstrap-4')}}

</div>

@endsection