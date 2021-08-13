@extends('layouts.app')

@section('content')

<div class="container">

    @include('inc.searchbar',['route' => 'monitoring.equipements'])

    <table class="table table-striped table-bordered table-hover">

        <tr class="bg-primary text-light text-center">

            <th>Box</th>
            <th>Equipement</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>
    
        <?php $check = 0 ?>

        @forelse ($equipements as $equipement)


        <tr>
           
            @if ($check == 0 || $equipement->host_object_id != $check)

                <td><a href="/monitoring/boxs/{{$equipement->host_id}}">{{$equipement->host_name}}</a></td> 

                <?php $check = $equipement->host_object_id ?>
               
            @else
                <td></td>
            @endif
            
            <td><a href="/monitoring/equipements/{{$equipement->service_id}}">{{$equipement->service_name}}</a></td>
            
            @switch($equipement->current_state)
             
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
            
            <td>{{$equipement->last_check}}</td>
            <td class="description">{{$equipement->output}}</td>

        </tr>
 
        @empty

            <tr>
                <td colspan="5">No result found <strong>{{ request()->query('search') }}</strong></td>
            </tr>

        @endforelse     

    </table>

    {{$equipements->appends(['search' => request()->query('search')])->links('vendor.pagination.bootstrap-4')}}

</div>

{{-- reload page --}}
<script>
    setTimeout(function(){
    window.location.reload(1);
    }, 15000);
</script>

@endsection
