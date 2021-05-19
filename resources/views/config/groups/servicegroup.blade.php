@extends('layouts.app')

@section('content')

<div class="container p-4 d-flex justify-content-center flex-wrap">

    @forelse ($servicegroups as $group)
        <div class="container w-50">
            <h5 class="text-center"><a href="{{ route('SGdetails', $group->servicegroup_id) }}">{{ $group->servicegroup }}</a></h5>
            <table class="table table-bordered text-center">
                <tr class="text-primary">
                    <th>Host</th>
                    <th>Status</th>
                    <th>Service</th>
                </tr>

                @forelse ($members as $member)

                    @if ($member->servicegroup_id == $group->servicegroup_id)
                        <tr>
                            <td>{{ $member->host_name }}</td>

                            @switch($member->host_state)

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

                            <td>

                                @switch($member->service_state)
                                    @case(0)
                                        <span class="badge badge-success">{{ $member->service_name }}</span>
                                        @break
                                    @case(1)
                                        <span class="badge badge-warning">{{ $member->service_name }}</span>
                                        @break
                                    @case(2)
                                        <span class="badge badge-danger">{{ $member->service_name }}</span>
                                        @break
                                    @case(3)
                                        <span class="badge badge-unknown">{{ $member->service_name }}</span>
                                        @break
                                    @default
                                        
                                @endswitch
            
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