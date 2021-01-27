@extends('layouts.app')

@section('content')

<div class="container">
    <table class="table table-striped table-bordered table-hover">
        <tr class="bg-primary text-light text-center">
            <th>Box</th>
            <th>Adresse IP</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>
    
        @foreach ($boxs as $box)

        @if ($box->alias == 'box')
            <tr>
                <td>{{$box->display_name}}</td>
                <td>{{$box->address}}</td>
                
                @switch($box->current_state)
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
                
                <td>{{$box->last_check}}</td>
                <td>{{$box->output}}</td>
            </tr>
        @endif

        @endforeach
    </table>
</div>

@endsection
