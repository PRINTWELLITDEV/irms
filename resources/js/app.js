import "bootstrap";

import "admin-lte";

setInterval(function () {
    const currentPath = window.location.pathname;
    if (currentPath.indexOf("/irms") !== -1) {
        fetch(window.sessionCheckUrl)
            .then((response) => response.json())
            .then((data) => {
                if (!data.valid) {
                    window.location.href = window.loginUrl;
                }
            });
    }
}, 5000);

$.extend($.fn.dataTable.defaults, {
    paging: true,
    info: true,
    lengthChange: false,
    searching: true,
    pageLength: 10,
    responsive: true,
    autoWidth: false,
    layout: {
        topStart: null, // Hides search box
        topEnd: null, // Hides page length / buttons
        bottomStart: "info", // Keep info text
        bottomEnd: "paging", // Keep pagination
    },
    columnDefs: [{ targets: "_all", type: "string" }],
});

$(document).ready(function () {
    // Hide filter boxes initially
    // $(".dataTables_filter").hide();
    // $(".dt-layout-cell").hide();

    // Users table
    const usersTable = $("#users-table").DataTable({
        pageLength: 5,
        language: {
            emptyTable: "No data available",
        },
    });
    $("#userSearch").on("keyup", function () {
        usersTable.search(this.value).draw();
    });

    // Warehouse table
    const warehouseTable = $("#warehouse-table").DataTable({
        fixedHeader: true,
        pageLength: 15,
        columnControl: ["order", ['colVisDropdown']],
        ordering: {
            indicators: false,
            handler: true,
        },
        responsive: true,
        language: {
            emptyTable: "No warehouses found",
        },
    });
    $("#whseSearch").on("keyup", function () {
        warehouseTable.search(this.value).draw();
    });

    // Bay Location table
    const bayLocationTable = $("#bayloc-table").DataTable({
        fixedHeader: true,
        pageLength: 15,
        columnControl: ["order", ['colVisDropdown']],
        ordering: {
            indicators: false,
            handler: true,
        },
        responsive: true,
        language: {
            emptyTable: "No Bay found",
        },
    });

    //Bay Location Search Function
    $("#baylocSearch").on("keyup", function () {
        bayLocationTable.search(this.value).draw();
    });

    //Bay Location Table row selection function
    $("#rackTable").on("click", "tr", function () {
        const $row = $(this);

        $("#view-bay-number").text($row.data("rsbaynum") || "-");
        $("#view-created-date").text($row.data("createdate") || "-");
        $("#view-created-by").text($row.data("createdby") || "-");
    });



    // Rack Location table
    const rackTable = $("#rackTable").DataTable({
        fixedHeader: true,
        pageLength: 15,
        columnControl: ["order", ['colVisDropdown']],
        ordering: {
            indicators: false,
            handler: true,
        },
        responsive: true,
        language: {
            emptyTable: "No Rack found",
        },
    });
    $("#rackSearch").on("keyup", function () {
        rackTable.search(this.value).draw();
    });


    // Controls
    // Show user view modal when a row is clicked
    $("#users-table tbody").on("click", "tr", function () {
        const $row = $(this);
        // Store current row data for use in edit modal
        $("#btnEditUser")
            .data("userid", $row.data("userid"))
            .data("name", $row.data("name"))
            .data("email", $row.data("email"))
            .data("site", $row.data("site"))
            .data("site_desc", $row.data("site_desc"))
            .data("level", $row.data("level"))
            .data("gender", $row.data("gender"))
            .data("profile", $row.data("profile"));

        // Fill view modal
        $("#view-user-site_desc").text($row.data("site_desc") || "-");
        $("#view-user-id").text($row.data("userid") || "-");
        $("#view-user-name").text($row.data("name") || "-");
        $("#view-user-email").text($row.data("email") || "-");
        $("#view-user-level").text($row.data("level") || "-");
        $("#view-user-gender").text($row.data("gender") || "-");
        $("#view-user-create_date").text($row.data("create_date") || "-");
        $("#view-user-label-name").text($row.data("name") || "-");
        $("#view-user-profile").attr("src", $row.data("profile"));

        $("#viewUserModal").modal("show");
    });

    // Profile picture preview for Add User modal
    $("#profile_pic_url").on("change", function (e) {
        const input = this;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $("#add-user-profile-preview").attr("src", e.target.result);
                $("#add-user-profile-preview-container").show();
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            $("#add-user-profile-preview").attr(
                "src",
                '{{ asset("uploads/user-profile/noprofile.png") }}'
            );
            $("#add-user-profile-preview-container").hide();
        }
    });

    // When Edit button in view modal is clicked, show edit modal with values
    $("#btnEditUser").on("click", function () {
        const userid = $(this).data("userid");
        const name = $(this).data("name");
        const email = $(this).data("email");
        const site = $(this).data("site");
        const level = $(this).data("level");
        const gender = $(this).data("gender");
        const profile = $(this).data("profile");

        // Set values in edit modal
        $("#edit-user-label-name").text(name || userid);
        $("#edit-user-profile-preview").attr("src", profile);
        $("#edit-rssite").val(site);
        $("#edit-userid").val(userid);
        $("#edit-userid-hidden").val(userid);
        $("#edit-name").val(name);
        $("#edit-email").val(email);
        $("#edit-gender").val(gender);
        $("#edit-level").val(level);
        $("#edit-password").val("");
        $("#edit-existing-profile-pic").val(profile);

        // Set form action to the appropriate resource URL (adjust if your route differs)
        // $("#editUserForm").attr("action", "/irms/manage-users/" + userid);

        // Hide view modal then show edit modal
        $("#viewUserModal").modal("hide");
        $("#editUserModal").modal("show");
    });

    $("#edit_profile_pic").on("change", function (e) {
        const input = this;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $("#edit-user-profile-preview").attr("src", e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    });

    // Show warehouse view modal when a row is clicked
    $("#warehouse-table tbody").on("click", "tr", function () {
        const $row = $(this);
        // Store current row data for use in edit modal
        $("#editWarehouseBtn")
            .data("rssite", $row.data("rssite"))
            .data("site_desc", $row.data("site_desc"))
            .data("rswhse", $row.data("rswhse"))
            .data("name", $row.data("name"))
            .data("addr", $row.data("addr"));
        // Fill view modal
        $("#view-warehouse-site-desc").text($row.data("rssite_desc") || "-");
        $("#view-warehouse-code").text($row.data("rswhse") || "-");
        $("#view-warehouse-name").text($row.data("name") || "-");
        $("#view-warehouse-addr").text($row.data("addr") || "-");
        $("#view-warehouse-label-name").text($row.data("name") || "-");
        $("#viewWarehouseModal").modal("show");
    });

    // When Edit button in view modal is clicked, show edit modal with values
    $("#editWarehouseBtn").on("click", function () {
        const rssite = $(this).data("rssite");
        const rswhse = $(this).data("rswhse");
        const name = $(this).data("name");
        const addr = $(this).data("addr");
        // Set select value for site
        $("#edit-rssite").val(rssite);
        // Set input values
        $("#edit-rswhse").val(rswhse);
        $("#edit-name").val(name);
        $("#edit-addr").val(addr);
        // Set hidden original keys
        $("#edit-orig-rssite").val(rssite);
        $("#edit-orig-rswhse").val(rswhse);

        $("#viewWarehouseModal").modal("hide");
        $("#editWarehouseModal").modal("show");
    });

        //Rack Viewing Modals
    $("#rackTable tbody").on("click", "tr", function () {
        const $row = $(this);

        $("#view-rack-warehouse").text($row.data("rswhse") || "-");
        $("#view-rack-baynum").text($row.data("rsbaynum") || "-");
        $("#view-rack-location").text($row.data("rsloc") || "-");
        $("#view-rack-description").text($row.data("rsdesc") || "-");
        $("#view-rack-quantity").text($row.data("qty") || "-");
        $("#view-rack-createDate").text($row.data("createDate") || "-");
        $("#viewRackModal").modal("show");
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Fade in content wrapper
    const wrapper = document.querySelector(".content-wrapper");
    if (wrapper) {
        setTimeout(() => {
            wrapper.classList.add("visible");
        }, 100);
    }

    const alert = document.getElementById("alerts");
    if (alert) {
        setTimeout(() => {
            alert.style.opacity = "0";
            setTimeout(() => {
                alert.style.display = "none";
            }, 700); // matches the transition duration
        }, 3000); // show for 3 seconds
    }




    //Rack Location Add Form - Filter Warehouse and Bay Number based on selected Site
    const siteSelect = document.getElementById("rssite");
    const whseSelect = document.getElementById("rswhse");
    const baySelect = document.getElementById("rsbaynum");
    const rslocInput = document.getElementById("rsloc");
    const rsdecInput = document.getElementById("rsdec");

    function filterOptions(select, siteValue) {
        Array.from(select.options).forEach((option) => {
            if (!option.value) return; // skip placeholder
            option.style.display =
                option.getAttribute("data-site") === siteValue ? "" : "none";
        });
        // Reset selection if current value is not visible
        if (
            select.selectedIndex > 0 &&
            select.options[select.selectedIndex].style.display === "none"
        ) {
            select.selectedIndex = 0;
        }
    }

    // Reset warehouse, bay, and other inputs when site changes
    if (siteSelect) {
        siteSelect.addEventListener("change", function () {
            whseSelect.selectedIndex = 0;
            baySelect.selectedIndex = 0;
            filterOptions(whseSelect, this.value);
            filterOptions(baySelect, this.value);

            // Blank other inputs
            if (rslocInput) rslocInput.value = "";
            if (rsdecInput) rsdecInput.value = "";
        });
    }

    // Reset bay and other inputs when warehouse changes
    if (whseSelect) {
        whseSelect.addEventListener("change", function () {
            baySelect.selectedIndex = 0;

            // Blank other inputs
            if (rslocInput) rslocInput.value = "";
            if (rsdecInput) rsdecInput.value = "";
        });
    }

    // Reset other inputs when bay changes
    if (baySelect) {
        baySelect.addEventListener("change", function () {
            if (rslocInput) rslocInput.value = "";
            if (rsdecInput) rsdecInput.value = "";
        });
    }

    // Initial filter on page load if old value exists
    if (siteSelect && siteSelect.value) {
        filterOptions(whseSelect, siteSelect.value);
        filterOptions(baySelect, siteSelect.value);
    }
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

        // generate random data for each dataset
        const dataA = randArray(labels.length, 5, 95);
        const dataB = randArray(labels.length, 5, 95);
        const dataC = randArray(day_name_label.length, 0, 120);
        const dataC2 = randArray(day_name_label.length, 0, 120);
        const dataD = randArray(site_labels.length, 10, 80);
        const dataE = randArray(site_labels.length, 5, 95);
        const dataF = randArray(site_labels.length, 5, 95);

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
