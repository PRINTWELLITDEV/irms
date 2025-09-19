@extends('irms.irms-partials.app')

@section('title', 'IRMS Dashboard')

@section('content')
    <div class="wrapper" style="height: 100%vh">
        <div class="content-wrapper">
            <div class="content-header">
                <h1>Welcome to IRMS Dashboard</h1>
            </div>
            <div class="content-body">
                <div class="card card-primary">
                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col-3 text-center text-white align-items-center">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>18%</h3>
                                        <p>Available Vacancy on PWPC</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-3 text-center text-white align-items-center">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>27</h3>
                                        <p>Dispatched Goods</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-3 text-center text-white align-items-center">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>150</h3>
                                        <p>Received Goods</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>227</h3>
                                        <p>Users</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-8">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">Monthly Cap Report</div>
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-lg">100</span>
                                                <span>Meet Goods</span>
                                            </div>
                                            <div class="ms-auto d-flex flex-column text-end">
                                                <span class="text-success">
                                                    <i class="fas fa-arrow-up"></i>
                                                    5%
                                                </span>
                                                <span class="text-muted">
                                                    Since Last Week
                                                </span>
                                            </div>
                                        </div>
                                        <div class="position-relative mb-4">
                                            <div id="container-chartjs">
                                                <canvas id="myChart" style="height:300px; width:100%;"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card">
                                    <div class="card-header bg-secondary text-white">
                                        <h3 class="card-title">Summary</h3>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            <li><strong>Users:</strong> 42</li>
                                            <li><strong>Warehouses:</strong> 12</li>
                                            <li><strong>Rack Locations:</strong> 128</li>
                                            <li><strong>Rack Locations:</strong> 128</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-12 col-md-3">
                                <div class="card card-dark">
                                    <div class="card-header">
                                        <h3 class="card-title">Available Occupancy per Sites</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="chart_bar" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="card card-danger">
                                    <div class="card-header">
                                        <h3 class="card-title">User per Site</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="chart_pie" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="card shadow-md">
                                    <div class="card-header bg-info text-white">
                                        <h3 class="card-title">Incoming & Outgoing Shipments per Week</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="chart_line" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="card">
                                    <div class="card-header bg-warning text-white">
                                        <h3 class="card-title">Transaction per Month</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="chart2" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <canvas id="realTimeAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Load scripts in correct order -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.13/jquery.knob.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@3.4.4/build/global/luxon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon@1.3.1/dist/chartjs-adapter-luxon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-streaming@2.0.0/dist/chartjs-plugin-streaming.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


    <script>
        (function () {
            // utility: random integer in [min,max]
            function randInt(min, max) { return Math.floor(Math.random() * (max - min + 1)) + min; }
            function randArray(len, min = 0, max = 100) { return Array.from({ length: len }, () => randInt(min, max)); }

            // safe init for knob (unchanged)
            try {
                if ($('.tron-knob').length) {
                    $(".tron-knob").knob({
                        min: 0, max: 100, readOnly: false,
                        width: 150, height: 150, angleOffset: -125, angleArc: 250,
                        rotation: 'anticlockwise', fgColor: '#00cccc', bgColor: '#f0f0f0',
                        inputColor: '#111', font: 'Arial, sans-serif', fontWeight: 'bold'
                    });
                }
            } catch (e) {
                console.warn('Knob init failed', e);
            }

            // replace DOMContentLoaded wrapper with an init function that runs immediately if DOM is already ready
            function initDashboardCharts() {
                // helper: create chart only if canvas exists
                function createChart(canvasId, configFactory) {
                    const el = document.getElementById(canvasId);
                    if (!el) return null;
                    const ctx = el.getContext('2d');
                    try { return new Chart(ctx, configFactory()); } catch (err) { console.error('Chart creation error for', canvasId, err); return null; }
                }

                // labels
                const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                const site_labels = ['PI', 'FPC', 'PWPC'];
                const day_name_label = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

                // generate random data for each dataset
                const dataA = randArray(labels.length, 5, 95);
                const dataB = randArray(labels.length, 5, 95);
                const dataC = randArray(day_name_label.length, 0, 120);
                const dataC2 = randArray(day_name_label.length, 0, 120);
                const dataD = randArray(site_labels.length, 10, 80);
                const dataE = randArray(site_labels.length, 5, 95);
                const dataF = randArray(site_labels.length, 5, 95);


                // Bar / Pie / Line / chart2 (unchanged)
                createChart('chart_bar', function () {
                    return {
                        type: 'bar',
                        data: {
                            labels: site_labels,
                            datasets: [
                                { label: 'Occupied', data: dataE, backgroundColor: 'rgba(54,162,235,0.6)', borderColor: 'rgba(54,162,235,1)', borderWidth: 1 },
                                { label: 'Available', data: dataF, backgroundColor: 'rgba(255,159,64,0.6)', borderColor: 'rgba(255,159,64,1)', borderWidth: 1 }
                            ]
                        },
                        options: { maintainAspectRatio: false, responsive: true, scales: { y: { beginAtZero: true } } }
                    };
                });

                createChart('chart_pie', function () {
                    return {
                        type: 'pie',
                        data: { labels: site_labels, datasets: [{ data: dataD, backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56'] }] },
                        options: { maintainAspectRatio: false, responsive: true }
                    };
                });

                createChart('chart_line', function () {
                    return {
                        type: 'line',
                        data: {
                            labels: day_name_label,
                            datasets: [
                                { label: 'Incoming', data: dataC, borderColor: '#4bc0c0', backgroundColor: 'rgba(75,192,192,0.2)', fill: true },
                                { label: 'Outgoing', data: dataC2, borderColor: '#c0a94bff', backgroundColor: 'rgba(205, 184, 60, 0.2)', fill: true },
                            ]
                        },
                        options: { maintainAspectRatio: false, responsive: true, scales: { y: { beginAtZero: true } } }
                    };
                });

                createChart('chart2', function () {
                    return {
                        type: 'bar',
                        data: { labels: labels, datasets: [{ label: 'Transactions', data: randArray(labels.length, 0, 120), backgroundColor: 'rgba(153,102,255,0.6)', borderColor: 'rgba(153,102,255,1)', borderWidth: 1 }] },
                        options: { maintainAspectRatio: false, responsive: true, scales: { y: { beginAtZero: true } } }
                    };
                });

                // Real-time chart: push random y values every second
                // Real-time chart: push random y values every second
                (function createRealTimeChart() {
                    const el = document.getElementById('realTimeAreaChart');
                    if (!el) return;

                    el.style.height = '250px';
                    el.height = 250;
                    const ctxRT = el.getContext('2d');

                    const config = {
                        type: 'line',
                        data: {
                            datasets: [{
                                label: 'Live Transactions',
                                backgroundColor: 'rgba(75,192,192,0.4)',
                                borderColor: 'rgb(75,192,192)',
                                fill: 'origin',
                                data: []
                            }]
                        },
                        options: {
                            maintainAspectRatio: false,
                            responsive: true,
                            scales: {
                                x: {
                                    type: 'realtime', // <--- Change type from 'time' to 'realtime'
                                    realtime: { // <--- ADD THIS BLOCK
                                        delay: 2000,
                                        onRefresh: chart => {
                                            chart.data.datasets.forEach(dataset => {
                                                dataset.data.push({
                                                    x: Date.now(),
                                                    y: randInt(0, 120) // Use your randInt function
                                                });
                                            });
                                        }
                                    },
                                    time: {
                                        unit: 'second',
                                        tooltipFormat: 'HH:mm:ss'
                                    },
                                    ticks: {
                                        source: 'auto'
                                    },
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false
                                }
                            }
                        }
                    };

                    const myRealTimeChart = new Chart(ctxRT, config);
                })();

                /* ===== Fixed: initialize myChart here (guarded) ===== */
                try {
                    // Utility helpers used by myChart
                    const Utils = {
                        months: (config) => {
                            const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                            const count = (config && config.count) || 12;
                            return labels.slice(0, count);
                        },
                        numbers: (config) => {
                            const count = (config && config.count) || 0;
                            const min = (config && config.min) || 0;
                            const max = (config && config.max) || 100;
                            const arr = [];
                            for (let i = 0; i < count; i++) arr.push(Math.floor(Math.random() * (max - min + 1) + min));
                            return arr;
                        },
                        CHART_COLORS: {
                            red: 'rgb(255, 99, 132)',
                            blue: 'rgb(54, 162, 235)',
                            yellow: 'rgb(255, 205, 86)',
                            green: 'rgb(75, 192, 192)',
                            purple: 'rgb(153, 102, 255)',
                            orange: 'rgb(255, 159, 64)'
                        },
                        transparentize: (color, opacity) => {
                            const alpha = opacity === undefined ? 0.5 : 1 - opacity;
                            return color.replace('rgb', 'rgba').slice(0, -1) + `,${alpha})`;
                        },
                        namedColor: (index) => Object.values(Utils.CHART_COLORS)[index % Object.values(Utils.CHART_COLORS).length],
                        rand: (min, max) => Math.floor(Math.random() * (max - min + 1) + min)
                    };

                    const myCtxEl = document.getElementById('myChart');
                    if (myCtxEl && typeof Chart !== 'undefined') {
                        const DATA_COUNT = 7;
                        const NUMBER_CFG = { count: DATA_COUNT, min: 0, max: 100 };
                        const labelsLocal = Utils.months({ count: DATA_COUNT });
                        const dataLocal = {
                            labels: labelsLocal,
                            datasets: [
                                { label: 'Dataset 1', data: Utils.numbers(NUMBER_CFG), borderColor: Utils.CHART_COLORS.red, backgroundColor: Utils.transparentize(Utils.CHART_COLORS.red, 0.5), stack: 'combined', type: 'bar' },
                                { label: 'Dataset 2', data: Utils.numbers(NUMBER_CFG), borderColor: Utils.CHART_COLORS.blue, backgroundColor: Utils.transparentize(Utils.CHART_COLORS.blue, 0.5), stack: 'combined' }
                            ]
                        };
                        const configLocal = {
                            type: 'line',
                            data: dataLocal,
                            options: {
                                maintainAspectRatio: false,
                                responsive: true,
                                plugins: {
                                    title: { display: true, text: 'Chart.js Stacked Line/Bar Chart' }
                                },
                                scales: { y: { stacked: true } }
                            }
                        };
                        const ctx2 = myCtxEl.getContext('2d');
                        const myChart = new Chart(ctx2, configLocal);
                    }
                } catch (err) {
                    console.error('myChart init error', err);
                }
            } // end initDashboardCharts

            // run init immediately if DOM already loaded, otherwise wait
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initDashboardCharts);
            } else {
                initDashboardCharts();
            }
        })();
    </script>
@endsection
