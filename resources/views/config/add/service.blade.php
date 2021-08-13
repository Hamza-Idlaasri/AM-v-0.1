@extends('layouts.app')

<style>
label:hover{
    font-weight: bolder;
    cursor: pointer;
}
</style>

@section('content')
    
<div class="container back">
    
    <div class="card container mt-3 mx-2 bg-white p-0 rounded">

        <div class="card-header">
            <h5 class="font-weight-bolder">Choose Service : <span class="text-danger">*</span></h5>
        </div>
        
        <div class="card-text text-center text-muted mt-3">
            <h6>choose Service depend on type of Host</h6>
        </div>

        <div class="card-body d-flex justify-content-around">

            {{-- Windows --}}
            <div class="card mx-1" style="width: 200px">
                <div class="card-header bg-primary text-white text-center">Windows</div>
                <div class="card-body">
                    
                    @for ($i = 0; $i < sizeof($windows); $i++)
                        <input type="radio" name="service" value="{{ $windows[$i] }}" id="{{ $windows[$i] }}"> <label for="{{ $windows[$i] }}">{{ $windows[$i] }}</label>
                        <br>
                    @endfor
                    
                </div>
            </div>

            {{-- Linux --}}
            <div class="card mx-1" style="width: 200px">
                <div class="card-header bg-primary text-white text-center">Linux</div>
                <div class="card-body">
                    @for ($i = 0; $i < sizeof($linux); $i++)
                        <input type="radio" name="service" value="{{ $linux[$i] }}" id="{{ $linux[$i] }}"> <label for="{{ $linux[$i] }}">{{ $linux[$i] }}</label>
                        <br>
                    @endfor
                </div>
            </div>

            {{-- Router --}}
            <div class="card mx-1" style="width: 200px">
                <div class="card-header bg-primary text-white text-center">Router</div>
                <div class="card-body">
                    @for ($i = 0; $i < sizeof($router); $i++)
                        <input type="radio" name="service" value="{{ $router[$i] }}" id="{{ $router[$i] }}"> <label for="{{ $router[$i] }}">{{ $router[$i] }}</label>
                        <br>
                    @endfor
                </div>
            </div>

            {{-- Switch --}}
            <div class="card mx-1" style="width: 200px">
                <div class="card-header bg-primary text-white text-center">Switch</div>
                <div class="card-body">
                    @for ($i = 0; $i < sizeof($switch); $i++)
                        <input type="radio" name="service" value="{{ $switch[$i] }}" id="{{ $switch[$i] }}"> <label for="{{ $switch[$i] }}">{{ $switch[$i] }}</label>
                        <br>
                    @endfor
                </div>
            </div>

            {{-- Printer --}}
            <div class="card mx-1" style="width: 200px">
                <div class="card-header bg-primary text-white text-center">Printer</div>
                <div class="card-body">
                    @for ($i = 0; $i < sizeof($printer); $i++)
                        <input type="radio" name="service" value="{{ $printer[$i] }}" id="{{ $printer[$i] }}"> <label for="{{ $printer[$i] }}">{{ $printer[$i] }}</label>
                        <br>
                    @endfor
                </div>
            </div>


        </div>


    </div>

    <div class="card container p-0 mt-3 mx-2 bg-white rounded">
        <div class="card-header">
            <h5 class="font-weight-bolder">Choose Host : <span class="text-danger">*</span></h5>
        </div>
        
        <div class="card-body">
           <div class="p-2" style="max-height: 200px; overflow: auto">
            
                @foreach ($hosts as $host)
                    <input type="radio" name="host" value="{{ $host->host_name }}" id="{{ $host->host_name }}"> <label for="{{ $host->host_name }}">{{ $host->host_name }}</label>
                    <br>
                @endforeach

            </div> 
        </div>
        
    </div>

    

    <button type="submit" class="btn btn-primary m-2" id="addService">Add</button>
</div>

<div class="container w-50 p-2 bg-white shadow rounded popup" style="display: none; position: absolute;top:50%;left:50%;  transform: translate(-50%,-50%);height: 250px">
    
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

</div>

<script>

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

</script>


@endsection
