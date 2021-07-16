@extends('layouts.app')

<style>
    .setting {
        opacity: .2;
        transition-duration: .3s
    }
    .setting:hover{
        opacity: 1;
    }
</style>

@section('content')

    <div class="container p-3">
        <a href="{{ route('addHG') }}" class="btn btn-success"><i class="fas fa-plus"></i> Add New</a>
    </div>

    <div class="container p-4 d-flex justify-content-center flex-wrap">
        @forelse ($hostgroups as $group)
            <div class="container w-50">
                <h5 class="text-center">
                    <a href="{{ route('HGdetails', $group->hostgroup_id) }}">{{ $group->alias }}</a>

                    <span class="float-right setting">
                        <a href="{{route('manageHG', $group->hostgroup_id)}}" class="mx-2"><i class="fas fa-pen fa-xs"></i></a>
                        <a href="{{route('deleteHG', $group->hostgroup_id)}}" class="mx-2 text-danger"><i class="fas fa-trash-alt fa-xs"></i></a>
                    </span>
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
        
 
        
@endsection
