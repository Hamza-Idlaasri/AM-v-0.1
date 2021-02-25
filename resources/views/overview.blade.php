@extends('layouts.app')

@section('content')
    
<div class="container">

    <div class="d-flex flex-wrap justify-content-around">

        <div class="chart">
            <h6>Porcentage des alarmes Hosts</h6>
                <div>
                    {{ $hosts->render() }}
                </div>
        </div>

        <div class="chart">
            <h6>Porcentage des alarmes Equipements</h6>
                <div>
                    {{ $equipements->render() }}
                </div>
        </div>

        <div class="chart">
            <h6>Porcentage des alarmes Services</h6>
                <div>
                    {{ $services->render() }}
                </div>
        </div>

        <div class="chart"> 
            <h6>Porcentage des alarmes Contacts</h6>
                <div>
                    {{ $contacts->render() }}
                </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>

@endsection