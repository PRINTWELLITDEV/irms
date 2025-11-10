$.extend($.fn.dataTable.defaults, {
    paging: true,
    info: true,
    lengthChange: false,
    searching: true,
    pageLength: 10,
    responsive: true,
    autoWidth: false,
    order: [],
    layout: {
        topStart: null, // Hides search box
        topEnd: null, // Hides page length / buttons
        bottomStart: "info", // Keep info text
        bottomEnd: "paging", // Keep pagination
    },
    columnDefs: [{ targets: "_all", type: "string" }],
});

$(document).ready(function () {

    // Sites table
    const sitesTable = $("#sites-table").DataTable({
        pageLength: 5,
        fixedHeader: true,
        columnControl: ["order", ['searchList']],
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

    // Users table
    const usersTable = $("#users-table").DataTable({
        pageLength: 9,
        fixedHeader: true,
        ordering: {
            indicators: false,
            handler: false,
        },
        responsive: true,
        language: {
            emptyTable: "No recently users transaction found",
        },
        // "rowCallback" : function(row, data, index){
        //     var status = $(row).attr('data-type');

        //     if(status == 'R'){
        //         $(row).addClass('bg-primary');
        //         $(row).addClass('bg-gradient');
        //     }else if(status == 'D'){
        //         $(row).addClass('bg-danger');
        //         $(row).addClass('bg-gradient');
        //     }
        // }

    });
    $("#userSearch").on("keyup", function () {
        usersTable.search(this.value).draw();
    });




    // Warehouse table
    const warehouseTable = $("#warehouse-table").DataTable({
        fixedHeader: true,
        columnControl: ["order", ['searchList']],
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

    //Bay Location Search Function
    $("#baylocSearch").on("keyup", function () {
        bayLocationTable.search(this.value).draw();
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

    // Rack Location table
    const rackTable = $("#rackTable").DataTable({
        fixedHeader: true,
        columnControl: ["order", ['searchList']],
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

    // Item Locations table
    const itemLocTable = $("#itemloc-table").DataTable({
        fixedHeader: true,
        columnControl: ["order", ['searchList']],
        ordering: {
            indicators: false,
            handler: true,
        },
        responsive: true,
        language: {
            emptyTable: "No Item Locations found",
        },
    });

    // Search function for item locations
    $("#itemSearch").on("keyup", function () {
        itemLocTable.search(this.value).draw();
    });

    // Item Locations job-rack-list table
    const jobRackListTable = $("#job-details-table").DataTable({
        fixedHeader: true,
        columnControl: ["order", ['searchList']],
        ordering: {
            indicators: false,
            handler: true,
        },
        responsive: true,
        language: {
            emptyTable: "No Item Locations found",
        },
    });

    // Search function for job rack list
    $("#jobRackSearch").on("keyup", function () {
        jobRackListTable.search(this.value).draw();
    });

    // Transaction table
    const transTable = $("#transaction-table").DataTable({
        fixedHeader: true,
        columnControl: ["order", ['searchList']],
        ordering: {
            indicators: false,
            handler: true,
        },
        responsive: true,
        language: {
            emptyTable: "No Item Locations found",
        },
    });

    // Search function for item locations
    $("#transSearch").on("keyup", function () {
        transTable.search(this.value).draw();
    });

});
