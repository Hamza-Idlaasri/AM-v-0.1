@extends('layouts.app')

@section('content')

<div class="container">

    @include('inc.searchbar',['route' => 'problems.equipements'])

    <table class="table table-striped table-bordered table-hover">

        <tr  class="bg-primary text-light text-center">

            <th>Box</th>
            <th>Equipement</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>

        <?php $check_box = 0 ?>
    
        @forelse ($equipement_problems as $equipement_problem)

        <tr>

            @if ($check_box == 0 || $equipement_problem->host_object_id != $check_box)

    
                <td>{{$equipement_problem->host_name}}</td> 
            
                <?php $check_box = $equipement_problem->host_object_id ?>
                
            @else
                <td></td>
            @endif

            <td>{{$equipement_problem->service_name}}</td>
            
            @switch($equipement_problem->current_state)
        
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
            
            <td>{{$equipement_problem->last_check}}</td>
            <td class="description">{{$equipement_problem->output}}</td>
        </tr>
    
        @empty

            <tr>
                <td colspan="5">No result found <strong>{{ request()->query('search') }}</strong></td>
            </tr>

        @endforelse

    </table>

   
    {{$equipement_problems->appends(['search' => request()->query('search')])->links('vendor.pagination.bootstrap-4')}}

</div>

@endsection