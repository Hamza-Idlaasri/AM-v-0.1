@extends('layouts.app')

@section('content')

<style>
    .table-details td{
        background-color : rgba(240, 240, 240, 0.541); 
        font-size: 14px;
        /* font-weight: bold; */
        text-align: left
    }
    .table-details{
        width: 70%;
        margin:auto
    }
    .container{
        padding: 20px 0px
    }
    .left-coll{
        width: 25%;
    }
</style>

<div class="container text-center">
    
    @if ($details[0]->alias == 'box')
        <h4>Equipement : <strong>{{ $details[0]->service_name }}</strong></h4>
        
        <h6>On Box : <strong>{{ $details[0]->host_name }}</strong></h6>
    @else
        <h4>Service : <strong>{{ $details[0]->service_name }}</strong></h4>
        
        <h6>On Host : <strong>{{ $details[0]->host_name }}</strong></h6>
    @endif
    
    
    <table class="table table-bordered table-details">

        <tr>
            <td class="left-coll">Current State</td>
            @switch($details[0]->current_state)

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
        </tr>

        <tr>
            <td class="left-coll">State Information</td>
            <td>{{ $details[0]->output }}</td>
        </tr>

        <tr>
            <td class="left-coll">Current Attempt</td>
            <td>{{ $details[0]->current_check_attempt }} / {{$details[0]->max_check_attempts}} 
                @switch($details[0]->state_type)
                    @case(0)
                        ( SOFT state )
                        @break
                    @case(1)
                        ( HARD state )
                        @break
                    @default
                        
                @endswitch
            </td>
        </tr>

        <tr>
            <td class="left-coll">Execution Time</td>
            <td>{{  $details[0]->execution_time }} s</td>
        </tr>

        <tr>
            <td class="left-coll">Last Check</td>
            <td>{{ $details[0]->last_check}}</td>
        </tr>
        <tr>
            <td class="left-coll">Next Check</td>
            <td>{{ $details[0]->next_check}}</td>
        </tr>
        <tr>
            <td class="left-coll">Last Update</td>
            <td>{{ $details[0]->status_update_time }}</td>
        </tr>
        <tr>
            <td class="left-coll">Flapping</td>

            @switch($details[0]->is_flapping)
                @case(0)
                    <td>NO</td>
                    @break
                @case(1)
                    <td>YES</td>
                    @break
                @default
                    
            @endswitch
            
        </tr>
        <tr>
            <td class="left-coll">Check Type</td>

            @switch($details[0]->check_type)
                @case(0)
                    <td>ACTIVE</td>
                    @break
                @case(1)
                    <td>PASSIVE</td>
                    @break
                @default
                    
            @endswitch
        </tr>

        

    </table>
</div>
    
@endsection