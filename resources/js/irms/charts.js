// Chart JS
(function () {
    // utility: random integer in [min,max]
    function randInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
    function randArray(len, min = 0, max = 100) {
        return Array.from({ length: len }, () => randInt(min, max));
    }

    // safe init for knob (unchanged)
    try {
        if ($(".tron-knob").length) {
            $(".tron-knob").knob({
                min: 0,
                max: 100,
                readOnly: false,
                width: 150,
                height: 150,
                angleOffset: -125,
                angleArc: 250,
                rotation: "anticlockwise",
                fgColor: "#00cccc",
                bgColor: "#f0f0f0",
                inputColor: "#111",
                font: "Arial, sans-serif",
                fontWeight: "bold",
            });
        }
    } catch (e) {
        console.warn("Knob init failed", e);
    }

    // replace DOMContentLoaded wrapper with an init function that runs immediately if DOM is already ready
    function initDashboardCharts() {
        // helper: create chart only if canvas exists
        function createChart(canvasId, configFactory) {
            const el = document.getElementById(canvasId);
            if (!el) return null;
            const ctx = el.getContext("2d");
            try {
                return new Chart(ctx, configFactory());
            } catch (err) {
                console.error("Chart creation error for", canvasId, err);
                return null;
            }
        }

        // labels
        const labels = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ];
        const site_labels = ["PI", "FPC", "PWPC"];
        const day_name_label = [
            "Sunday",
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
        ];

        // generate random data for each dataset
        const dataA = randArray(labels.length, 5, 95);
        const dataB = randArray(labels.length, 5, 95);
        const dataC = randArray(day_name_label.length, 0, 120);
        const dataC2 = randArray(day_name_label.length, 0, 120);
        const dataD = randArray(site_labels.length, 10, 80);
        const dataE = randArray(site_labels.length, 5, 95);
        const dataF = randArray(site_labels.length, 5, 95);
        const dataG = [0];

        // Bar / Pie / Line / chart2 (unchanged)
        createChart("chart_bar", function () {
            return {
                type: "bar",
                data: {
                    labels: site_labels,
                    datasets: [
                        {
                            label: "Occupied",
                            data: dataE,
                            backgroundColor: "rgba(54,162,235,0.6)",
                            borderColor: "rgba(54,162,235,1)",
                            borderWidth: 1,
                        },
                        {
                            label: "Available",
                            data: dataF,
                            backgroundColor: "rgba(255,159,64,0.6)",
                            borderColor: "rgba(255,159,64,1)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                },
            };
        });

        createChart("chart_line2", function () {
            return {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "Monthly Transactions",
                            data: randArray(labels.length, 50, 200),
                            backgroundColor: "rgba(54,162,235,0.6)",
                            borderColor: "rgba(54,162,235,1)",
                            borderWidth: 1,
                        },
                        {
                            label: "Target",
                            data: randArray(labels.length, 5, 100), // Constant target line
                            type: "line",
                            borderColor: "rgba(255,99,132,1)",
                            borderWidth: 2,
                            fill: false,
                            pointRadius: 0,
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    interaction: {
                        intersect: false,
                        mode: "index",
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: "Transaction Count",
                            },
                        },
                    },
                    plugins: {
                        legend: {
                            position: "bottom",
                        },
                        title: {
                            display: true,
                            text: "Monthly Transaction Recap",
                        },
                    },
                },
            };
        });

        document.addEventListener("DOMContentLoaded", function () {
            // Ensure Chart.js and createChart() exist before continuing
            if (
                typeof Chart === "undefined" ||
                typeof createChart === "undefined"
            ) {
                console.error(
                    "Chart.js or createChart() not found — make sure they’re loaded in your layout."
                );
                return;
            }

            // Get data from Laravel (these are arrays from your controller)
            const labels = Array.isArray(window.CHART_LABELS)
                ? window.CHART_LABELS
                : [];
            const data = Array.isArray(window.CHART_DATA)
                ? window.CHART_DATA
                : [];

            console.log("Chart Labels:", labels);
            console.log("Chart Data:", data);

            // If no data, skip chart creation
            if (labels.length === 0 || data.length === 0) {
                console.warn(
                    "No chart data available for Active Users per Site"
                );
                return;
            }

            // Function to generate unique colors
            function generateColors(num) {
                const colors = [
                    "#ff6384",
                    "#36a2eb",
                    "#ffcd56",
                    "#4bc0c0",
                    "#9966ff",
                    "#ff9f40",
                ];
                return Array.from(
                    { length: num },
                    (_, i) => colors[i % colors.length]
                );
            }

            // Create the pie chart
            createChart("chart_pie", function () {
                return {
                    type: "pie",
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                data: data,
                                backgroundColor: generateColors(data.length),
                                hoverOffset: 4,
                            },
                        ],
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: "Active Users per Site",
                            },
                            legend: {
                                position: "bottom",
                            },
                        },
                    },
                };
            });
        });

        createChart("chart_line", function () {
            return {
                type: "line",
                data: {
                    labels: day_name_label,
                    datasets: [
                        {
                            label: "Incoming",
                            data: dataC,
                            borderColor: "#4bc0c0",
                            backgroundColor: "rgba(75,192,192,0.2)",
                            fill: true,
                        },
                        {
                            label: "Outgoing",
                            data: dataC2,
                            borderColor: "#c0a94bff",
                            backgroundColor: "rgba(205, 184, 60, 0.2)",
                            fill: true,
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                },
            };
        });

        createChart("chart2", function () {
            return {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "Transactions",
                            data: randArray(labels.length, 0, 120),
                            backgroundColor: "rgba(153,102,255,0.6)",
                            borderColor: "rgba(153,102,255,1)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                },
            };
        });

        // Real-time chart: push random y values every second
        // Real-time chart: push random y values every second
        (function createRealTimeChart() {
            const el = document.getElementById("realTimeAreaChart");
            if (!el) return;

            el.style.height = "250px";
            el.height = 250;
            const ctxRT = el.getContext("2d");

            const config = {
                type: "line",
                data: {
                    datasets: [
                        {
                            label: "Live Transactions",
                            backgroundColor: "rgba(75,192,192,0.4)",
                            borderColor: "rgb(75,192,192)",
                            fill: "origin",
                            data: dataD,
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: {
                        x: {
                            type: "realtime", // <--- Change type from 'time' to 'realtime'
                            realtime: {
                                // <--- ADD THIS BLOCK
                                delay: 2000,
                                onRefresh: (chart) => {
                                    chart.data.datasets.forEach((dataset) => {
                                        dataset.data.push({
                                            x: Date.now(),
                                            y: randInt(0, 120), // Use your randInt function
                                        });
                                    });
                                },
                            },
                            time: {
                                unit: "second",
                                tooltipFormat: "HH:mm:ss",
                            },
                            ticks: {
                                source: "auto",
                            },
                            grid: {
                                display: false,
                            },
                        },
                        y: {
                            beginAtZero: true,
                        },
                    },
                    plugins: {
                        legend: {
                            display: true,
                        },
                        tooltip: {
                            mode: "index",
                            intersect: false,
                        },
                    },
                },
            };

            const myRealTimeChart = new Chart(ctxRT, config);
        })();

        /* ===== Fixed: initialize myChart here (guarded) ===== */
        try {
            // Utility helpers used by myChart
            const Utils = {
                months: (config) => {
                    const labels = [
                        "January",
                        "February",
                        "March",
                        "April",
                        "May",
                        "June",
                        "July",
                        "August",
                        "September",
                        "October",
                        "November",
                        "December",
                    ];
                    const count = (config && config.count) || 12;
                    return labels.slice(0, count);
                },
                numbers: (config) => {
                    const count = (config && config.count) || 0;
                    const min = (config && config.min) || 0;
                    const max = (config && config.max) || 100;
                    const arr = [];
                    for (let i = 0; i < count; i++)
                        arr.push(
                            Math.floor(Math.random() * (max - min + 1) + min)
                        );
                    return arr;
                },
                CHART_COLORS: {
                    red: "rgb(255, 99, 132)",
                    blue: "rgb(54, 162, 235)",
                    yellow: "rgb(255, 205, 86)",
                    green: "rgb(75, 192, 192)",
                    purple: "rgb(153, 102, 255)",
                    orange: "rgb(255, 159, 64)",
                },
                transparentize: (color, opacity) => {
                    const alpha = opacity === undefined ? 0.5 : 1 - opacity;
                    return (
                        color.replace("rgb", "rgba").slice(0, -1) + `,${alpha})`
                    );
                },
                namedColor: (index) =>
                    Object.values(Utils.CHART_COLORS)[
                        index % Object.values(Utils.CHART_COLORS).length
                    ],
                rand: (min, max) =>
                    Math.floor(Math.random() * (max - min + 1) + min),
            };

            const myCtxEl = document.getElementById("myChart");
            if (myCtxEl && typeof Chart !== "undefined") {
                const DATA_COUNT = 7;
                const NUMBER_CFG = { count: DATA_COUNT, min: 0, max: 100 };
                const labelsLocal = Utils.months({ count: DATA_COUNT });
                const dataLocal = {
                    labels: labelsLocal,
                    datasets: [
                        {
                            label: "Dataset 1",
                            data: Utils.numbers(NUMBER_CFG),
                            borderColor: Utils.CHART_COLORS.red,
                            backgroundColor: Utils.transparentize(
                                Utils.CHART_COLORS.red,
                                0.5
                            ),
                            stack: "combined",
                            type: "bar",
                        },
                        {
                            label: "Dataset 2",
                            data: Utils.numbers(NUMBER_CFG),
                            borderColor: Utils.CHART_COLORS.blue,
                            backgroundColor: Utils.transparentize(
                                Utils.CHART_COLORS.blue,
                                0.5
                            ),
                            stack: "combined",
                        },
                    ],
                };
                const configLocal = {
                    type: "line",
                    data: dataLocal,
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: "Chart.js Stacked Line/Bar Chart",
                            },
                        },
                        scales: { y: { stacked: true } },
                    },
                };
                const ctx2 = myCtxEl.getContext("2d");
                const myChart = new Chart(ctx2, configLocal);
            }
        } catch (err) {
            console.error("myChart init error", err);
        }
    } // end initDashboardCharts

    // run init immediately if DOM already loaded, otherwise wait
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initDashboardCharts);
    } else {
        initDashboardCharts();
    }
})();
