@extends('layouts.app')

@section('content')

<div class="container">
    <table class="table table-striped table-bordered table-hover">
        <tr  class="bg-primary text-light text-center">
            <th>Host</th>
            <th>Service</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>

        <?php $check = 0 ?>
        
        @foreach ($services as $service)        
        
        @if ($service->alias == 'host')
        
        <tr>

            @if ($check == 0 || $service->host_object_id != $check)

                @for ($i=0; $i < sizeof($hosts); $i++) 
                    
                @if($service->host_object_id == $hosts[$i]->host_object_id)
                    <td>{{$hosts[$i]->display_name}}</td> 
                    <?php $check = $service->host_object_id ?>
                @endif

                @endfor
            @else
                <td></td>
            @endif
            


            <td>{{$service->display_name}}</td>
            
            @switch($service->current_state)
                @case(0)
                    <td class="bg-success">Ok</td>
                    @break
                @case(1)
                    <td class="bg-warning">Warning</td>
                    @break
                @case(2)
                    <td class="bg-danger">Critical</td>
                    @break
                @case(3)
                    <td style="background-color: violet">Ureachable</td>
                    @break
                @default
                    
            @endswitch
            
            <td>{{$service->last_check}}</td>
            <td>{{$service->output}}</td>
        </tr>
 
        @endif
            

        @endforeach


        
    </table>
</div>


@endsection
