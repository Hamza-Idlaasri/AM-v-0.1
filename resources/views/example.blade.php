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
    
        @forelse ($boxs_statistic as $box_statistic)

            <tr>
                <td>{{$box_statistic->display_name}}</td>
                <td>{{$box_statistic->address}}</td>
                
                @switch($box_statistic->state)
                    
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
                
                <td>{{$box_statistic->state_time}}</td>
                <td class="description">{{$box_statistic->output}}</td>
            </tr>


        @empty

            <tr>
                <td colspan="5">No result found </td>
            </tr>

        @endforelse

    </table>
    
    

</div>


@endsection