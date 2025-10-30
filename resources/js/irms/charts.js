// Datables
$(document).ready(function () {
    $("#transHistory tbody").on("click", "tr", function () {
        const $row = $(this);

        $("#view-rack-warehouse").text($row.data("rswhse") || "");
        $("#view-rack-baynum").text($row.data("rsbaynum") || "");
        $("#view-rack-location").text($row.data("rsloc") || "");
        $("#view-rack-description").text($row.data("rsdesc") || "");
    });
});

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

        const rack_labels = ["Rack 1", "Rack 2", "Rack 3", "Rack 4"];

        // generate random data for each dataset
        const dataA = randArray(labels.length, 5, 95);
        const dataB = randArray(labels.length, 5, 95);
        const dataC = randArray(day_name_label.length, 0, 120);
        const dataC2 = randArray(day_name_label.length, 0, 120);
        const dataD = randArray(site_labels.length, 10, 80);
        const dataE = randArray(site_labels.length, 5, 95);
        const dataF = randArray(site_labels.length, 5, 95);

        const rack_data = randArray(rack_labels.length, 5, 50);

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

        createChart("chart_pie", function () {
            return {
                type: "pie",
                data: {
                    labels: site_labels,
                    datasets: [
                        {
                            data: dataD,
                            backgroundColor: ["#ff6384", "#36a2eb", "#ffcd56"],
                        },
                    ],
                },
                options: { maintainAspectRatio: false, responsive: true },
            };
        });

        createChart("stacked_bar", function () {
            return {
                type: "bar",
                data: {
                    labels: rack_labels,
                    datasets: [
                        {
                            label: "Warehouse 1",
                            data: rack_data,
                            borderColor: "#4bc0c0",
                            backgroundColor: "rgba(75,192,192,0.2)",
                            fill: true,
                        },
                        {
                            label: "Warehouse 2",
                            data: rack_data,
                            borderColor: "#c0a94bff",
                            backgroundColor: "rgba(205, 184, 60, 0.2)",
                            fill: true,
                        },
                    ],
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: "Rack Utilization by Warehouse",
                        },
                    },
                    responsive: true,
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true,
                        },
                    },
                },
            };
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

//Heatmap

document.addEventListener("DOMContentLoaded", function () {
    function generateData(count, yrange) {
        var i = 1;
        var series = [];
        while (i < count) {
            var x = "S" + (i + 1).toString();
            var y =
                Math.floor(Math.random() * (yrange.max - yrange.min + 1)) +
                yrange.min;

            series.push({
                x: x,
                y: y,
            });
            i++;
        }
        return series;

        // while(i <= 20){
        //     var x = 2;

        // }
    }

    var options = {
        series: [
            {
                name: "",
                data: generateData(20, {
                    min: 0,
                    max: 100,
                }),
            },
            {
                name: "A1-L2",
                data: generateData(20, {
                    min: 0,
                    max: 100,
                }),
            },
            {
                name: "A1-L1",
                data: generateData(20, {
                    min: 0,
                    max: 100,
                }),
            },
            {
                name: "A2-L1",
                data: generateData(20, {
                    min: 0,
                    max: 100,
                }),
            },
            {
                name: "A2-L2",
                data: generateData(20, {
                    min: 0,
                    max: 100,
                }),
            },
            {
                name: "B1-L2",
                data: generateData(20, {
                    min: 0,
                    max: 100,
                }),
            },
            {
                name: "B1-L1",
                data: generateData(20, {
                    min: 0,
                    max: 100,
                }),
            },
            {
                name: "B2-L1",
                data: generateData(20, {
                    min: 0,
                    max: 100,
                }),
            },
            {
                name: "B2-L2",
                data: generateData(20, {
                    min: 0,
                    max: 100,
                }),
            },
        ],
        chart: {
            height: 350,
            type: "heatmap",
        },
        plotOptions: {
            heatmap: {
                shadeIntensity: 0.5,
                radius: 0,
                useFillColorAsStroke: true,
                colorScale: {
                    ranges: [
                        {
                            from: 0,
                            to: 30,
                            name: "Vacant",
                            color: "#00A100",
                        },
                        {
                            from: 31,
                            to: 60,
                            name: "Partial",
                            color: "#128FD9",
                        },
                        {
                            from: 61,
                            to: 90,
                            name: "Occupied",
                            color: "#FF0000",
                        },
                        {
                            from: 91,
                            to: 100,
                            name: "Damage",
                            color: "#FFB200",
                        },
                    ],
                },
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            width: 1,
        },
        title: {
            text: "Warehouse 1",
        },
    };

    var chart = new ApexCharts(
        document.querySelector("#chart-heatmap"),
        options
    );
    chart.render();
});
