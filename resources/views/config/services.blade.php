@extends('layouts.app')

@section('content')

<div class="container my-3">

    {{-- <form action="" class="float-left">
        <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Add New</button>
    </form> --}}

    @include('inc.searchbar',['route' => 'configServices'])

</div>

<div class="container my-3">
    <table class="table table-striped table-bordered" >

        <tr class="bg-primary text-light text-center">
            <th>Host</th>
            <th>Service</th>
            <th>Description</th>
            <th>Check Command</th>
            <th>Check Interval</th>
            <th>Retry Interval</th>
            <th>Max Check</th>
            <th>Check</th>
            <th>Notif</th>
            <th>Edit</th>
        </tr>

        @forelse ($services as $service)
            <tr>
            
                <td>{{ $service->host_name }}</td>
                <td>{{ $service->service_name }} </td>
                <td>{{ $service->output }}</td>
                <td style="word-break: break-all;width:150px">{{ $service->check_command }}</td>
                <td>{{ $service->normal_check_interval }}</td>
                <td>{{ $service->retry_check_interval }}</td>
                <td>{{ $service->max_check_attempts }}</td>

                @if ($service->has_been_checked)
                    <td>true</td>
                @else
                    <td>false</td>
                @endif
                
                @if ($service->notifications_enabled)
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
                    <form action="{{ route('deleteService', $service->service_object_id) }}" method="get" class="float-right">
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

    {{$services->appends(['search' => request()->query('search')])->links('vendor.pagination.bootstrap-4')}}

</div>

@endsection