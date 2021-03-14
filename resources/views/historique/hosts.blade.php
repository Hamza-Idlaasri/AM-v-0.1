@extends('layouts.app')

@section('content')

<div class="container">
    <!-- Search bar -->
    {{-- <div class="float-right">
        @include('inc.searchbar',['route' => 'historic.hosts'])
    </div> --}}
    
    {{-- Filter --}}
    <div class="float-right text-primary">
        @include('inc.filter',['names' => $hosts_name,'route' => 'historic.hosts','type' => 'Host'])
    </div>

    <!-- Download button -->
    <div class="float-left">
        @include('inc.download')
    </div>

</div>

<div class="container">


    <table class="table table-striped table-bordered">
        <tr class="bg-primary text-light text-center">
            <th>Host</th>
            <th>Adresse IP</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>
    
        @forelse ($hosts_history as $host_history)
            <tr>
                <td>{{$host_history->display_name}}</td>
                <td>{{$host_history->address}}</td>
                
                @switch($host_history->state)
                
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
                
                <td>{{$host_history->state_time}}</td>
                <td class="description">{{$host_history->output}}</td>
            </tr>

        @empty

            <tr>
                <td colspan="5">No result found {{-- <strong>{{ request()->query('search') }}</strong> --}}</td>
            </tr>

        @endforelse
    </table>

    {{$hosts_history->appends(['status' => request()->query('status'),'from' => request()->query('from'),'to' => request()->query('to'),'name' => request()->query('name')])->links('vendor.pagination.bootstrap-4')}}

</div>

@endsection