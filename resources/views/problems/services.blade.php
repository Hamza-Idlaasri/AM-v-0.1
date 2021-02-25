@extends('layouts.app')

@section('content')

<div class="container">

    @include('inc.searchbar',['route' => 'problems.services'])

    <table class="table table-striped table-bordered table-hover">

        <tr  class="bg-primary text-light text-center">
            <th>Host</th>
            <th>Service</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>
    
        <?php $check_host = 0 ?>

        @forelse ($service_problems as $service_problem)

        <tr>

            @if ($check_host == 0 || $service_problem->host_object_id != $check_host)
       
                <td>{{$service_problem->host_name}}</td> 

                <?php $check_host = $service_problem->host_object_id ?>
              
            @else
                <td></td>
            @endif

            <td>{{$service_problem->service_name}}</td>
            
            @switch($service_problem->current_state)
        
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
            
            <td>{{$service_problem->last_check}}</td>
            <td class="description">{{$service_problem->output}}</td>
        </tr>

        @empty

            <tr>
                <td colspan="5">No result found <strong>{{ request()->query('search') }}</strong></td>
            </tr>

        @endforelse
    </table>

    {{$service_problems->appends(['search' => request()->query('search')])->links('vendor.pagination.bootstrap-4')}}

</div>

@endsection