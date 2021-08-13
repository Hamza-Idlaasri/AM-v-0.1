@extends('layouts.app')

@section('content')

    <div class="container">

        @include('inc.searchbar',['route' => 'monitoring.hosts'])

        <table class="table table-striped table-bordered text-center table-hover">

            <thead class="bg-primary text-white">
                <tr>
                    <th>Host</th>
                    <th>Adresse IP</th>
                    <th>Status</th>
                    <th>Dernier verification</th>
                    <th>Description</th>
                </tr>
            </thead>

            @forelse ($hosts as $host)

                <tr>
                    <td>
                        <a href="/monitoring/hosts/{{ $host->host_id }}">{{ $host->display_name }}</a>

                        @if ($host->is_flapping)
                            <span class="float-right text-danger" title="This Host is flapping"><i
                                    class="fas fa-retweet"></i></span>
                        @endif

                    </td>
                    <td>{{ $host->address }}</td>

                    @switch($host->current_state)

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

                    <td>{{ $host->last_check }}</td>
                    <td class="description">{{ $host->output }}</td>
                </tr>


                @empty

                    <tr>
                        <td colspan="5">No result found <strong>{{ request()->query('search') }}</strong></td>
                    </tr>

                @endforelse

            </table>

            {{ $hosts->appends(['search' => request()->query('search')])->links('vendor.pagination.bootstrap-4') }}

        </div>

{{-- reload page --}}
<script>
    setTimeout(function(){
    window.location.reload(1);
    }, 15000);
</script>

@endsection
