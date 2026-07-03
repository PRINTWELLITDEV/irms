function loadJobLocTable() {
    $.get(window.appUrl + "/irms/job-locations/job-list", function (html) {
        if ($.fn.DataTable.isDataTable("#jobloc-table")) {
            $("#jobloc-table").DataTable().clear().destroy();
        }
        $("#jobloc-table tbody").html(html);
        const jobLocTable = $("#jobloc-table").DataTable({
            fixedHeader: true,
            columnControl: ["order", ["searchList"]],
            ordering: {
                indicators: false,
                handler: true,
            },
            responsive: true,
            language: {
                emptyTable: "No Job Locations found",
            },
        });

        // Search function for job locations
        $("#jobSearch").on("keyup", function () {
            jobLocTable.search(this.value).draw();
        });
    });
}

// Call on page load
if (window.location.pathname.includes("/job-locations")) {
    loadJobLocTable();
}
