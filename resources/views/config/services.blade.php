@extends('layouts.app')

@section('content')

<style>

    .pop {
        display: none;
        width: 350px;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
    }
    
</style>

<div class="m-3">

    {{-- <form action="" class="float-left">
        <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Add New</button>
    </form> --}}

    @include('inc.searchbar', ['route' => 'configServices'])

</div>

<div class="m-3">
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

        <?php $i=0?>

        @forelse ($services as $service)

            <?php $i++ ?>

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
                    <form action="{{ route('serviceDetails', $service->service_id) }}" class="float-left">
                        <button type="submit" class="text-primary btn"><i class="fas fa-pen"></i></button>
                    </form>

                    {{-- Delete User --}}
                    <button title="delete" class="float-right text-danger btn" onclick="show({{$i}})"><i class="fas fa-trash"></i></button>
                        
                    <div class="popup{{$i}} container p-3 bg-white shadow rounded pop" style="opacity:1">
                        <h6><b>Are you sure?</b></h6>
                        <p>Do you really you want to delete this Service <b>"{{$service->service_name}}"</b> ?</p>
                        <form action="{{ route('deleteService', $service->service_id) }}" method="get" class="d-inline">
                            <button type="submit" title="delete" class="btn btn-danger">Delete</button>
                        </form>
                        <button type="submit" title="Cancel" class="btn btn-light border border-secondary d-inline" onclick="cancel({{$i}})">Cancel</button>
                    </div>

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

<script>
    
    show = (i) => {
        document.querySelector('.popup'+i).style.display = 'block';
    }
    
    cancel = (i) => {
        document.querySelector('.popup'+i).style.display = 'none';
    }
    
</script>

@endsection