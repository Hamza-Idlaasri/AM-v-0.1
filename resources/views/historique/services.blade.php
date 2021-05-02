@extends('layouts.app')

@section('content')

<div class="container">
    <!-- Search bar -->
    {{-- <div class="float-right">
        @include('inc.searchbar',['route' => 'historic.services'])
    </div> --}}
    
    <!-- Filter -->
    <div class="float-right text-primary">
        @include('inc.filter',['names' => $services_name ,'route' => 'historic.services','type' => 'Service','from' => 'historic'])
    </div>

    <!-- Download button -->
    <div class="float-left">
        @include('inc.download', ['route' => 'services.pdf','csv' => 'services.csv'])
    </div>

</div>

<div class="container back">    

    <table class="table table-striped table-bordered">

        <tr  class="bg-primary text-light text-center">
            <th>Host</th>
            <th>Service</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>
    

        @forelse ($services_history as $service_history)

        
        <tr>        
               
            <td>{{$service_history->host_name}}</td> 

            <td>{{$service_history->service_name}}</td>
            
            @switch($service_history->state)
                
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
            
            <td>{{$service_history->state_time}}</td>
            <td class="description">{{$service_history->output}}</td>
        </tr>
 
        @empty

            <tr>
                <td colspan="5">No result found</td>
            </tr>

        @endforelse
        
    </table>

    {{$services_history->appends(['status' => request()->query('status'),'from' => request()->query('from'),'to' => request()->query('to'),'name' => request()->query('name')])->links('vendor.pagination.bootstrap-4')}}

</div>

<script>

const back = document.querySelector('.back');

// const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

// const comparer = (idx, asc) => (a, b) => ((v1, v2) => 
//     v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
//     )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

// // do the work...
// document.querySelectorAll('th').forEach(th => th.addEventListener('click', (() => {
//     const table = th.closest('table');
//     Array.from(table.querySelectorAll('tr:nth-child(n+2)'))
//         .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
//         .forEach(tr => table.appendChild(tr) );
// })));
</script>

@endsection