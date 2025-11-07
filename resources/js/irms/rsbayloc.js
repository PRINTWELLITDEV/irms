import Swal from "sweetalert2";
import moment from 'moment';

// function renderBayLocTable(data, userLevel) {
//     let html = "";
//     data.forEach(function(bay) {
//         html += `<tr data-rssite="${bay.rssite}"
//                     data-rssite_desc="${bay.rssite_desc || ''}"
//                     data-rsbaynum="${bay.rsbaynum}"
//                     data-create-date="${bay.createdate ? moment(bay.createdate).format('D MMMM YYYY') : ''}"
//                     data-createdby="${bay.name || ''}">
//                     <td>${bay.rsbaynum}</td>
//                     ${userLevel == 1 ? `<td>${bay.rssite_desc || 'N/A'}</td>` : ""}
//                 </tr>`;
//     });
//     $("#baylocTableBody").html(html);
// }

function loadBayLocTable() {
    $.get(window.appUrl + "/irms/bay-locations/bay-list", function (html) {
        if ($.fn.DataTable.isDataTable("#bayloc-table")) {
            $("#bayloc-table").DataTable().clear().destroy();
        }
        $("#baylocTableBody").html(html);
        const bayLocationTable = $("#bayloc-table").DataTable({
            fixedHeader: true,
            columnControl: ["order", ['searchList']],
            ordering: {
                indicators: false,
                handler: true,
            },
            responsive: true,
            language: {
                emptyTable: "No Bay found",
            },
        });
        $("#baylocSearch").on("keyup", function () {
            bayLocationTable.search(this.value).draw();
        });
    });
}

// Call on page load
if (window.location.pathname.includes('/bay-locations')) {
    loadBayLocTable();
}

// setInterval(function() {
//     if (window.location.pathname.includes('/bay-locations')) {
//         loadBayLocTable();
//     }
// }, 5000);

// AJAX Save for Add Bay
$("#addBayModal .btn-success").on("click", function (e) {
    e.preventDefault();
    const form = $("#addBayModal form")[0];
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
                title: data.message || "Bay location added successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            $("#addBayModal").modal("hide");
            form.reset();
            loadBayLocTable(); // reload table via AJAX
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
                timerProgressBar: true
            });
        }
    });
});

//Bay Location Table row selection function
$("#bayloc-table tbody").on("click", "tr", function () {
    const $row = $(this);
    $("#view-bay-number").text($row.data("rsbaynum") || "");
    $("#view-created-date").text($row.data("createDate") || "");
    $("#view-created-by").text($row.data("createdby") || "");
    $("#view-bay-site-desc").text($row.data("rssite_desc") || "");
    $("#viewBayModal").modal("show");
});