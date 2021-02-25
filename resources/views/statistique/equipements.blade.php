@extends('layouts.app')

@section('content')
    
<div class="container">


    <div class="d-flex flex-wrap justify-content-around">

        <div class="chart">
            <h6>Porcentage des alarmes</h6>
                <div>
                    {{ $Piechart->render() }}
                </div>
        </div>

       
        
        <div class="chart">
            <h6>Nombre des alarmes</h6>
                <div>
                    {{ $Barchart->render() }}
                </div>
        </div>
       
        {{-- <div class="container">
            <div class="chart" style="width:97%">
                <h6>Nombre des alarmes</h6>
                    <div style="width: 100%;">
                        {{ $lineChart->render() }}
                    </div>
            </div>
            
            <div class="float-right">
                <form action="" method="get">
                    <label for="Periode">Periode:</label>
                    <select >
                        <option selected></option>
                        <option value="1">last 24 hours</option>
                        <option value="2">last week</option>
                        <option value="3">last Year</option>
                    </select>
                </form>
            </div>
        </div> --}}

    </div>

{{-- <script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script> --}}
<script>
    // animation: {
    //                 duration: 1,
    //                 onComplete: function () {
    //                     var chartInstance = this.chart,
    //                         ctx = chartInstance.ctx;
    //                     ctx.textAlign = 'center';
    //                     ctx.fillStyle = "rgba(0, 0, 0, 1)";
    //                     ctx.textBaseline = 'bottom';

    //                     this.data.datasets.forEach(function (dataset, i) {
    //                         var meta = chartInstance.controller.getDatasetMeta(i);
    //                         meta.data.forEach(function (bar, index) {
    //                             var data = dataset.data[index];
    //                             ctx.fillText(data, bar._model.x, bar._model.y - 5);

    //                         });
    //                     });
    //                 }
</script>
</div>

@endsection