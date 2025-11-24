import Swal from "sweetalert2";
import moment from "moment";

// function renderRackLocTable(data, userLevel) {
//     let html = "";
//     data.forEach(function (rack) {
//         html += `<tr data-rssite="${rack.rssite}"
//                     data-rssite_desc="${rack.rssite_desc || ""}"
//                     data-rswhse="${rack.rswhse}"
//                     data-rsbaynum="${rack.rsbaynum}"
//                     data-rsloc="${rack.rsloc}"
//                     data-rsdesc="${rack.rsdesc || ""}"
//                     data-qty="${rack.qty || 0}"
//                     data-create-date="${
//                         rack.createdate
//                             ? moment(rack.createdate).format("DD MMMM YYYY")
//                             : ""
//                     }">
//                     <td>${rack.rsloc}</td>
//                     <td>${rack.rswhse}</td>
//                     <td>${rack.rsbaynum}</td>
//                     <td class="text-end"> ${Number(
//                         rack.qty || 0
//                     ).toLocaleString(undefined, {
//                         maximumFractionDigits: 0,
//                     })} </td>
//                     ${
//                         userLevel == 1
//                             ? `<td>${rack.rssite_desc || "N/A"}</td>`
//                             : ""
//                     }
//                 </tr>`;
//     });
//     $("#rackTableBody").html(html);
// }

function loadRackLocTable() {
    $.get(window.appUrl + "/irms/rack-locations/rack-list", function (html) {
        if ($.fn.DataTable.isDataTable("#rackTable")) {
            $("#rackTable").DataTable().clear().destroy();
        }
        $("#rackTableBody").html(html);
        // Initialize DataTable AFTER rows are rendered
        const rackTable = $("#rackTable").DataTable({
            fixedHeader: true,
            responsive: true,
            columnControl: ["order", ["searchList"]],
            ordering: {
                indicators: false,
                handler: true,
            },
            language: {
                emptyTable: "No Rack found",
            },
        });
        $("#rackSearch").on("keyup", function () {
            rackTable.search(this.value).draw();
        });
    });
}

//For Future use with different columnDefs based on user level -Trick
// function loadRackLocTable() {
//     $.get(window.appUrl + "/irms/rack-locations/rack-list", function (html) {
//         if ($.fn.DataTable.isDataTable("#rackTable")) {
//             $("#rackTable").DataTable().clear().destroy();
//         }
//         $("#rackTableBody").html(html);

//         const userLevel = $("main").data("user-level");
//         let columnDefs = [];

//         if (userLevel == 1) {
//             // Show Site column (target 4) with searchList, others with order
//             columnDefs = [
//                 {
//                     targets: 4, // Site column
//                     columnControl: ["order", ["searchList"]],
//                 },
//                 {
//                     targets: [0, 1, 2, 3], // Other columns
//                     columnControl: ["order"],
//                 }
//             ];
//         } else {
//             // No Site column, all columns with order only
//             columnDefs = [
//                 {
//                     targets: "_all",
//                     columnControl: ["order"],
//                 }
//             ];
//         }

//         const rackTable = $("#rackTable").DataTable({
//             fixedHeader: true,
//             responsive: true,
//             columnDefs: columnDefs,
//             ordering: {
//                 indicators: false,
//                 handler: true,
//             },
//             language: {
//                 emptyTable: "No Rack found",
//             },
//         });

//         // Search only in Site column if user-level == 1
//         $("#rackSearch").on("keyup", function () {
//             rackTable.search(this.value).draw();
//         });
//     });
// }


// Call on page load

if (window.location.pathname.includes('/rack-locations')) {
    loadRackLocTable();
}

// setInterval(function() {
//     if (window.location.pathname.includes('/rack-locations')) {
//         loadRackLocTable();
//     }
// }, 5000);

// AJAX Save for Add Rack
$("#btnSaveRack").on("click", function (e) {
    e.preventDefault();
    const form = $("#addRackModal form")[0];
    const formData = new FormData(form);

    $.ajax({
        url: $(form).attr("action"),
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: data.message || "Rack location added successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            $("#addRackModal").modal("hide");
            form.reset();
            loadRackLocTable(); // reload table via AJAX
        },
        error: function (xhr) {
            let msg = "An error occurred.";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors).join("<br>");
            }
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "error",
                title: msg,
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
            });
        },
    });
});

// Rack Location Table row selection function
$("#rackTable tbody").on("click", "tr", function () {
    const $row = $(this);
    $("#title-rack-location").text($row.data("rsloc") || "");
    $("#view-rack-warehouse").text($row.data("rswhse") || "");
    $("#view-rack-baynum").text($row.data("rsbaynum") || "");
    $("#view-rack-location").text($row.data("rsloc") || "");
    $("#view-rack-description").text($row.data("rsdesc") || "");
    $("#view-rack-quantity").text(
        parseFloat($row.data("qty") || 0).toLocaleString(undefined, {
            maximumFractionDigits: 2,
        })
    );
    $("#view-rack-createDate").text($row.attr("data-create-date") || "");

    // Fetch rack items via AJAX
    $.post({
        url: window.appUrl + "/irms/rack-locations/rack-items",
        data: {
            rsloc: $row.data("rsloc"),
            _token: $('input[name="_token"]').val(),
        },
        success: function (items) {
            let html = "";
            if (items.length === 0) {
                html = `<tr><td colspan="3" class="text-center text-muted">No Job items found.</td></tr>`;
            } else {
                items.forEach((item) => {
                    html += `<tr>
                        <td>${item.job}</td>
                        <td>
                            <div class="fw-semibold">${item.item}</div>
                            <div class="small text-muted">${
                                item.desc || ""
                            }</div>
                        </td>
                        <td>${parseFloat(item.qty).toLocaleString()} ${
                        item.um || ""
                    }</td>
                    </tr>`;
                });
            }
            $("#view-rack-items-body").html(html);
        },
    });

    // Show modal (or offcanvas if you changed to sidebar)
    $("#viewRackModal").modal("show");
});

// --- Rack Map Filter: Show warehouse and bay options depending on rssite (map tab) ---
// Map tab filter logic
const mapSiteSelect = document.getElementById("mapRsSite");
const mapWhseSelect = document.getElementById("mapRsWhse");
const mapBaySelect = document.getElementById("mapRsBay");

function filterMapOptions(select, siteValue) {
    if (!select) return;
    Array.from(select.options).forEach((option) => {
        if (!option.value) return;
        option.style.display =
            option.getAttribute("data-site") === siteValue ? "" : "none";
    });
    // Reset selection if current is hidden
    if (
        select.selectedIndex > 0 &&
        select.options[select.selectedIndex].style.display === "none"
    ) {
        select.selectedIndex = 0;
    }
}

function filterMapBaysByWarehouse(select, siteValue, whseValue) {
    if (!select || !siteValue || !whseValue) return;
    
    Array.from(select.options).forEach((option) => {
        if (!option.value) return;
        
        const optionSite = option.getAttribute("data-site");
        const optionWhse = option.getAttribute("data-whse");
        const shouldShow = optionSite === siteValue && optionWhse === whseValue;
        option.style.display = shouldShow ? "" : "none";
    });
    
    // Reset selection if current is hidden
    if (
        select.selectedIndex > 0 &&
        select.options[select.selectedIndex].style.display === "none"
    ) {
        select.selectedIndex = 0;
    }
}

// For 'sa', filter on change
if (mapSiteSelect && mapWhseSelect && mapBaySelect) {
    mapSiteSelect.addEventListener("change", function () {
        mapWhseSelect.selectedIndex = 0;
        mapBaySelect.selectedIndex = 0;
        filterMapOptions(mapWhseSelect, this.value);
        filterMapOptions(mapBaySelect, this.value);
    });

    mapWhseSelect.addEventListener("change", function () {
        mapBaySelect.selectedIndex = 0;
        filterMapBaysByWarehouse(mapBaySelect, mapSiteSelect.value, this.value);
    });

    // Initial filter on page load if old value exists
    if (mapSiteSelect.value) {
        filterMapOptions(mapWhseSelect, mapSiteSelect.value);
        filterMapOptions(mapBaySelect, mapSiteSelect.value);
    }
}

$("#mapRsSite, #mapRsWhse, #mapRsBay").on("change", function () {
    let rssite = $("#mapRsSite").val();
    let rswhse = $("#mapRsWhse").val();
    let rsbaynum = $("#mapRsBay").val();

    if (rssite && rswhse && rsbaynum) {
        $.post({
            url: window.appUrl + "/irms/rack-locations/map-grid",
            data: {
                rssite: rssite,
                rswhse: rswhse,
                rsbaynum: rsbaynum,
                _token: $('input[name="_token"]').val(),
            },
            success: function (data) {
                renderRackMapGrid(data, rsbaynum);
            },
        });
    }
});

let rackMapSortDirection = "ltr"; // "ltr" = Left to Right, "rtl" = Right to Left

// Show/hide sort button based on selection
function updateRackMapSortButton() {
    const whse = $("#mapRsWhse").val();
    const bay = $("#mapRsBay").val();
    if (whse && bay) {
        $("#btnRackMapSort").removeClass("d-none");
    } else {
        $("#btnRackMapSort").addClass("d-none");
    }
}

// Toggle sort direction and re-render grid
$(document).on("click", "#btnRackMapSort", function () {
    rackMapSortDirection = rackMapSortDirection === "ltr" ? "rtl" : "ltr";
    $("#rackMapSortText").text(
        rackMapSortDirection === "ltr" ? "Sort: Left to Right" : "Sort: Right to Left"
    );
    // Re-render grid if data is available
    let rssite = $("#mapRsSite").val();
    let rswhse = $("#mapRsWhse").val();
    let rsbaynum = $("#mapRsBay").val();
    if (rssite && rswhse && rsbaynum) {
        $.post({
            url: window.appUrl + "/irms/rack-locations/map-grid",
            data: {
                rssite: rssite,
                rswhse: rswhse,
                rsbaynum: rsbaynum,
                _token: $('input[name="_token"]').val(),
            },
            success: function (data) {
                renderRackMapGrid(data, rsbaynum);
            },
        });
    }
});

// Update sort button visibility on filter change
$("#mapRsSite, #mapRsWhse, #mapRsBay").on("change", function () {
    updateRackMapSortButton();
    let rssite = $("#mapRsSite").val();
    let rswhse = $("#mapRsWhse").val();
    let rsbaynum = $("#mapRsBay").val();

    if (rssite && rswhse && rsbaynum) {
        $.post({
            url: window.appUrl + "/irms/rack-locations/map-grid",
            data: {
                rssite: rssite,
                rswhse: rswhse,
                rsbaynum: rsbaynum,
                _token: $('input[name="_token"]').val(),
            },
            success: function (data) {
                renderRackMapGrid(data, rsbaynum);
            },
        });
    }
});

// Update renderRackMapGrid to use sort direction
function renderRackMapGrid(locations, rsbaynum) {
    // Levels
    let levels = [
        ...new Set(locations.map((l) => l.rsloc.match(/-(L\d+)-/)?.[1])),
    ]
        .filter(Boolean)
        .sort(
            (a, b) =>
                parseInt(b.replace("L", "")) - parseInt(a.replace("L", ""))
        );

    // Columns (C01, C02, ...)
    let columns = [
        ...new Set(
            locations.map((l) => {
                let m = l.rsloc.match(/-C(\d+)-S/);
                return m ? m[1] : null;
            })
        ),
    ]
        .filter(Boolean)
        .map(Number);

    // Slots
    let slots = [
        ...new Set(locations.map((l) => l.rsloc.match(/-(S\d+)$/)?.[1])),
    ]
        .filter(Boolean);

    // Sort columns and slots based on direction
    if (rackMapSortDirection === "ltr") {
        columns.sort((a, b) => a - b);
        slots.sort();
    } else {
        columns.sort((a, b) => b - a);
        slots.sort().reverse();
    }

    // Function to get background color based on quantity comparison
    function getQtyColor(currentQty, originalQty) {
        if (currentQty <= 0)
            return "linear-gradient(180deg, rgba(231, 255, 231, 1) 0%, rgba(198, 253, 198, 1) 100%)";

        let percentage = (currentQty / originalQty) * 100;
        if (percentage >= 100)
            return "linear-gradient(180deg, rgba(139, 0, 0, 1) 0%, rgba(80, 0, 0, 1) 100%)";
        else if (percentage >= 90)
            return "linear-gradient(180deg, rgba(255, 140, 0, 1) 0%, rgba(255, 140, 0, 1) 0%, rgba(139, 0, 0, 1) 100%)";
        else if (percentage >= 80)
            return "linear-gradient(180deg, rgba(255, 140, 0, 1) 0%, rgba(255, 140, 0, 1) 50%, rgba(139, 0, 0, 1) 100%)";
        else if (percentage >= 70)
            return "linear-gradient(180deg, rgba(255, 140, 0, 1) 0%, rgba(255, 140, 0, 1) 100%, rgba(139, 0, 0, 1) 100%)";
        else if (percentage >= 60)
            return "linear-gradient(180deg, rgba(255, 215, 0, 1) 0%, rgba(255, 140, 0, 1) 0%, rgba(255, 69, 0, 1) 100%)";
        else if (percentage >= 50)
            return "linear-gradient(180deg, rgba(255, 215, 0, 1) 0%, rgba(255, 140, 0, 1) 50%, rgba(255, 69, 0, 1) 100%)";
        else if (percentage >= 40)
            return "linear-gradient(180deg, rgba(255, 215, 0, 1) 0%, rgba(255, 140, 0, 1) 100%, rgba(255, 69, 0, 1) 100%)";
        else if (percentage >= 30)
            return "linear-gradient(180deg, rgba(255, 255, 255, 1) 0%, rgba(50, 205, 50, 1) 0%, rgba(255, 217, 47, 1) 100%)";
        else if (percentage >= 20)
            return "linear-gradient(180deg, rgba(255, 255, 255, 1) 0%, rgba(50, 205, 50, 1) 50%, rgba(255, 217, 47, 1) 100%)";
        else if (percentage >= 10)
            return "linear-gradient(180deg, rgba(255, 255, 255, 1) 0%, rgba(50, 205, 50, 1) 100%, rgba(255, 217, 47, 1) 100%)";
        else
            return "linear-gradient(180deg, rgba(255, 255, 255, 1) 0%, rgba(50, 205, 50, 1) 100%, rgba(255, 217, 47, 1) 100%)";
    }

    let html =
        '<table class="table table-bordered text-center align-middle"><tbody>';
    levels.forEach((level) => {
        html += "<tr>";
        columns.forEach((colNum) => {
            let colStr = colNum.toString().padStart(2, "0");
            slots.forEach((slot) => {
                let rsloc = `${rsbaynum}-${level}-C${colStr}-${slot}`;
                let found = locations.find((l) => l.rsloc === rsloc);
                let currentQty = found ? Math.floor(found.qty || 0) : 0;
                let originalQty = found
                    ? Math.floor(found.original_qty || 0)
                    : 0;
                let bgColor = getQtyColor(currentQty, originalQty);

                // Determine text color for readability
                let textColor = "";
                if (originalQty > 0) {
                    let percentage = (currentQty / originalQty) * 100;
                    textColor =
                        percentage >= 85 ? "color:white;" : "color:black;";
                } else {
                    textColor =
                        currentQty > 10000 ? "color:white;" : "color:black;";
                }

                // Display format: current / original (if original exists)
                let qtyDisplay = "";
                if (originalQty > 0) {
                    qtyDisplay = "<br>" + currentQty.toLocaleString();
                    qtyDisplay += ` / ${originalQty.toLocaleString()}`;
                    let percentage = (currentQty / originalQty) * 100;
                    // Show 2 decimal places if percentage is below 1%
                    let formattedPercentage =
                        percentage < 1
                            ? percentage.toFixed(2)
                            : Math.round(percentage);
                    qtyDisplay += ` <br>(${formattedPercentage}%)`;
                } else {
                    qtyDisplay = "<br>Empty<br>(0%)";
                }

                // ...inside renderRackMapGrid...
                html += `
                        <td 
                            data-rsloc="${found ? found.rsloc : ""}" 
                            data-rswhse="${found ? found.rswhse : ""}"
                            data-rsbaynum="${found ? found.rsbaynum : ""}"
                            data-rsdesc="${found ? found.rsdesc : ""}"
                            data-qty="${found ? found.qty : 0}"
                            data-create-date="${found && found.createdate ? moment(found.createdate).format("DD MMMM YYYY") : ""}"
                            data-has-items="${found && found.jobs && found.jobs.length > 0 ? '1' : '0'}"
                            style="min-width:60px;height:120px;vertical-align:middle;font-size:0.8em;background:${bgColor};${textColor};padding:4px;">
                            ${
                                found
                                    ? `<div class="fw-bold">${found.rsloc}</div><div class="fw-bold small">${qtyDisplay}</div>`
                                    : ""
                            }
                        </td>
                    `;
            });
        });
        html += "</tr>";
    });
    html += "</tbody></table>";
    $("#rack-map-grid").html(html);

    // Enable horizontal scroll
    enableHorizontalScroll();
}

// Add this after the renderRackMapGrid function
function enableHorizontalScroll() {
    const container = document.getElementById("rack-map-grid");
    if (!container) return;

    let isDown = false;
    let startX;
    let scrollLeft;

    container.addEventListener("mousedown", (e) => {
        isDown = true;
        container.style.cursor = "grabbing";
        startX = e.pageX - container.offsetLeft;
        scrollLeft = container.scrollLeft;
    });

    container.addEventListener("mouseleave", () => {
        isDown = false;
        container.style.cursor = "grab";
    });

    container.addEventListener("mouseup", () => {
        isDown = false;
        container.style.cursor = "grab";
    });

    container.addEventListener("mousemove", (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - container.offsetLeft;
        const walk = (x - startX) * 2; // Scroll speed multiplier
        container.scrollLeft = scrollLeft - walk;
    });
}

$(document).on("click", "#rack-map-grid td[data-rsloc]", function () {
    const $td = $(this);
    $("#title-rack-location").text($td.data("rsloc") || "");
    $("#view-rack-warehouse").text($td.data("rswhse") || "");
    $("#view-rack-baynum").text($td.data("rsbaynum") || "");
    $("#view-rack-location").text($td.data("rsloc") || "");
    $("#view-rack-description").text($td.data("rsdesc") || "");
    $("#view-rack-quantity").text(
        parseFloat($td.data("qty") || 0).toLocaleString(undefined, {
            maximumFractionDigits: 2,
        })
    );
    $("#view-rack-createDate").text($td.data("create-date") || "");

    // Fetch rack items via AJAX
    $.post({
        url: window.appUrl + "/irms/rack-locations/rack-items",
        data: {
            rsloc: $td.data("rsloc"),
            _token: $('input[name="_token"]').val(),
        },
        success: function (items) {
            let html = "";
            if (items.length === 0) {
                html = `<tr><td colspan="3" class="text-center text-muted">No items found.</td></tr>`;
            } else {
                items.forEach(item => {
                    html += `<tr>
                        <td>${item.job}</td>
                        <td>
                            <div class="fw-semibold">${item.item}</div>
                            <div class="small text-muted">${item.desc || ""}</div>
                        </td>
                        <td>${parseFloat(item.qty).toLocaleString()} ${item.um || ""}</td>
                    </tr>`;
                });
            }
            $("#view-rack-items-body").html(html);
        }
    });

    $("#viewRackModal").modal("show");
});

$(document).on("click", "#view-rack-items-body tr", function () {
    const job = $(this).find("td:first").text().trim();

    if (!job) {
        Swal.fire({
            toast: true,
            position: "top-end",
            icon: "error",
            title: "Job not found for this rack location.",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        return;
    }

    // Check job existence before redirect
    $.post({
        url: window.appUrl + "/irms/item-locations/job-exists",
        data: {
            job: job,
            _token: $('input[name="_token"]').val(),
        },
        success: function (response) {
            if (response.exists) {
                window.location.href = window.appUrl + "/irms/item-locations/" + encodeURIComponent(job);
            } else {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "info",
                    title: "Job does not exist.",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
        }
    });
});

$(document).ready(function () {
    // Get user level from main tag
    const userLevel = $("main").data("user-level");

    if (userLevel == 1) {
        // Super admin: reset rssite
        $("#mapRsSite").val('Select Site');
    } else {
        // Regular user: use rssite value for mapRsSite
        const rssite = $("#rssite").val();
        $("#mapRsSite").val(rssite);
    }

    // Always reset warehouse, bay, and grid
    $("#mapRsWhse").val('');
    $("#mapRsBay").val('');
    $("#rack-map-grid").html('');
});
