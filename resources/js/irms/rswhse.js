import Swal from "sweetalert2";
import moment from 'moment';

function renderWarehouseTable(data, userLevel) {
    let html = "";
    data.forEach(function (whse) {
        html += `<tr data-rssite="${whse.rssite}"
                        data-rssite_desc="${whse.rssite_desc || ""}"
                        data-rswhse="${whse.rswhse}"
                        data-name="${whse.name}"
                        data-addr="${whse.addr}">
                        <td>${whse.rswhse}</td>
                        <td>${whse.name}</td>
                        ${
                            userLevel == 1
                                ? `<td>${whse.rssite_desc || "N/A"}</td>`
                                : ""
                        }
                    </tr>`;
    });
    $("#warehouseTableBody").html(html);
}

function loadWarehouseTable() {
    $.get(window.appUrl + "/irms/warehouse/warehouse-list", function (data) {
        // Destroy DataTable if already initialized
        if ($.fn.DataTable.isDataTable("#warehouse-table")) {
            $("#warehouse-table").DataTable().clear().destroy();
        }

        const userLevel = $("main").data("user-level");
        renderWarehouseTable(data, userLevel);

        const warehouseTable = $("#warehouse-table").DataTable({
            fixedHeader: true,
            columnControl: ["order", ["searchList"]],
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
    });
}

// Call on page load

if (window.location.pathname.includes('/warehouse')) {
    loadWarehouseTable();
}

// setInterval(function() {
//     if (window.location.pathname.includes('/warehouse')) {
//         loadWarehouseTable();
//     }
// }, 5000);

$("#btnSaveWarehouse").on("click", function (e) {
    e.preventDefault();
    const form = $("#addWarehouseModal form")[0];
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
                title: data.message || "Warehouse saved successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            $("#addWarehouseModal").modal("hide");
            form.reset();
            loadWarehouseTable(); // reload table via AJAX
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

$("#btnUpdateWarehouse").on("click", function () {
    const form = $("#editWarehouseModal form")[0];
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
                title: data.message || "Warehouse updated successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            $("#editWarehouseModal").modal("hide");
            form.reset();
            loadWarehouseTable(); // reload table via AJAX
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
    $("#view-warehouse-site-desc").text($row.data("rssite_desc") || "");
    $("#view-warehouse-code").text($row.data("rswhse") || "");
    $("#view-warehouse-name").text($row.data("name") || "");
    $("#view-warehouse-addr").text($row.data("addr") || "");
    $("#view-warehouse-label-name").text($row.data("name") || "");
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

