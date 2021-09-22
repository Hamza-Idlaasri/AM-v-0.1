@extends('layouts.app')

@section('content')
    
<div class="container">

    <div class="d-flex flex-wrap justify-content-around">

        <div class="chart bg-white rounded shadow-sm">
            <h6>Porcentage des Hosts</h6>
                <div>
                    {{ $hosts->render() }}
                </div>
        </div>

        <div class="chart bg-white rounded shadow-sm"> 
            <h6>Porcentage des Boxs</h6>
                <div>
                    {{ $boxs->render() }}
                </div>
        </div>

        <div class="chart bg-white rounded shadow-sm">
            <h6>Porcentage des Services</h6>
                <div>
                    {{ $services->render() }}
                </div>
        </div>

        <div class="chart bg-white rounded shadow-sm">
            <h6>Porcentage des Equipements</h6>
                <div>
                    {{ $equipements->render() }}
                </div>
        </div>
        
    </div>

</div>

<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>

{{-- reload page --}}
<script>
    setTimeout(function(){
        window.location.reload(1);
    }, 15000);
</script>

@endsection