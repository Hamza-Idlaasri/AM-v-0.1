@extends('layouts.app')

@section('content')

<div class="container my-3">
    <table class="table table-bordered table-hover">
        <thead class="text-center bg-primary text-white">
            <tr>
                <th>Host</th>
                <th>Service</th>
                <th>Status</th>
                <th>Dernier verification</th>
                <th>Description</th>
            </tr>
        </thead>

            <h4 class="text-center">Equipementgroup : <b>{{ $equipgroup[0]->alias }}</b></h4>

            @forelse ($members as $member)
                <tr>
                           
                    <td>{{$member->host_name}}</td> 

                    <td>{{ $member->service_name }}</td>

                    @switch($member->service_state)
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

                    <td>{{ $member->last_check }}</td>
                    <td class="description">{{ $member->output }}</td>

                </tr>

            @empty
                <tr>
                    <td colspan="5">No results found</td>
                </tr>
            @endforelse
        
    </table>
</div>

@endsection