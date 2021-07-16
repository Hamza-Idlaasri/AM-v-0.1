<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js" integrity="sha512-UXumZrZNiOwnTcZSHLOfcTs0aos2MzBWHXOHOuB0J/R44QB0dwY5JgfbvljXcklVf65Gc4El6RjZ+lnwd2az2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/1.1.1/chartjs-plugin-zoom.min.js" integrity="sha512-NxlWEbNbTV6acWnTsWRLIiwzOw0IwHQOYUCKBiu/NqZ+5jSy7gjMbpYI+/4KvaNuZ1qolbw+Vnd76pbIUYEG8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div  style="height:400px ">
    <canvas id="myChart"></canvas>
</div>

<script>
    var ctx = document.getElementById("myChart").getContext('2d');


    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Tokyo",	"Mumbai",	"Mexico City",	"Shanghai",	"Sao Paulo",	"New York",	"Karachi","Buenos Aires",	"Delhi","Moscow"],
            datasets: [{
                label: 'Series 1', // Name the series
                data: [500,	50,	2424,	14040,	14141,	4111,	4544,	47,	5555, 6811], // Specify the data values array
                fill: false,
                borderColor: '#2196f3', // Add custom color border (Line)
                backgroundColor: '#2196f3', // Add custom color background (Points and Fill)
                borderWidth: 1 // Specify bar border width
            }]},
        options: {
            responsive: true, // Instruct chart js to respond nicely.
            maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
            plugins: {
                zoom: {
                    zoom: {
                    wheel: {
                        enabled: true,
                    },
                    pinch: {
                        enabled: true
                    },
                    mode: 'x',
                    }
                }
            }
        }
    });
</script>
