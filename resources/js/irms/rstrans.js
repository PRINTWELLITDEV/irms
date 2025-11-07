function loadTransactionTable() {
    $.get(window.appUrl + "/irms/transactions/transaction-list", function (html) {
        if ($.fn.DataTable.isDataTable("#transaction-table")) {
            $("#transaction-table").DataTable().clear().destroy();
        }
        $("#transaction-table tbody").html(html);
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
}

// Call on page load
if (window.location.pathname.includes('/transactions')) {
    loadTransactionTable();
}