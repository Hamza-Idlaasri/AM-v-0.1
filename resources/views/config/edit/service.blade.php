@extends('layouts.app')

@section('content')

<style>
    
.unity{
    background: rgb(211, 210, 210);
    color: black;
    display: flex;
    justify-content: center;
    align-items: center;
    padding:0 10px;
    border-radius: 0 10px 10px 0;
}

.p-unity
{
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

</style>

<div class="container my-3 w-50">

    <form action="{{ route('editService', $service[0]->service_id) }}" method="get">

        <div class="card">

            <div class="card-header">Define Service</div>

            <div class="card-body">
                {{-- service Name --}}
                <label for="service_name"><b>Service name {{--<span class="text-danger">*</span>--}}</b></label>
                <input type="text" name="serviceName" class="form-control @error('serviceName') border-danger @enderror" id="service_name" value="{{ $service[0]->display_name }}">
                @error('serviceName')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror

                {{-- Community --}}
                {{-- @if ($type == 'switch' || $type == 'router' || $type == 'printer')
                    <label for="community"><b>Community String <span class="text-danger">*</span></b></label>
                    <input type="text" name="community" class="form-control @error('community') border-danger @enderror" id="community" value="public">
                    @error('community')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                @endif --}}

            </div>

        </div>
        
        <br>

        <div class="card">

            <div class="card-header">Paramétrage de supervision</div>
            
            <div class="card-body">
                {{-- Check Interval --}}
                <label for="CheckInterval"><b>Check Interval <!--<span class="text-danger">*</span>--></b></label>
                <div class="d-flex">
                    <input  type="number" min="1" max="100" name="check_interval" class="form-control p-unity @error('check_interval') border-danger @enderror" id="CheckInterval" value="{{ $service[0]->check_interval }}">
                    <span class="unity">min</span>
                </div>
                @error('check_interval')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror

                <br>

                {{-- Retry Interval --}}
                <label for="retryInterval"><b>Retry Interval <!--<span class="text-danger">*</span>--></b></label>
                <div class="d-flex">
                    <input  type="number" min="1" max="100" name="retry_interval" class="form-control p-unity @error('retry_interval') border-danger @enderror" id="retryInterval" value="{{ $service[0]->retry_interval }}">
                    <span class="unity">min</span>
                </div>
                @error('retry_interval')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror

                <br>

                {{-- Max Check --}}
                <label for="maxInterval"><b>Max Check Attempts<!--<span class="text-danger">*</span>--></b></label>
                <div class="d-flex">
                    <input  type="number" min="1" max="100" name="max_attempts" class="form-control p-unity @error('max_attempts') border-danger @enderror" id="maxInterval" value="{{ $service[0]->max_check_attempts }}">
                    <span class="unity">attempts</span>
                </div>
                @error('max_attempts')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror

                <br>

                {{-- Notification Interval --}}
                <label for="notifInterval"><b>Notification Interval <!--<span class="text-danger">*</span>--></b></label>
                <div class="d-flex">
                    <input  type="number" min="1" max="1000" name="notif_interval" class="form-control p-unity @error('notif_interval') border-danger @enderror" id="notifInterval" value="{{ $service[0]->notification_interval }}">
                    <span class="unity">min</span>
                </div>
                @error('notif_interval')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror

                <br>

                {{-- Active Check --}}
                <label for="check"><b>Check this service</b></label>
                <select name="check" id="check">

                    @if ($service[0]->active_checks_enabled)
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    @else
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    @endif
                    
                </select>

                <br>

                {{-- Active Notifications --}}
                <label for="activeNotif"><b>Active Notification</b></label>
                <select name="active_notif" id="activeNotif">

                    @if ($service[0]->notifications_enabled)
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    @else
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    @endif
                    
                </select>
            </div>

        </div>
        
        <br>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>

</div>

@endsection