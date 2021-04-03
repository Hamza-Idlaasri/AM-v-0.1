@extends('layouts.app')

@section('content')

<div class="container">
    <!-- Search bar -->
    {{-- <div class="float-right">
        @include('inc.searchbar',['route' => 'historic.boxs'])
    </div> --}}
    
    {{-- Filter --}}
    <div class="float-right text-primary">
        @include('inc.filter',['names' => $boxs_name,'route' => 'historic.boxs','type' => 'Box','from' => 'historic'])
    </div>

    <!-- Download button -->
    <div class="float-left">
        @include('inc.download')
    </div>

</div>

<div class="container back">
    

    <table class="table table-striped table-bordered">
        <tr class="bg-primary text-light text-center">
            <th>Box</th>
            <th>Adresse IP</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>
    
        @forelse ($boxs_history as $box_history)

            <tr>
                <td>{{$box_history->display_name}}</td>
                <td>{{$box_history->address}}</td>
                
                @switch($box_history->state)
                    
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
                
                <td>{{$box_history->state_time}}</td>
                <td class="description">{{$box_history->output}}</td>
            </tr>


        @empty

            <tr>
                <td colspan="5">No result found</td>
            </tr>

        @endforelse

    </table>
    
    {{$boxs_history->appends(['status' => request()->query('status'),'from' => request()->query('from'),'to' => request()->query('to'),'name' => request()->query('name')])->links('vendor.pagination.bootstrap-4')}}

</div>

<script>
    const back = document.querySelector('.back');
</script>

@endsection