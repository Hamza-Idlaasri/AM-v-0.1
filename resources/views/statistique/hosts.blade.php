@extends('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<div class="container">
    
    {{-- Filter --}}
    <div class="container text-primary w-75">
        @include('inc.filter', ['names' => $all_hosts_names,'route' => 'statistic.hosts','type' => 'Host','from' => 'statistic'])
    </div>

    @if (sizeof($cas) == 0)
        <div class="container w-25 mt-5">
            <h4>{{$case}}</h4>
        </div>
    @else
        <div class="d-flex flex-wrap justify-content-around">

            {{-- <div class="chart">
                <h6>Porcentage des alarmes</h6>
                    <div>
                        {{ $Piechart->render() }}
                    </div>
            </div> --}}
            <div class="chart">
                <h6>Porcentage des alarmes</h6>
                <div>
                    <canvas id="PieChart"></canvas>
                </div>
            </div>

        
            
            {{-- <div class="chart">
                <h6>Nombre des alarmes</h6>
                    <div>
                        {{ $Barchart->render() }}
                    </div>
            </div> --}}

            <div class="chart">
                <h6>Nombre des alarmes</h6>
                <div>
                    <canvas id="BarChart"></canvas>
                </div>
            </div>
        
        
            
            {{-- <div class="chart" style="width:97%">
                <h6>Nombre des alarmes</h6>
                    <div style="width: 100%;">
                        {{ $Linechart->render() }}
                    </div>
            </div> --}}
            
            
            <div class="chart" style="width:97%;">
                <h6>Nombre des alarmes</h6>
                <div class="w-100" style="height:400px">
                    <canvas id="LineChart"></canvas>
                </div>
            </div>
            
        </div>
    
{{-- < src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></> --}}


<!-- PieChart -->
<script>

    let ctxPie = document.getElementById('PieChart').getContext('2d');
    let PieChart = new Chart(ctxPie, {
        type: 'pie',
        data:{
            labels:['Up','Down','Unreachable'],
            datasets:[{
                data: [{{$hosts_up}},{{$hosts_down}},{{$hosts_unreachable}}],
                backgroundColor: [
                        '#6ccf01',
                        'crimson',
                        '#C200FF'

                    ],

            }]
        },
        
        options:{
            legend:{
                position:'right',
                labels:{
                    boxWidth:15,
                }
            },
            // maintainAspectRatio: false,
        },
        
        
    });

</script>

<!-- BarChart -->
<script>

    let ctxBar = document.getElementById('BarChart').getContext('2d');
    let barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['Up','Down','Unreachable'],
            datasets: [{
                
                data: [{{$hosts_up}},{{$hosts_down}},{{$hosts_unreachable}}],
                backgroundColor: [
                    '#6ccf01',
                    'crimson',
                    '#C200FF'

                ],
                borderColor: [
                    
                ],
                borderWidth: 1
            }],
            
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize:1,
                    }
                }],
                xAxes:[{
                    barPercentage:0.4
                }],
            },
            legend:{
                display:false
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem) {
                            return tooltipItem.yLabel;
                    }
                }
            },
            animation: {
                duration: 1,
                onComplete: function () {
                    var chartInstance = this.chart,
                        ctx = chartInstance.ctx;
                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';

                    this.data.datasets.forEach(function (dataset, i) {
                        var meta = chartInstance.controller.getDatasetMeta(i);
                        meta.data.forEach(function (bar, index) {
                            var data = dataset.data[index];                            
                            ctx.fillText(data, bar._model.x, bar._model.y - 5);
                        });
                    });
                }
            }
        }
    });
</script>

<!-- LineChart -->
<script>

let yLabels = {0 : '', 1 : 'Down', 2 : 'Up', 3 : 'Unreachable',4 : ''}

let cas = @json($cas);
let range = @json($range);

for (let i = 0; i < cas.length; i++) {
    switch (cas[i]) {
        case 0:
            cas[i] = 2;
            break;
        case 1:
            cas[i] = 1;
            break;
        case 2:
            cas[i] = 3;
            break;
    
        default:
            break;
    }
    
    
}

let ctxline = document.getElementById('LineChart').getContext('2d');

let lineChart = new Chart(ctxline, {
    type: 'line',
    data: {
        labels: range,
        datasets: [{ 
            data: cas,
            label: "Status",
            borderColor: "#3e95cd",
            fill: false
        }]
    },
        
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        
                        beginAtZero: true,
                        stepSize:1,
                        max:4,
                        callback: function(value, index, values) {
                        return yLabels[value];
                        }
                        
                    }
                        
                    
                }]
            },
            elements: {
                point:{
                    radius: 3
                }
            }
        }
});
</script>

</div>

@endif
@endsection