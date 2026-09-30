import Swal from "sweetalert2";

document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('itemInquiryPage');
    if (!container) return;

    const routes = window.itemInquiryRoutes;

    const item = document.getElementById('item');
    const co = document.getElementById('co');
    const itemSuggestions = document.getElementById('itemSuggestions');

    const btnReset = document.getElementById('btnReset');

    const summaryTable = document.getElementById('itemInquirySummaryTable');

    const btnDownloadSummary = document.getElementById('btnDownloadSummary');
    // const btnDownloadDetailed = document.getElementById('btnDownloadDetailed');

    let summaryDT = null;



// ==========================================================
// ITEM AUTOCOMPLETE / SEARCH SUGGESTIONS
// ==========================================================

let itemSuggestionTimer = null;

item.addEventListener('input', function () {

    const search = this.value.trim();

    clearTimeout(itemSuggestionTimer);

    // Hide suggestions if less than 2 characters
    if (search.length < 2) {
        itemSuggestions.innerHTML = '';
        itemSuggestions.style.display = 'none';
        return;
    }

    // Wait before sending request
    itemSuggestionTimer = setTimeout(function () {

        $.get(routes.itemSuggestions, {
            term: search
        })
        .done(function (items) {

            itemSuggestions.innerHTML = '';

            if (!items || items.length === 0) {
                itemSuggestions.style.display = 'none';
                return;
            }

            items.forEach(function (itemCode) {

                const option = document.createElement('button');

                option.type = 'button';
                option.className = 'list-group-item list-group-item-action';

                option.textContent = itemCode;

                option.addEventListener('click', function () {

                    // Put the selected full item code into input
                    item.value = itemCode;

                    // Hide suggestions
                    itemSuggestions.innerHTML = '';
                    itemSuggestions.style.display = 'none';

                    // Trigger your existing search function
                    onFilterChange();
                });

                itemSuggestions.appendChild(option);
            });

            itemSuggestions.style.display = 'block';

        })
        .fail(function () {

            itemSuggestions.innerHTML = '';
            itemSuggestions.style.display = 'none';

        });

    }, 300);
});






    // ==========================================================
    // CHECK IF BOTH FILTERS HAVE VALUES
    // ==========================================================
    function hasFilters() {
        return item.value.trim() !== '' && co.value.trim() !== '';
    }


    // ==========================================================
    // DESTROY DATATABLE
    // ==========================================================
    function destroyDataTable(selector) {

        if ($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().clear().destroy();
        }

        summaryDT = null;
    }


    // ==========================================================
    // AUTOMATICALLY LOAD RESULT WHEN USER STOPS TYPING
    // ==========================================================
    let debounceTimer = null;

    function onFilterChange() {

        clearTimeout(debounceTimer);

        // Hide download buttons while filters are changing
        btnDownloadSummary.classList.add('d-none');
        // btnDownloadDetailed.classList.add('d-none');

        // If both Item and CO are not filled
        if (!hasFilters()) {

            resetReport();

            return;
        }

        // Wait 500ms after user stops typing
        debounceTimer = setTimeout(function () {

            loadSummaryTable();

        }, 500);
    }


    // ==========================================================
    // LISTEN TO ITEM / CO INPUT
    // ==========================================================
    item.addEventListener('input', onFilterChange);
    co.addEventListener('input', onFilterChange);


    // ==========================================================
    // LOAD SUMMARY TABLE
    // ==========================================================
    function loadSummaryTable() {

        destroyDataTable('#item-inquiry-summary-table');

        summaryTable.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="mt-2 text-muted">Loading...</div>
                </td>
            </tr>
        `;

        $.get(routes.reportList, {
            viewType: 'summary',
            item: item.value.trim(),
            co: co.value.trim()
        })

        .done(function (html) {

            if (!html || html.trim() === '') {

                summaryTable.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            No result found.
                        </td>
                    </tr>
                `;

                btnDownloadSummary.classList.add('d-none');
                // btnDownloadDetailed.classList.add('d-none');

                Swal.fire({
                    icon: 'info',
                    title: 'No Result Found',
                    text: 'No records match the Item and CO you entered.'
                });

                return;
            }


            // Insert server-generated rows
            summaryTable.innerHTML = html;


            // ==================================================
            // INITIALIZE DATATABLE
            // ==================================================
            summaryDT = $('#item-inquiry-summary-table').DataTable({

                fixedHeader: true,

                columnControl: [
                    "order",
                    ['searchList']
                ],

                ordering: {
                    indicators: false,
                    handler: true
                },

                responsive: {
                    details: false
                },

                columnDefs: [
                    {
                        orderable: false,
                        className: 'text-center',
                        targets: 0
                    }
                ],

                language: {
                    emptyTable: 'No records found'
                }
            });


            // Show Summary download button
            btnDownloadSummary.classList.remove('d-none');

            // Show Detailed download button
            // btnDownloadDetailed.classList.remove('d-none');

        })

        .fail(function (xhr) {

            handleAjaxError(xhr);

        });
    }


    // ==========================================================
    // SUMMARY ROW CLICK
    // ==========================================================
    $(document).on(
        'click',
        '#item-inquiry-summary-table tbody tr',
        function () {

            // Ignore placeholder rows
            if ($(this).find('td[colspan]').length) {
                return;
            }

            const tr = $(this);

            const row = summaryDT.row(tr);


            // ==================================================
            // COLLAPSE CURRENT ROW
            // ==================================================
            if (row.child.isShown()) {

                row.child.hide();

                tr.removeClass('detail-shown');

                tr.find('.row-expand-icon')
                    .removeClass('bi-chevron-up')
                    .addClass('bi-chevron-down');

                return;
            }


            // ==================================================
            // CLOSE OTHER OPEN ROWS
            // ==================================================
            summaryDT.rows().every(function () {

                if (this.child.isShown()) {

                    this.child.hide();

                    $(this.node())
                        .removeClass('detail-shown')
                        .find('.row-expand-icon')
                        .removeClass('bi-chevron-up')
                        .addClass('bi-chevron-down');
                }

            });


            // ==================================================
            // GET ROW DATA
            // ==================================================
            const rowData = tr.data();

            const detailParams = {
                item: item.value.trim(),
                co: co.value.trim(),
                warehouse: rowData.warehouse,
                bay: rowData.bay,
                status: rowData.status
            };


            // Show loading
            row.child(detailLoadingHtml()).show();

            tr.addClass('detail-shown');

            tr.find('.row-expand-icon')
                .removeClass('bi-chevron-down')
                .addClass('bi-chevron-up');


            // ==================================================
            // LOAD DETAILED DATA
            // ==================================================
            $.get(routes.reportList, {
                viewType: 'detailed',
                item: item.value.trim(),
                co: co.value.trim(),

                warehouse: rowData.warehouse,

                bay: rowData.bay,

                status: rowData.status

            })

            .done(function (html) {

                if (!html || html.trim() === '') {

                    row.child(detailEmptyHtml()).show();

                    return;
                }

                row.child(
                    wrapDetailTable(html, detailParams)
                ).show();

            })

            .fail(function (xhr) {

                row.child(
                    '<div class="text-danger p-3">' +
                    'Failed to load details.' +
                    '</div>'
                ).show();

                handleAjaxError(xhr);

            });

        }
    );


  function detailLoadingHtml() {
    return `
        <div class="detail-panel p-4 text-center">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            <span class="ms-2 text-muted">Loading details...</span>
        </div>
    `;
}

function detailEmptyHtml() {
    return `
        <div class="detail-panel p-4 text-center text-muted">
            <i class="bi bi-inbox me-1"></i>
            No detailed records found for this row.
        </div>
    `;
}

function wrapDetailTable(rowsHtml, detailParams) {

    return `
        <div class="detail-panel p-3">

            <!-- DETAILED HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-3">

                <h6 class="mb-0 fw-bold" style="font-size: 22px;">
                    Detailed
                </h6>

                <button type="button"
                        id="btnDownloadDetailed"
                        class="btn btn-primary btn-sm"
                        data-item="${detailParams.item}"
                        data-co="${detailParams.co}"
                        data-warehouse="${detailParams.warehouse}"
                        data-bay="${detailParams.bay}"
                        data-status="${detailParams.status}">
                    <i class="bi bi-file-earmark-pdf me-1"></i>
                    Download Detailed
                </button>
            </div>

            <!-- DETAILED TABLE -->
            <div class="table-responsive">

                <table class="table table-sm table-bordered mb-0 detail-inner-table">

                    <thead>
                        <tr>
                            <th>Warehouse</th>
                            <th>CO</th>
                            <th>Item</th>
                            <th>Item Description</th>
                            <th>U/M</th>
                            <th>Qty</th>
                            <th>Date Received</th>
                            <th>Location</th>
                            
                        </tr>
                    </thead>

                    <tbody>
                        ${rowsHtml}
                    </tbody>

                </table>

            </div>

        </div>
    `;
}


    // ==========================================================
    // AJAX ERROR
    // ==========================================================
    function handleAjaxError(xhr) {

        let message = 'Failed to retrieve data.';

        if (
            xhr.responseJSON &&
            xhr.responseJSON.message
        ) {
            message = xhr.responseJSON.message;
        }

        Swal.fire({
            icon: 'error',
            title: 'Search Failed',
            text: message
        });
    }


    // ==========================================================
    // DOWNLOAD SUMMARY PDF
    // ==========================================================
    btnDownloadSummary.addEventListener(
        'click',
        function () {

            const params = new URLSearchParams({

                item: item.value.trim(),

                co: co.value.trim()

            });

            window.open(
                `${routes.pdfSummary}?${params.toString()}`,
                '_blank'
            );

        }
    );


    // ==========================================================
    // DOWNLOAD DETAILED PDF
    // ==========================================================
    // btnDownloadDetailed.addEventListener(
    //     'click',
    //     function () {

    //         const params = new URLSearchParams({

    //             item: item.value.trim(),

    //             co: co.value.trim()

    //         });

    //         window.open(
    //             `${routes.pdfDetailed}?${params.toString()}`,
    //             '_blank'
    //         );

    //     }
    // );
$(document).on('click', '#btnDownloadDetailed', function (e) {

    e.stopPropagation();

    const btn = $(this);

    const params = new URLSearchParams({
        item: btn.data('item') ?? '',
        co: btn.data('co') ?? '',
        warehouse: btn.data('warehouse') ?? '',
        bay: btn.data('bay') ?? '',
        status: btn.data('status') ?? ''
    });

    window.open(
        `${routes.pdfDetailed}?${params.toString()}`,
        '_blank'
    );
});

    // ==========================================================
    // RESET REPORT
    // ==========================================================
    function resetReport() {

        destroyDataTable(
            '#item-inquiry-summary-table'
        );

        summaryTable.innerHTML = `
            <tr>
                <td
                    colspan="8"
                    class="text-center text-muted py-4">

                    Enter Item and CO to view the summary.

                </td>
            </tr>
        `;

        btnDownloadSummary.classList.add('d-none');

        // btnDownloadDetailed.classList.add('d-none');
    }


    // ==========================================================
    // RESET BUTTON
    // ==========================================================
    btnReset.addEventListener(
        'click',
        function () {

            clearTimeout(debounceTimer);

            item.value = '';

            co.value = '';

            resetReport();

        }
    );

});