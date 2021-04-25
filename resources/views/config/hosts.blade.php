@extends('layouts.app')

@section('content')

<div class="container my-3">

    <a href="{{ route('hostType')}}" type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Add New</a>
    
    @include('inc.searchbar',['route' => 'configHosts'])

</div>

<div class="container my-3">

    <table class="table table-striped table-bordered">

        <tr class="bg-primary text-light text-center">
            <th>Host</th>
            <th>Address</th>
            <th>Description</th>
            <th>Check Command</th>
            <th>Check Interval</th>
            <th>Retry Interval</th>
            <th>Max Check</th>
            <th>Check</th>
            <th>Notif</th>
            <th>Edit</th>
        </tr>

        @forelse ($hosts as $host)
            <tr>
            
                <td>{{ $host->display_name }}</td>
                <td>{{ $host->address }}</td>
                <td>{{ $host->output }}</td>
                <td>{{ $host->check_command }}</td>
                <td>{{ $host->normal_check_interval }}</td>
                <td>{{ $host->retry_check_interval }}</td>
                <td>{{ $host->max_check_attempts }}</td>

                @if ($host->has_been_checked)
                    <td>true</td>
                @else
                    <td>false</td>
                @endif
                
                @if ($host->notifications_enabled)
                    <td>true</td>
                @else
                    <td>false</td>
                @endif

                <td style="width: 80px">
                    {{-- Edit --}}
                    <form action="{{ route('editHost', $host->host_object_id) }}" method="get" class="float-left">
                        <button type="submit" class="text-primary btn"><i class="fas fa-pen"></i></button>
                    </form>

                    {{-- Delete --}}
                    <form action="{{ route('deleteHost', $host->host_id) }}" method="get" class="float-right">
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

    {{$hosts->appends(['search' => request()->query('search')])->links('vendor.pagination.bootstrap-4')}}

</div>

@endsection