import Swal from "sweetalert2";

function loadSiteTable() {
    $.get(window.appUrl + "/irms/manage-sites/site-list", function (html) {
        if ($.fn.DataTable.isDataTable("#site-table")) {
            $("#site-table").DataTable().clear().destroy();
        }
        $("#siteTableBody").html(html);
        const sitesTable = $("#site-table").DataTable({
            pageLength: 5,
            fixedHeader: true,
            columnControl: ["order", ["searchList"]],
            ordering: {
                indicators: false,
                handler: true,
            },
            responsive: true,
            language: {
                emptyTable: "No sites found",
            },
        });
        $("#siteSearch").on("keyup", function () {
            sitesTable.search(this.value).draw();
        });
    });
}


if (window.location.pathname.includes('/manage-sites')) {
    loadSiteTable();
}

$("#btnSaveSite").on("click", function (e) {
    e.preventDefault();
    const form = $("#addSiteForm")[0];
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
                title: data.message || "Site added successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            $("#addSiteModal").modal("hide");
            form.reset();
            loadSiteTable(); // reload table via AJAX
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

$("#site-table tbody").on("click", "tr", function () {
    const $row = $(this);
    $("#view-site-code").text($row.data("rssite") || "-");
    $("#view-site-desc").text($row.data("rssite_desc") || "-");
    $("#view-site-address").text($row.data("address") || "-");
    $("#view-site-link").html($row.data("site_link") ? `<a href="${$row.data("site_link")}" target="_blank">${$row.data("site_link")}</a>` : "N/A");
    $("#view-site-logo").attr("src", $row.data("logo_pic_url") || "");
    $("#viewSiteModal").modal("show");
});


