@extends('layouts.app')

@section('content')

<div class="container">
   
    <table class="table table-striped table-bordered table-hover">
        <tr class="bg-primary text-light text-center">
            <th>Host</th>
            <th>Adresse IP</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>
    
        @foreach ($hosts as $host)

        @if ($host->alias == 'host')
            <tr>
                <td>{{$host->display_name}}</td>
                <td>{{$host->address}}</td>
                
                @switch($host->current_state)
                    @case(0)
                        <td class="bg-success">Up</td>
                        @break
                    @case(1)
                        <td class="bg-danger">Down</td>
                        @break
                    @case(2)
                        <td style="background-color: violet">Ureachable</td>
                        @break
                    @default
                        
                @endswitch
                
                <td>{{$host->last_check}}</td>
                <td>{{$host->output}}</td>
            </tr>
        @endif

        @endforeach
    </table>
</div>

@endsection
