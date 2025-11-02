import Swal from "sweetalert2";
import moment from "moment";

function renderRackLocTable(data, userLevel) {
    let html = "";
    data.forEach(function (rack) {
        html += `<tr data-rssite="${rack.rssite}"
                    data-rssite_desc="${rack.rssite_desc || ""}"
                    data-rswhse="${rack.rswhse}"
                    data-rsbaynum="${rack.rsbaynum}"
                    data-rsloc="${rack.rsloc}"
                    data-rsdesc="${rack.rsdesc || ""}"
                    data-qty="${rack.qty || 0}"
                    data-create-date="${
                        rack.createdate
                            ? moment(rack.createdate).format(
                                  "DD MMMM YYYY"
                              )
                            : ""
                    }">
                    <td>${rack.rsloc}</td>
                    <td>${rack.rswhse}</td>
                    <td>${rack.rsbaynum}</td>
                    <td class="text-end"> ${Number(rack.qty || 0).toLocaleString(undefined,{ maximumFractionDigits: 0 })} </td>
                    ${
                        userLevel == 1
                            ? `<td>${rack.rssite_desc || "N/A"}</td>`
                            : ""
                    }
                </tr>`;
    });
    $("#rackTableBody").html(html);
}

function loadRackLocTable() {
    $.get(window.appUrl + "/irms/rack-locations/rack-list", function (data) {
        // Destroy DataTable if already initialized
        if ($.fn.DataTable.isDataTable("#rackTable")) {
            $("#rackTable").DataTable().clear().destroy();
        }

        const userLevel = $("main").data("user-level");
        renderRackLocTable(data, userLevel);

        // Initialize DataTable AFTER rows are rendered
        const rackTable = $("#rackTable").DataTable({
            fixedHeader: true,
            columnControl: ["order", ["searchList"]],
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
    });
}

// Call on page load
loadRackLocTable();

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
    $("#viewRackModal").modal("show");
});
