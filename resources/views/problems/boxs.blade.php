@extends('layouts.app')

@section('content')

<div class="container">

    @include('inc.searchbar',['route' => 'problems.boxs'])

    <table class="table table-striped table-bordered table-hover">
        <tr class="bg-primary text-light text-center">
            <th>Box</th>
            <th>Adresse IP</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>

        @forelse ($boxs_problems as $boxs_problem)

            <tr>
                <td>
                    <a href="/problems/boxs/{{$boxs_problem->host_id}}">{{$boxs_problem->display_name}}</a>
                    
                    @if ($boxs_problem->is_flapping)
                        <span class="float-right text-danger" title="This Host is flapping"><i class="fas fa-retweet"></i></span>
                    @endif
                </td>

                <td>{{$boxs_problem->address}}</td>
                
                @switch($boxs_problem->current_state)
                

                    @case(1)
                        <td><span class="badge badge-danger">Down</span></td>
                        @break
                            
                    @case(2)
                        <td><span class="badge badge-unknown">Ureachable</span></td>
                        @break

                    @default
                        
                @endswitch
                
                <td>{{$boxs_problem->last_check}}</td>
                <td class="description">{{$boxs_problem->output}}</td>
            </tr>
    

        @empty

            <tr>
                <td colspan="5">No result found <strong>{{ request()->query('search') }}</strong></td>
            </tr>

        @endforelse

    </table>

    {{$boxs_problems->appends(['search' => request()->query('search')])->links('vendor.pagination.bootstrap-4')}}

</div>

@endsection