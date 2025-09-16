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
                            <div class="col-3 text-center">
                                <input type="text" value="80" class="tron-knob" data-width="150" data-height="150" disabled>
                                <div class="mt-2 text-muted small">System Utilization</div>
                            </div>
                            <div class="col-3 text-center text-white">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>150</h3>
                                        <p>New Orders</p>
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
                                <div class="small-box bg-gradient-warning">
                                    <div class="content">

                                    </div>
                                    <a href="#" class="small-box-footer">
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-12 col-md-4">
                                <div class="card card-dark">
                                    <div class="card-header">
                                        <h3 class="card-title">Supply and Demand</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="chart_bar" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="card card-danger">
                                    <div class="card-header">
                                        <h3 class="card-title">User per Site</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="chart_pie" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Summary</h3>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            <li><strong>Warehouses:</strong> 12</li>
                                            <li><strong>Users:</strong> 42</li>
                                            <li><strong>Rack Locations:</strong> 128</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-3">
                                <div class="card shadow-md">
                                    <div class="card-header bg-warning text-white">
                                        <h3 class="card-title">Transaction per Weeks</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="chart_line" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Transanction per Month</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="chart2" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Interactive Area Chart</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="realTimeAreaChart" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /.row -->
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

   <script>
    (function () {
        // safe init for knob
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

        document.addEventListener('DOMContentLoaded', function () {
            // helper: create chart only if canvas exists
            function createChart(canvasId, configFactory) {
                const el = document.getElementById(canvasId);
                if (!el) return null;
                const ctx = el.getContext('2d');
                try {
                    return new Chart(ctx, configFactory());
                } catch (err) {
                    console.error('Chart creation error for', canvasId, err);
                    return null;
                }
            }

            // sample data (replace with backend data later)
            const labels = ['January', 'February', 'March', 'April', 'May', 'June'];
            const site_labels = ['PI', 'FPC', 'PWPC'];
            const day_name_label = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const dataA = [12, 19, 3, 5, 2, 3];
            const dataB = [7, 2, 34, 28, 1, 18];
            const dataC = [10, 38, 55, 48, 57, 65, 50];
            const dataD = [55, 35, 10];

            // Bar chart
            createChart('chart_bar', function () {
                return {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            { label: 'Pallet Supply', data: dataA, backgroundColor: 'rgba(54,162,235,0.6)', borderColor: 'rgba(54,162,235,1)', borderWidth: 1 },
                            { label: 'Pallet Demand', data: dataB, backgroundColor: 'rgba(255,159,64,0.6)', borderColor: 'rgba(255,159,64,1)', borderWidth: 1 }
                        ]
                    },
                    options: { maintainAspectRatio: false, responsive: true, scales: { y: { beginAtZero: true } } }
                };
            });

            // Pie chart
            createChart('chart_pie', function () {
                return {
                    type: 'pie',
                    data: { labels: site_labels, datasets: [{ data: dataD, backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff', '#ff9f40'] }] },
                    options: { maintainAspectRatio: false, responsive: true }
                };
            });

            // Line chart
            createChart('chart_line', function () {
                return {
                    type: 'line',
                    data: { labels: day_name_label, datasets: [{ label: 'Transactions', data: dataC, borderColor: '#4bc0c0', backgroundColor: 'rgba(75,192,192,0.2)', fill: true }] },
                    options: { maintainAspectRatio: false, responsive: true, scales: { y: { beginAtZero: true } } }
                };
            });

            // Another bar chart
            createChart('chart2', function () {
                return {
                    type: 'bar',
                    data: { labels: labels, datasets: [{ label: 'Transactions', data: dataA, backgroundColor: 'rgba(153,102,255,0.6)', borderColor: 'rgba(153,102,255,1)', borderWidth: 1 }] },
                    options: { maintainAspectRatio: false, responsive: true, scales: { y: { beginAtZero: true } } }
                };
            });

            // ------------------------------------------------------------------
            // CORRECTED Real-time chart code (single instance)
            // ------------------------------------------------------------------
            function createRealTimeChart() {
                const el = document.getElementById('realTimeAreaChart');
                if (!el) return null;
                const ctxRT = el.getContext('2d');

                const config = {
                    type: 'line',
                    data: {
                        datasets: [{
                            label: day_name_label,
                            backgroundColor: 'rgba(75,192,192,0.4)',
                            borderColor: 'rgb(75,192,192)',
                            fill: 'origin',
                            data: [dataA]
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        scales: {
                            x: {
                                type: 'time',
                                time: {
                                    unit: 'second'
                                },
                                ticks: {
                                    source: 'data',
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
                const maxDataPoints = 20; // Number of data points to display at a time

                function addData() {
                    const now = Date.now();
                    const newDataPoint = Math.round(Math.random() * 100);
                    myRealTimeChart.data.datasets[0].data.push({ x: now, y: newDataPoint });

                    if (myRealTimeChart.data.datasets[0].data.length > maxDataPoints) {
                        myRealTimeChart.data.datasets[0].data.shift();
                    }
                    myRealTimeChart.update();
                }

                setInterval(addData, 1000);
            }

            createRealTimeChart();
        });
    })();
</script>
@endsection
