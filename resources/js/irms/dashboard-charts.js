/**
 * Dashboard Charts Module
 * Handles initialization and rendering of dashboard charts
 */

let monthlyChart = null;
let dailyChart = null;
let weeklyChart = null;

/**
 * Initialize Monthly Chart for Super Admin & Admin
 */
function initMonthlyChart(data) {
    const ctx = document.getElementById('monthlyChart');
    if (!ctx) {
        console.warn('Monthly chart canvas not found');
        return;
    }

    console.log('Monthly chart data:', data);

    // Destroy existing chart if any
    if (monthlyChart) {
        monthlyChart.destroy();
    }

    // Prepare data arrays for all 12 months
    const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const receivedData = new Array(12).fill(0);
    const dispatchedData = new Array(12).fill(0);

    // Fill in the actual data
    if (Array.isArray(data) && data.length > 0) {
        data.forEach(item => {
            const monthIndex = parseInt(item.month) - 1;
            if (monthIndex >= 0 && monthIndex < 12) {
                receivedData[monthIndex] = parseFloat(item.received) || 0;
                dispatchedData[monthIndex] = parseFloat(item.dispatched) || 0;
            }
        });
    }

    console.log('Received data:', receivedData);
    console.log('Dispatched data:', dispatchedData);

    monthlyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Received',
                data: receivedData,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Dispatched',
                data: dispatchedData,
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}

/**
 * Initialize Weekly Chart for All Users (Stacked Bar Chart with Negative Values)
 */
function initWeeklyChart(data) {
    const ctx = document.getElementById('weeklyChart');
    if (!ctx) {
        console.warn('Weekly chart canvas not found');
        return;
    }

    console.log('Weekly chart data:', data);

    // Destroy existing chart if any
    if (weeklyChart) {
        weeklyChart.destroy();
    }

    // Day labels for the week (Sunday = 1, Monday = 2, etc. from SQL Server DATEPART)
    const dayLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const receivedData = new Array(7).fill(0);
    const dispatchedData = new Array(7).fill(0);

    // Fill in the actual data
    if (Array.isArray(data) && data.length > 0) {
        data.forEach(item => {
            // SQL Server DATEPART(WEEKDAY) returns 1 = Sunday, 2 = Monday, etc.
            const dayIndex = parseInt(item.weekday) - 1;
            if (dayIndex >= 0 && dayIndex < 7) {
                receivedData[dayIndex] = parseFloat(item.received) || 0;
                // Make dispatched values negative for stacked chart
                dispatchedData[dayIndex] = parseFloat(item.dispatched) || 0;
            }
        });
    }

    console.log('Weekly received data:', receivedData);
    console.log('Weekly dispatched data (negative):', dispatchedData);

    weeklyChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dayLabels,
            datasets: [{
                label: 'Received',
                data: receivedData,
                backgroundColor: 'rgba(40, 167, 69, 0.8)',
                borderColor: '#28a745',
                borderWidth: 2,
                stack: 'operations'
            }, {
                label: 'Dispatched',
                data: dispatchedData,
                backgroundColor: 'rgba(220, 53, 69, 0.8)',
                borderColor: '#dc3545',
                borderWidth: 2,
                stack: 'operations'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            let value = context.parsed.y || 0;
                            // Show absolute value for dispatched in tooltip
                            if (label === 'Dispatched') {
                                value = Math.abs(value);
                            }
                            return `${label}: ${value.toLocaleString()}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    stacked: true,
                    grid: {
                        display: false
                    }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            // Show absolute values on Y-axis
                            return Math.abs(value).toLocaleString();
                        }
                    },
                    grid: {
                        borderDash: [5, 5],
                        color: function(context) {
                            // Different color for zero line
                            return context.tick.value === 0 ? '#000000' : '#e0e0e0';
                        },
                        lineWidth: function(context) {
                            return context.tick.value === 0 ? 2 : 1;
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}

/**
 * Initialize Today's Operations Chart (Pie Chart)
 */
function initTodayChart(data) {
    const ctx = document.getElementById('todayChart');
    if (!ctx) {
        console.warn('Today chart canvas not found');
        return;
    }

    console.log('Today chart data:', data);

    // Destroy existing chart if any
    if (dailyChart) {
        dailyChart.destroy();
    }

    // Prepare data
    let receivedData = 0;
    let dispatchedData = 0;

    if (data && typeof data === 'object') {
        receivedData = parseFloat(data.received) || 0;
        dispatchedData = parseFloat(data.dispatched) || 0;
    }

    console.log('Today received data:', receivedData);
    console.log('Today dispatched data:', dispatchedData);

    // Check if there's any data to display
    const totalOperations = receivedData + dispatchedData;
    if (totalOperations === 0) {
        // Show empty state
        ctx.parentElement.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                <p class="text-muted">No operations recorded today</p>
            </div>
        `;
        return;
    }

    dailyChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Received', 'Dispatched'],
            datasets: [{
                data: [receivedData, dispatchedData],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',   // Green for received
                    'rgba(220, 53, 69, 0.8)'    // Red for dispatched
                ],
                borderColor: [
                    '#28a745',
                    '#dc3545'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value.toLocaleString()} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '50%'
        }
    });
}

// Make functions available globally
window.initMonthlyChart = initMonthlyChart;
window.initWeeklyChart = initWeeklyChart;
window.initTodayChart = initTodayChart;