@extends('layouts.app')

<style>

label:hover{
    font-weight: bolder;
    cursor: pointer;
}

#cancel{
    widows: 5px;
    height: 5px;
    padding:0 5px;
    cursor: pointer;
    color: red
}

#cancel:hover{
    font-weight: bolder;
}

</style>

@section('content')
    
<div class="container back">

    <form action="{{ route('addService') }}" method="get">

    <div class="card container mt-3 mx-2 bg-white p-0 rounded">

        <div class="card-header">
            <h5 class="font-weight-bolder">Choose Service : <span class="text-danger font-weight-bolder">*</span></h5>
        </div>
        
        <div class="card-text text-center text-muted mt-3">
            <h6>choose Service depend on type of Host</h6>
            @error('service')
                <p class="alert alert-danger w-25 m-auto text-center">
                    <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                </p>
            @enderror
        </div>

        <div class="card-body d-flex justify-content-around">

            {{-- Windows --}}
            <div class="card shadow-sm mx-1" style="width: 200px">
                <div class="card-header bg-primary text-white text-center">Windows</div>
                <div class="card-body">
                    
                    @for ($i = 0; $i < sizeof($windows); $i++)
                        <input type="radio" name="service" value="{{ $windows[$i] }}" id="{{ $windows[$i] }}"> <label for="{{ $windows[$i] }}">{{ $windows[$i] }}</label>
                        <br>
                    @endfor
                        <input type="radio" name="service" value="Uptime(windows)" id="Uptime(windows)"> <label for="Uptime(windows)">Uptime</label>
                    
                </div>
            </div>

            {{-- Linux --}}
            <div class="card shadow-sm mx-1" style="width: 200px">
                <div class="card-header bg-primary text-white text-center">Linux</div>
                <div class="card-body">

                    <input type="radio" name="service" value="PING(linux)" id="PING(linux)"> <label for="PING(linux)">PING</label>
                    <br>
                    @for ($i = 0; $i < sizeof($linux); $i++)
                        <input type="radio" name="service" value="{{ $linux[$i] }}" id="{{ $linux[$i] }}"> <label for="{{ $linux[$i] }}">{{ $linux[$i] }}</label>
                        <br>
                    @endfor
                </div>
            </div>

            {{-- Router --}}
            <div class="card shadow-sm mx-1" style="width: 200px">
                <div class="card-header bg-primary text-white text-center">Router</div>
                <div class="card-body">

                    <input type="radio" name="service" value="PING(router)" id="PING(router)"> <label for="PING(router)">PING</label>
                    <br>
                    <input type="radio" name="service" value="Port n link status(router)" id="Port n link status(router)"> <label class="portNumber" for="Port n link status(router)">Port n Link Status</label>
                    <br>
                    <input type="radio" name="service" value="Uptime(router)" id="Uptime(router)"> <label for="Uptime(router)">Uptime</label>
                    <br>
                    
                </div>
            </div>

            {{-- Switch --}}
            <div class="card shadow-sm mx-1" style="width: 200px">
                <div class="card-header bg-primary text-white text-center">Switch</div>
                <div class="card-body">
                    <input type="radio" name="service" value="PING(switch)" id="PING(switch)"> <label for="PING(switch)">PING</label>
                    <br>
                    <input type="radio" name="service" value="Port n link status(switch)" id="Port n link status(switch)"> <label class="portNumber" for="Port n link status(switch)">Port n Link Status</label>
                    <br>
                    <input type="radio" name="service" value="Uptime(switch)" id="Uptime(switch)"> <label for="Uptime(switch)">Uptime</label>
                    <br>
                    <input type="radio" name="service" value="Port n Bandwidth Usage" id="Port n Bandwidth Usage"> <label class="portNumber" for="Port n Bandwidth Usage">Port n Bandwidth Usage</label>
                    
                </div>
            </div>

            {{-- Printer --}}
            <div class="card shadow-sm mx-1" style="width: 200px">
                <div class="card-header bg-primary text-white text-center">Printer</div>
                <div class="card-body">
                    <input type="radio" name="service" value="PING(printer)" id="PING(printer)"> <label for="PING(printer)">PING</label>
                    <br>
                    <input type="radio" name="service" value="Printer Status" id="Printer Status"> <label for="Printer Status">Printer Status</label>
                    <br>
                </div>
            </div>

        </div>

    </div>

    <div class="card container p-0 mt-3 mx-2 bg-white rounded">
        <div class="card-header">
            <h5 class="font-weight-bolder">For the Host : <span class="text-danger font-weight-bolder">*</span></h5>
        </div>
        
        <div class="card-body">
           <div class="p-2" style="max-height: 200px; overflow: auto">
                @error('host')
                    <p class="alert alert-danger w-25 m-auto text-center">
                        <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                    </p>
                @enderror
                @foreach ($hosts as $host)
                    <input type="radio" name="host" value="{{ $host->host_name }}" id="{{ $host->host_name }}"> <label for="{{ $host->host_name }}">{{ $host->host_name }}</label>
                    <br>
                @endforeach

            </div> 
        </div>
        
    </div>    

    <button type="submit" class="btn btn-primary m-2" id="addService">Add</button>

    <div class="contaaner rounded shadow-lg bg-white border w-25 p-2 pb-4" id="port" style="display:none; position: absolute;top: 50%;left: 50%;transform: translate(-50%); ">
        <span class="float-right" id="cancel">X</span>
        <br><br>
        <h6 class="font-weight-bolder text-secondary">Wich port number you want to check?</h6>
        
        <input  type="number" min="1" max="50" name="portNbr" class="form-control w-100 @error('portNbr') border-danger @enderror" id="PortNbr" value="1">
        @error('portNbr')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>

    </form>

</div>


<script>

    const addS = document.getElementById('addService');
    const cancel = document.getElementById('cancel');
    const port = document.getElementById('port');
    const portNumber = document.getElementsByClassName('portNumber');

    cancel.onclick = () => {
        port.style.display = 'none';
    }

    portNumber.onclick = () => {
        port.style.display = 'block';
    }

</script>

{{-- <div class="container w-50 p-2 bg-white shadow rounded popup" style="display: none; position: absolute;top:50%;left:50%;  transform: translate(-50%,-50%);height: 250px">
    
    <form action="" method="get">
        <h5 class="font-weight-bolder">Add Service : </h5>
        <p class="text-muted">You can change name of service</p>

        <input type="text" name="service-name" id="serviceName" value="">

        <br>
        <h5 class="font-weight-bolder">To the Host :</h5>
        <p id="hostName"></p>

        <div>
            <button type="submit" class="btn btn-primary d-inline">Save</button>
            <span class="btn btn-light d-inline" id="cancel">Cancel</span>
        </div>
    </form>

</div> --}}

{{-- <script>

    const addService = document.getElementById('addService');
    let service = document.getElementsByName('service');
    let host = document.getElementsByName('host');
    let serviceName = document.getElementsByName('serviceName');
    const popup = document.querySelector('.popup');
    const back = document.querySelector('.back');

    addService.onclick = () => {

        let serviceChecked = '';
        let hostChecked = '';

        for (var i = 0, length = service.length; i < length; i++) {

            if (service[i].checked) {
                // do whatever you want with the checked radio
                serviceChecked = service[i].value;

                // only one radio can be logically checked, don't check the rest
                break;
            }
        }

        for (var i = 0, length = host.length; i < length; i++) {
            if (host[i].checked) {
                // do whatever you want with the checked radio
                hostChecked = host[i].value;

                // only one radio can be logically checked, don't check the rest
                break;
            }
        }

        if(serviceChecked && hostChecked)
        {
            document.getElementById('serviceName').value = serviceChecked;
            document.getElementById('hostName').innerHTML = hostChecked;

            popup.style.display = 'block';
            back.style.opacity = '.1';
        }
    }

</script> --}}


@endsection
