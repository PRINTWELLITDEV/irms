function loadItemLocTable() {
    $.get(window.appUrl + "/irms/item-locations/item-list", function (html) {
        if ($.fn.DataTable.isDataTable("#itemloc-table")) {
            $("#itemloc-table").DataTable().clear().destroy();
        }
        $("#itemloc-table tbody").html(html);
        const itemLocTable = $("#itemloc-table").DataTable({
            fixedHeader: true,
            columnControl: ["order", ["searchList"]],
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
    });
}

// Call on page load
if (window.location.pathname.includes("/item-locations")) {
    loadItemLocTable();
}
