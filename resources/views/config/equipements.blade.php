@extends('layouts.app')

@section('content')

<div class="mx-4 my-3">

    <form action="" class="float-left">
        <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Add New</button>
    </form>

    @include('inc.searchbar',['route' => 'configEquips'])
</div>

<div class="my-3 mx-4">
    <table class="table table-striped table-bordered" >

        <tr class="bg-primary text-light text-center">
            <th>Host</th>
            <th>Equipement</th>
            <th>Description</th>
            <th>Check Commande</th>
            <th>Check Interval</th>
            <th>Retry Interval</th>
            <th>Max Check</th>
            <th>Check</th>
            <th>Notif</th>
            <th>Edit</th>
        </tr>

        @forelse ($equipements as $equipement)
            <tr>
            
                <td>{{ $equipement->host_name }}</td>
                <td>{{ $equipement->service_name }} </td>
                <td>{{ $equipement->output }}</td>
                <td style="word-break: break-all;width:150px">{{ $equipement->check_command }}</td>
                <td>{{ $equipement->normal_check_interval }}</td>
                <td>{{ $equipement->retry_check_interval }}</td>
                <td>{{ $equipement->max_check_attempts }}</td>

                @if ($equipement->has_been_checked)
                    <td>true</td>
                @else
                    <td>false</td>
                @endif
                
                @if ($equipement->notifications_enabled)
                    <td>true</td>
                @else
                    <td>false</td>
                @endif

                <td style="width: 100px">
                    {{-- Edit --}}
                    <form action="" class="float-left">
                        <button type="submit" class="text-primary btn"><i class="fas fa-pen"></i></button>
                    </form>

                    {{-- Delete --}}
                    <form action="" class="float-right">
                        <button type="submit" class="text-danger btn"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            
            </tr>

        @empty
            <tr>
                <td colspan="10">No result found <strong>{{ request()->query('search') }}</strong></td>
            </tr>
        @endforelse
        
    </table>

    {{$equipements->appends(['search' => request()->query('search')])->links('vendor.pagination.bootstrap-4')}}

</div>

@endsection