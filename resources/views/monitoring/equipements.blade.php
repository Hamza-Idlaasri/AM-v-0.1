@extends('layouts.app')

@section('content')

<div class="container">

    <table class="table table-striped table-bordered table-hover">

        <tr class="bg-primary text-light text-center">

            <th>Box</th>
            <th>Equipement</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>
    
        <?php $check = 0 ?>

        @foreach ($equipements as $equipement)

        @if ($equipement->alias == 'box')

        <tr>
           
            @if ($check == 0 || $equipement->host_object_id != $check)

                @for ($i=0; $i < sizeof($hosts); $i++) 
                    
                @if($equipement->host_object_id == $hosts[$i]->host_object_id)
                    <td>{{$hosts[$i]->display_name}}</td> 
                    <?php $check = $equipement->host_object_id ?>
                @endif

                @endfor
            @else
                <td></td>
            @endif
            
            <td>{{$equipement->display_name}}</td>
            
            @switch($equipement->current_state)
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
            
            <td>{{$equipement->last_check}}</td>
            <td>{{$equipement->output}}</td>

        </tr>
 
        @endif
            

        @endforeach
    </table>
</div>

@endsection
