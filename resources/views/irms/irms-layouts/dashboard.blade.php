@extends('irms/irms-partials.app')

@section('title', 'IRMS Dashboard')

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <h1>Welcome to IRMS Dashboard</h1>
            </div>
            <div class="content-body">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row mb-3">

                            <div class="col col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <canvas id="chart1" class="chartjs-render-monitor" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="col col-md-4">
                                <div class="card">

                                </div>
                            </div>

                            <div class="col col-md-4">

                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <canvas id="chart3" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="col col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <canvas id="chart2" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-md-3"></div>
                            <div class="col col-md-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        // Get the canvas element
        var ctx1 = document.getElementById('chart1').getContext('2d');

        // Chart data and options
        var myChart1 = new Chart(ctx1, {
            type: 'pie', // Specify the chart type (e.g., 'bar', 'line', 'pie')
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'Sales',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false, // Prevents the chart from being too large
                responsive: true, // Make the chart responsive
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


        var ctx2 = document.getElementById('chart2').getContext('2d');

        // Chart data and options
        var myChart2 = new Chart(ctx2, {
            type: 'bar', // Specify the chart type (e.g., 'bar', 'line', 'pie')
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'Transactions',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false, // Prevents the chart from being too large
                responsive: true, // Make the chart responsive
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


        // Line Chart

         var ctx3 = document.getElementById('chart3').getContext('2d');

        // Chart data and options
        var myChart3 = new Chart(ctx3, {
            type: 'line', // Specify the chart type (e.g., 'bar', 'line', 'pie')
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'Transactions',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false, // Prevents the chart from being too large
                responsive: true, // Make the chart responsive
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
