@extends('irms.irms-partials.app')

@section('title', 'IRMS Dashboard')

@section('content')
    <main class="app-main">
        <div class="app-content-wrapper">
            <div class="app-content-header">
                <div class="container-fluid">
                    <h1>Welcome to IRMS Dashboard</h1>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="card card-primary">
                        <div class="card-body">

                            <div class="row mb-3 wrap">
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
                                <div class="col-6">
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
                                                    <canvas id="chart_line2" style="height:300px; width:100%;"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">

                                    @if ($user == 'sa')
                                        <div class="card">
                                            <div class="card-header bg-secondary text-white">
                                                <div class="card-title">Active Users Log</div>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>User Name</th>
                                                            <th>Site</th>
                                                            <th>Last Login</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="active-users-table-body">
                                                        <tr>
                                                            <td colspan="5" class="text-center text-info">Loading active user data...</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    @else
                                        <div class="card">
                                            <div class="card-header card-secondary">
                                                <h3 class="card-title">Summary</h3>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-unstyled mb-0">
                                                    <li><strong>Users:</strong> 42</li>
                                                    <li><strong>Warehouses:</strong> 12</li>
                                                    <li><strong>Rack Locations:</strong> 128</li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-12 col-md-3">
                                    <div class="card card-secondary">
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
                                    <div class="card shadow-md card-secondary">
                                        <div class="card-header">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


@endsection
@push('scripts')
    <script>
        window.CHART_LABELS = @json($chartLabels ?? []);
        window.CHART_DATA = @json($chartData ?? []);


        function generateColors(num) {
            const colors = ["#ff6384", "#36a2eb", "#ffcd56", "#4bc0c0", "#9966ff", "#ff9f40"];
            return Array.from({ length: num }, (_, i) => colors[i % colors.length]);
        }

        function initDashboardCharts() {
            if (!Array.isArray(window.CHART_LABELS) || !Array.isArray(window.CHART_DATA)) {
                console.error("Chart data is invalid", { labels: window.CHART_LABELS, data: window.CHART_DATA });
                return;
            }

            createChart("chart_pie", function () {
                return {
                    type: "pie",
                    data: {
                        labels: window.CHART_LABELS,
                        datasets: [{
                            data: window.CHART_DATA,
                            backgroundColor: generateColors(window.CHART_DATA.length),
                            hoverOffset: 4,
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: "Active Users per Site",
                            }
                        }
                    }
                };
            });
        }

    function updateActiveUsersTable() {
        const tableBody = document.getElementById('active-users-table-body');
        if (!tableBody) return; // Exit if the element isn't present (e.g., if $user != 'sa')

        // Use the dedicated AJAX route
        const url = "{{ route('active.users.data') }}";

        // Fetch the fresh table body HTML
        fetch(url, {
            method: 'GET',
            headers: {
                // Good practice to signal an AJAX request
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            // Replace the old content with the new HTML, automatically reflecting changes
            tableBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Real-Time Active User Update Error:', error);
            // Optionally, stop the interval or show an error to the user
            // tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading data.</td></tr>';
        });
    }

    // Initialize the chart and polling when the document is ready
    document.addEventListener("DOMContentLoaded", function() {
        initDashboardCharts();

        // Check if the table body exists before starting the polling (only for 'sa' user)
        if (document.getElementById('active-users-table-body')) {
            // 1. Load data immediately on page load
            updateActiveUsersTable();

            // 2. Poll for updates every 10 seconds (adjust as needed)
            // Use a variable to store the interval ID if you need to stop it later
            const activeUsersInterval = setInterval(updateActiveUsersTable, 1000); // 10000ms = 10 seconds

            // Optional: Log the interval ID for potential cleanup on navigation
            console.log("Active users polling started. Interval ID:", activeUsersInterval);
        }
    });
    </script>
@endpush
