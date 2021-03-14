@extends('layouts.app')

@section('content')

<div class="container my-3">
    <form action="" method="get">
    <div class="card w-25 float-left">

        <div class="card-header">
            <h4>Define Host</h4>
        </div>

        <div class="card-body">
            
                <label for="host_name">Host Name :</label>
                <input type="text" name="host_name" class="form-control" id="host_name" value="{{ $host[0]->display_name }}">
                <br>
                <label for="address">Address :</label>
                <input type="text" name="address" class="form-control" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" value="{{ $host[0]->address }}">
                <br>
                <label for="check_interval">Check Interval :</label>
                <input type="number" min="1" name="check_interval" class="form-control" id="check_interval" value="{{ $host[0]->normal_check_interval }}">
                <br>
                <label for="retry_interval">Retry Interval :</label>
                <input type="number" min="1" name="retry_interval" class="form-control" id="retry_interval" value="{{ $host[0]->retry_check_interval }}">
                <br>
                <label for="max_chek">Max Chek :</label>
                <input type="number" min="1" name="max_chek" class="form-control" id="max_chek" value="{{ $host[0]->max_check_attempts }}">
                <br>
                <label for="check" class="d-inline">Check :</label>
                <select name="check" class="form-control d-inline" id="check" style="width: 100px">
                    @if ($host[0]->has_been_checked)
                        <option value="true" selected>true</option>
                        <option value="fale">false</option>
                    @else
                        <option value="true">true</option>
                        <option value="fale" selected>false</option>
                    @endif
                </select>
                <br><br>
                <label for="notif" class="d-inline">Get Notification :</label>
                <select name="notif" class="form-control d-inline" id="notif" style="width: 100px">
                    @if ($host[0]->notifications_enabled)
                        <option value="true" selected>true</option>
                        <option value="fale">false</option>
                    @else
                        <option value="true">true</option>
                        <option value="fale" selected>false</option>
                    @endif
                </select>
               
        </div>

    </div>

    <div class="card w-75 float-right">
        <div class="card-header">
            <h4>Define Services</h4>
        </div>

        <div class="card-body d-flex flex-wrap p-2">
            @foreach ($services as $service)
                <div class="card w-50 my-3">
                    <div class="card-header">
                        <h6>{{ $service->service_name }}</h6>
                    </div>

                    <div class="card-body">
                        <label for="service_name">Service Name :</label>
                        <input type="text" name="service_name" class="form-control" id="service_name" value="{{ $service->service_name }}">
                        <br>
                        <label for="command">Command :</label>
                        <input type="text" name="command" class="form-control" id="command" value="{{ $service->service_name }}">
                        <br>
                        <label for="check_interval">Check Interval :</label>
                        <input type="number" min="1" name="check_interval" class="form-control" id="check_interval" value="{{ $service->normal_check_interval }}">
                        <br>
                        <label for="retry_interval">Retry Interval :</label>
                        <input type="number" min="1" name="retry_interval" class="form-control" id="retry_interval" value="{{ $service->retry_check_interval }}">
                        <br>
                        <label for="max_chek">Max Chek :</label>
                        <input type="number" min="1" name="max_chek" class="form-control" id="max_chek" value="{{ $service->max_check_attempts }}">
                        <br>
                        <label for="check" class="d-inline">Check :</label>
                        <select name="check" class="form-control d-inline" id="check" style="width: 100px">
                            @if ($service->has_been_checked)
                                <option value="true" selected>true</option>
                                <option value="fale">false</option>
                            @else
                                <option value="true">true</option>
                                <option value="fale" selected>false</option>
                            @endif
                        </select>
                        <br><br>
                        <label for="notif" class="d-inline">Get Notification :</label>
                        <select name="notif" class="form-control d-inline" id="notif" style="width: 100px">
                            @if ($service->notifications_enabled)
                                <option value="true" selected>true</option>
                                <option value="fale">false</option>
                            @else
                                <option value="true">true</option>
                                <option value="fale" selected>false</option>
                            @endif
                        </select>
                    </div>
                </div>
                
            @endforeach
        </div>

    </div>

    {{-- <button type="submit" class="btn btn-primary">Save</button> --}}
</form>
</div>

@endsection