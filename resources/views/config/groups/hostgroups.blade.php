@extends('layouts.app')

<style>
    .setting {
        opacity: .2;
        transition-duration: .3s
    }
    .setting:hover{
        opacity: 1;
    }

    .pop {
        width: 200px;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
    }
</style>

@section('content')

<div class="container p-3">
    <a href="{{ route('addHG') }}" class="btn btn-success"><i class="fas fa-plus"></i> Add New</a>
</div>

<div class="container p-4 d-flex justify-content-center flex-wrap">
        
    <?php $i=0?>
        
    @forelse ($hostgroups as $group)

        <?php $i++ ?>

        <div class="container w-50">
            <h5 class="text-center">

                <a href="{{ route('HGdetails', $group->hostgroup_id) }}">{{ $group->alias }}</a>

                <span class="float-right setting">
                    <a href="{{route('manageHG', $group->hostgroup_id)}}" class="mx-2"><i class="fas fa-pen fa-xs"></i></a>
                    <button title="delete" class="btn mx-2 text-danger" onclick="show({{$i}})" style="outline: none"><i class="fas fa-trash"></i></button>
                </span>

                <div class="popup{{$i}} container p-3 bg-white shadow rounded pop w-50" style="display: none">
                    <h6><b>Are you sure?</b></h6>
                    <p>Do you really you want to delete this hostgroup <b>"{{$group->alias}}"</b> ?</p>
                    <a href="{{route('deleteHG', $group->hostgroup_id)}}" class="btn btn-danger d-inline">Delete</a>
                    <button type="submit" title="Cancel" class="btn btn-light border border-secondary d-inline" onclick="cancel({{$i}})">Cancel</button>
                </div>

            </h5>
            <table class="table table-bordered text-center">
                <tr class="text-primary">
                    <th>Host</th>
                    <th>Status</th>
                    <th>Service</th>
                </tr>

                @forelse ($members as $member)

                    @if ($member->hostgroup_id == $group->hostgroup_id)
                        <tr>
                            <td>{{ $member->host_name }}</td>

                            @switch($member->current_state)

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

                            <?php
                                $ok = 0;
                                $warning = 0;
                                $critical = 0;
                                $unknown = 0;
                            ?>

                            <td>
                                @foreach ($member_services as $services)
                                    @if ($services->host_object_id == $member->host_object_id)
                                        <?php switch ($services->current_state) {
                                    case 0:
                                        $ok++;
                                        break;
                                    case 1:
                                        $warning++;
                                        break;
                                    case 2:
                                        $critical++;
                                        break;
                                    case 3:
                                        $unknown++;
                                        break;
                                        } ?>
                                    @endif
                                @endforeach
                                <span class="badge badge-success">{{ $ok }}</span>
                                <span class="badge badge-warning">{{ $warning }}</span>
                                <span class="badge badge-danger">{{ $critical }}</span>
                                <span class="badge badge-unknown">{{ $unknown }}</span>
                            </td>
                        </tr>
                    @endif

                    @empty
                        <tr>
                            <td colspan="3">No members found</td>
                        </tr>
                @endforelse


                </table>
            </div>
            @empty
                <h6>No hostgroups existe</h6>
            @endforelse

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
