@extends('layouts.app')

@section('content')

<div class="container">

    {{-- Display Hosts Problems  --}}

    <table class="table table-striped table-bordered table-hover">
        <tr class="bg-primary text-light text-center">
            <th>Host</th>
            <th>Adresse IP</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>
    
        @foreach ($hp as $host_problem)

        @if ($host_problem->alias == 'host' && $host_problem->current_state != 0)

            <tr>
                <td>{{$host_problem->display_name}}</td>
                <td>{{$host_problem->address}}</td>
                
                @switch($host_problem->current_state)
                   
                    @case(1)
                        <td class="bg-danger">Down</td>
                        @break
                    @case(2)
                        <td style="background-color: violet">Ureachable</td>
                        @break
                    @default
                        
                @endswitch
                
                <td>{{$host_problem->last_check}}</td>
                <td>{{$host_problem->output}}</td>
            </tr>
        @endif

        @endforeach
    </table>

    {{-- Display Services Problems  --}}

    <table class="table table-striped table-bordered table-hover">

        <tr  class="bg-primary text-light text-center">
            <th>Host</th>
            <th>Service</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>
    
        <?php $check_host = 0 ?>

        @foreach ($problems as $problem)

        @if ($problem->alias == 'host' && $problem->current_state != 0)
        <tr>

            @if ($check_host == 0 || $problem->host_object_id != $check_host)

                @for ($i=0; $i < sizeof($hosts); $i++) 
                    
                @if($problem->host_object_id == $hosts[$i]->host_object_id)
                    <td>{{$hosts[$i]->display_name}}</td> 
                    <?php $check_host = $problem->host_object_id ?>
                @endif

                @endfor
            @else
                <td></td>
            @endif

            <td>{{$problem->display_name}}</td>
            
            @switch($problem->current_state)
        
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
            
            <td>{{$problem->last_check}}</td>
            <td>{{$problem->output}}</td>
        </tr>
 
        @endif
            

        @endforeach
    </table>

    {{-- Display Boxs Problems  --}}

    <table class="table table-striped table-bordered table-hover">
        <tr class="bg-primary text-light text-center">
            <th>Box</th>
            <th>Adresse IP</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>
    
        @foreach ($hp as $host_problem)

        @if ($host_problem->alias == 'box' && $host_problem->current_state != 0)

            <tr>
                <td>{{$host_problem->display_name}}</td>
                <td>{{$host_problem->address}}</td>
                
                @switch($host_problem->current_state)
                    
                    @case(1)
                        <td class="bg-danger">Down</td>
                        @break
                    @case(2)
                        <td style="background-color: violet">Ureachable</td>
                        @break
                    @default
                        
                @endswitch
                
                <td>{{$host_problem->last_check}}</td>
                <td>{{$host_problem->output}}</td>
            </tr>
        @endif

        @endforeach
    </table>

    {{-- Display Equipements Problems  --}}

    <table class="table table-striped table-bordered table-hover">

        <tr  class="bg-primary text-light text-center">

            <th>Box</th>
            <th>Equipement</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>

        <?php $check_box = 0 ?>
    
        @foreach ($problems as $problem)

        @if ($problem->alias == 'box' && $problem->current_state != 0)

        <tr>

            @if ($check_box == 0 || $problem->host_object_id != $check_box)

                @for ($i=0; $i < sizeof($hosts); $i++) 
                    
                @if($problem->host_object_id == $hosts[$i]->host_object_id)
                    <td>{{$hosts[$i]->display_name}}</td> 
                    <?php $check_box = $problem->host_object_id ?>
                @endif

                @endfor
            @else
                <td></td>
            @endif

            <td>{{$problem->display_name}}</td>
            
            @switch($problem->current_state)
        
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
            
            <td>{{$problem->last_check}}</td>
            <td>{{$problem->output}}</td>
        </tr>
 
        @endif
            

        @endforeach
    </table>

</div>

@endsection
