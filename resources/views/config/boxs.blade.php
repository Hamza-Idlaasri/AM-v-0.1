@extends('layouts.app')

@section('content')

<div class="container my-3">

    <a href="{{ route('manageBox') }}" class="btn btn-success float-left"><i class="fas fa-plus"></i> Add New</a>

    @include('inc.searchbar',['route' => 'configBoxs'])

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

        @forelse ($boxs as $box)
            <tr>
            
                <td>{{ $box->display_name }}</td>
                <td>{{ $box->address }}</td>
                <td>{{ $box->output }}</td>
                <td>{{ $box->check_command }}</td>
                <td>{{ $box->normal_check_interval }}</td>
                <td>{{ $box->retry_check_interval }}</td>
                <td>{{ $box->max_check_attempts }}</td>

                @if ($box->has_been_checked)
                    <td>true</td>
                @else
                    <td>false</td>
                @endif
                
                @if ($box->notifications_enabled)
                    <td>true</td>
                @else
                    <td>false</td>
                @endif

                <td style="width: 80px">
                    {{-- Edit --}}
                    <form action="" class="float-left">
                        <button type="submit" class="text-primary btn"><i class="fas fa-pen"></i></button>
                    </form>

                    {{-- Delete --}}
                    <form action="{{ route('deleteBox', $box->host_id) }}" class="float-right">
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

    {{$boxs->appends(['search' => request()->query('search')])->links('vendor.pagination.bootstrap-4')}}

</div>

@endsection