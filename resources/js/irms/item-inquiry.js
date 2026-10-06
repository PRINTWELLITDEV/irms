import Swal from "sweetalert2";

document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('itemInquiryPage');
    if (!container) return;

    const routes = window.itemInquiryRoutes;

    const item = document.getElementById('item');
    const co = document.getElementById('co');
    const itemSuggestions = document.getElementById('itemSuggestions');
    const coSuggestions = document.getElementById('coSuggestions');
    const btnReset = document.getElementById('btnReset');

    const summaryTable = document.getElementById('itemInquirySummaryTable');
    const btnDownloadSummary = document.getElementById('btnDownloadSummary');

    let summaryDT = null;


    // ==========================================================
    // STYLES FOR VERIFIED INPUT (GREEN BORDER + CHECK ICON)
    // ==========================================================
    const verifiedStyle = document.createElement('style');
    verifiedStyle.textContent = `
        .input-verified {
            border-color: #198754 !important;
            box-shadow:
                0 0 0 0.25rem rgba(25, 135, 84, 0.30),
                0 0 14px 3px rgba(25, 135, 84, 0.45) !important;
            transition: border-color .25s ease, box-shadow .25s ease;
            padding-right: 2.5rem !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 1.25rem 1.25rem !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3ccircle cx='8' cy='8' r='8' fill='%23198754'/%3e%3cpath fill='none' stroke='%23fff' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round' d='M4.5 8.5l2.2 2.2 4.8-5'/%3e%3c/svg%3e") !important;
        }
        .input-verified:focus {
            border-color: #00f181 !important;
            box-shadow:
                0 0 0 0.3rem rgba(0, 218, 116, 0.4),
                0 0 20px 5px rgba(25, 135, 84, 0.55) !important;
        }

        /* One-time pulse when a value is selected (box-shadow only, no layout shift) */
        .verified-pop {
            animation: verifiedPulse .6s ease-out;
        }
        @keyframes verifiedPulse {
            0%   { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.8), 0 0 0 0 rgba(25, 135, 84, 0.6); }
            100% { box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.30), 0 0 14px 3px rgba(25, 135, 84, 0.45); }
        }

        /* Red flash when nothing matches */
        .input-nomatch {
            border-color: #dc3545 !important;
            animation: nomatchPulse .7s ease-out;
        }
        @keyframes nomatchPulse {
            0%   { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
            100% { box-shadow: 0 0 0 0.4rem rgba(220, 53, 69, 0); }
        }

        /* Suggestion list polish */
        .list-group.suggestions-anim,
        #itemSuggestions[style*="block"],
        #coSuggestions[style*="block"] {
            animation: suggestionsFade .15s ease-out;
        }
        @keyframes suggestionsFade {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        #itemSuggestions .list-group-item-action,
        #coSuggestions .list-group-item-action {
            transition: background-color .12s ease, box-shadow .12s ease;
            cursor: pointer;
        }
        #itemSuggestions .list-group-item-action:hover,
        #coSuggestions .list-group-item-action:hover,
        .suggestion-active {
            background-color: #e8f5ee !important;
            box-shadow: inset 4px 0 0 #198754;
        }
        .suggestion-match {
            font-weight: 700;
            color: #198754;
        }
        .suggestion-status {
            font-style: italic;
            pointer-events: none;
        }
    `;
    document.head.appendChild(verifiedStyle);

    function markVerified(input) {
        input.classList.remove('input-nomatch', 'verified-pop');
        input.classList.add('input-verified');

        // restart the pulse animation
        void input.offsetWidth;
        input.classList.add('verified-pop');
    }

    function clearVerified(input) {
        input.classList.remove('input-verified', 'verified-pop');
    }

    function flashNoMatch(input) {
        input.classList.remove('input-nomatch');
        void input.offsetWidth;
        input.classList.add('input-nomatch');
        setTimeout(function () {
            input.classList.remove('input-nomatch');
        }, 700);
    }

    // Bold + green the part of the suggestion that matches what was typed
    function renderHighlighted(el, value, term) {
        const idx = value.toLowerCase().indexOf(term.toLowerCase());

        if (idx === -1) {
            el.textContent = value;
            return;
        }

        const mark = document.createElement('span');
        mark.className = 'suggestion-match';
        mark.textContent = value.slice(idx, idx + term.length);

        el.appendChild(document.createTextNode(value.slice(0, idx)));
        el.appendChild(mark);
        el.appendChild(document.createTextNode(value.slice(idx + term.length)));
    }


    // ==========================================================
    // REUSABLE AUTOCOMPLETE
    // - Click or press Enter to select
    // - Arrow Up / Down to move through suggestions
    // - Escape or clicking outside closes the list
    // - Selecting a suggestion marks the input as verified
    // - Typing again removes the verified state
    // ==========================================================
    function setupAutocomplete({ input, box, url, getParams }) {

        let timer = null;
        let activeIndex = -1;
        let requestId = 0;

        // Small message row inside the dropdown (e.g. "Searching...")
        function showStatus(text) {
            box.innerHTML = '';
            activeIndex = -1;

            const row = document.createElement('div');
            row.className = 'list-group-item text-muted suggestion-status';
            row.textContent = text;

            box.appendChild(row);
            box.style.display = 'block';
        }

        function hideBox() {
            box.innerHTML = '';
            box.style.display = 'none';
            activeIndex = -1;
        }

        function setActive(index) {
            const options = box.querySelectorAll('button');
            options.forEach(function (o) {
                o.classList.remove('suggestion-active');
            });

            if (options.length === 0) return;

            // wrap around
            if (index < 0) index = options.length - 1;
            if (index >= options.length) index = 0;

            activeIndex = index;
            options[index].classList.add('suggestion-active');
            options[index].scrollIntoView({ block: 'nearest' });
        }

        function selectValue(value) {
            input.value = value;
            markVerified(input);
            hideBox();
            onFilterChange();
        }

        input.addEventListener('input', function () {

            // User is typing again, so it's no longer a confirmed selection
            clearVerified(input);

            const search = this.value.trim();

            clearTimeout(timer);
            requestId++;
            input.classList.remove('input-nomatch');

            if (search.length < 2) {
                hideBox();
                return;
            }

            timer = setTimeout(function () {

                const myId = ++requestId;

                showStatus('Searching...');

                $.get(url, Object.assign({ term: search }, getParams()))
                    .done(function (results) {

                        // Ignore responses that arrived after the user kept typing
                        if (myId !== requestId) return;

                        box.innerHTML = '';
                        activeIndex = -1;

                        if (!results || results.length === 0) {
                            showStatus('No matches found');
                            flashNoMatch(input);
                            return;
                        }

                        results.forEach(function (value) {

                            const option = document.createElement('button');
                            option.type = 'button';
                            option.className = 'list-group-item list-group-item-action';
                            option.dataset.value = value;
                            renderHighlighted(option, value, search);

                            option.addEventListener('click', function () {
                                selectValue(value);
                            });

                            box.appendChild(option);
                        });

                        box.style.display = 'block';
                    })
                    .fail(hideBox);

            }, 300);
        });

        input.addEventListener('keydown', function (e) {

            const isOpen = box.style.display === 'block';
            if (!isOpen) return;

            const options = box.querySelectorAll('button');

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                setActive(activeIndex + 1);
            }
            else if (e.key === 'ArrowUp') {
                e.preventDefault();
                setActive(activeIndex - 1);
            }
            else if (e.key === 'Enter') {
                // Select highlighted option, or the first one if none highlighted
                e.preventDefault();
                const target = options[activeIndex >= 0 ? activeIndex : 0];
                if (target) selectValue(target.textContent);
            }
            else if (e.key === 'Escape') {
                hideBox();
            }
        });

        // Close when clicking outside
        document.addEventListener('click', function (e) {
            if (e.target !== input && !box.contains(e.target)) {
                hideBox();
            }
        });
    }

    setupAutocomplete({
        input: item,
        box: itemSuggestions,
        url: routes.itemSuggestions,
        getParams: function () { return {}; }
    });

    setupAutocomplete({
        input: co,
        box: coSuggestions,
        url: routes.coSuggestions,
        getParams: function () { return { item: item.value.trim() }; }
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

        btnDownloadSummary.classList.add('d-none');

        if (!hasFilters()) {
            resetReport();
            return;
        }

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

                Swal.fire({
                    icon: 'info',
                    title: 'No Result Found',
                    text: 'No records match the Item and CO you entered.'
                });

                return;
            }

            summaryTable.innerHTML = html;

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

            btnDownloadSummary.classList.remove('d-none');
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

            // Collapse current row
            if (row.child.isShown()) {

                row.child.hide();
                tr.removeClass('detail-shown');

                tr.find('.row-expand-icon')
                    .removeClass('bi-chevron-up')
                    .addClass('bi-chevron-down');

                return;
            }

            // Close other open rows
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

            const rowData = tr.data();

            const detailParams = {
                item: item.value.trim(),
                co: co.value.trim(),
                warehouse: rowData.warehouse,
                bay: rowData.bay,
                status: rowData.status
            };

            row.child(detailLoadingHtml()).show();

            tr.addClass('detail-shown');

            tr.find('.row-expand-icon')
                .removeClass('bi-chevron-down')
                .addClass('bi-chevron-up');

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

        if (xhr.responseJSON && xhr.responseJSON.message) {
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
    btnDownloadSummary.addEventListener('click', function () {

        const params = new URLSearchParams({
            item: item.value.trim(),
            co: co.value.trim()
        });

        window.open(
            `${routes.pdfSummary}?${params.toString()}`,
            '_blank'
        );
    });


    // ==========================================================
    // DOWNLOAD DETAILED PDF
    // ==========================================================
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

        destroyDataTable('#item-inquiry-summary-table');

        summaryTable.innerHTML = `
            <tr>
                <td colspan="8" class="text-center text-muted py-4">
                    Enter Item and CO to view the summary.
                </td>
            </tr>
        `;

        btnDownloadSummary.classList.add('d-none');
    }


    // ==========================================================
    // RESET BUTTON
    // ==========================================================
    btnReset.addEventListener('click', function () {

        clearTimeout(debounceTimer);

        item.value = '';
        co.value = '';

        clearVerified(item);
        clearVerified(co);

        itemSuggestions.style.display = 'none';
        coSuggestions.style.display = 'none';

        resetReport();

        item.focus();
    });

});