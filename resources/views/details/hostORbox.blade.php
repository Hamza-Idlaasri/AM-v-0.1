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
    <h4>{{ $details[0]->display_name }}</h4>
    <h6>{{ $details[0]->address}}</h6>
    <table class="table table-bordered table-details">

        <tr>
            <td class="left-coll font-weight-bolder">Current State</td>
            @switch($details[0]->current_state)
                
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
        </tr>

        <tr>
            <td class="left-coll font-weight-bolder">State Information</td>
            <td>{{ $details[0]->output }}</td>
        </tr>

        <tr>
            <td class="left-coll font-weight-bolder">Current Attempt</td>
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
            <td class="left-coll font-weight-bolder">Execution Time</td>
            <td>{{  $details[0]->execution_time }} s</td>
        </tr>

        <tr>
            <td class="left-coll font-weight-bolder">Last Check</td>
            <td>{{ $details[0]->last_check}}</td>
        </tr>
        <tr>
            <td class="left-coll font-weight-bolder">Next Check</td>
            <td>{{ $details[0]->next_check}}</td>
        </tr>
        <tr>
            <td class="left-coll font-weight-bolder">Last Update</td>
            <td>{{ $details[0]->status_update_time }}</td>
        </tr>
        <tr>
            <td class="left-coll font-weight-bolder">Flapping</td>

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
            <td class="left-coll font-weight-bolder">Check Type</td>

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