$(document).ready(function () {
    // Dispatching Form/Details toggle logic
    $("#dispatching-details").hide();
    $("#goodsDispatchingForm").on("submit", function (e) {
        e.preventDefault();

        // Pass all form data to the summary in dispatching-details-form
        $("#details-site").text($("#rssite option:selected").text() || $("#rssite").val() || '-');
        $("#details-date").text($("#date").val() || '-');
        $("#details-warehouse").text($("#rswhse").val() || '-');
        $("#details-jobco").text($("#jobcodispatch").val() || '-');
        $("#details-lot").text($("#lot").val() || '-');
        $("#details-item").text($("#item").val() || '-');
        $("#details-desc").text($("#desc").val() || '-');
        $("#details-um").text($("#um").val() || '-');
        $("#details-docno").text($("#docno").val() || '-');

        // AJAX to get item in rsloc list
        $.ajax({
            url: window.appUrl + '/irms/dispatching/item-in-rsloc-list',
            method: 'POST',
            data: {
                rssite: $("#rssite").val() || $("input[name='rssite']").val(),
                job: $("#jobcodispatch").val(),
                _token: $('input[name="_token"]').val()
            },
            success: function (data) {
                const tbody = $("#dispatchingTable tbody");
                tbody.empty();
                if (data.length > 0) {
                    data.forEach(function(row, idx) {
                        let qtyNum = parseFloat(row.qty);
                        let qty = isNaN(qtyNum) || qtyNum === 0 ? "0" : (qtyNum % 1 === 0 ? qtyNum.toString() : qtyNum.toFixed(2).replace(/\.00$/, ""));
                        tbody.append(`
                            <tr>
                                <td>${idx + 1}</td>
                                <td class="text-center align-middle"><input type="checkbox" name="select_row[]" value="${idx + 1}" class="big-checkbox"></td>
                                <td>${row.rsloc}</td>
                                <td>${row.rspallet_num || ""}</td>
                                <td class="text-end"><input type="text" class="form-control text-end" value="${qty}" disabled></td>
                                <td>${row.um || ""}</td>
                                <td>${row.datercvd || ""}</td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append('<tr><td colspan="8" class="text-center">No Rack Location found.</td></tr>');
                }
            }
        });

        // Hide form, show details
        $(".card:has(#goodsDispatchingForm)").hide();
        $("#dispatching-details").fadeIn();
    });

    // Back button to return to form
    $("#btnBackDispatching").on("click", function () {
        $("#dispatching-details").hide();
        $(".card:has(#goodsDispatchingForm)").fadeIn();
    });

    // Enable/disable Qty to Dispatch input based on checkbox
    $(document).on('change', '#dispatchingTable input[type="checkbox"].big-checkbox', function () {
        const $row = $(this).closest('tr');
        const enabled = $(this).is(':checked');
        $row.find('input[type="text"]').prop('disabled', !enabled);
    });

    // Select All / Unselect All logic for dispatchingTable
    $("#btnSelectAll").on("click", function () {
        const checkboxes = $("#dispatchingTable input[type='checkbox'].big-checkbox");
        const allChecked = checkboxes.length > 0 && checkboxes.filter(":checked").length === checkboxes.length;

        if (allChecked) {
            checkboxes.prop('checked', false).trigger('change');
            $(this).find('span').text('Select all');
            $(this).find('i').removeClass('bi-x-circle-fill').addClass('bi-check-circle-fill');
        } else {
            checkboxes.prop('checked', true).trigger('change');
            $(this).find('span').text('Unselect all');
            $(this).find('i').removeClass('bi-check-circle-fill').addClass('bi-x-circle-fill');
        }
    });

    $("#btnDispatch").on("click", function () {
        const rows = [];
        let hasError = false;

        $("#dispatchingTable tbody tr").each(function () {
            const $row = $(this);
            const checked = $row.find('input[type="checkbox"].big-checkbox').is(':checked');
            if (checked) {
                const qtyInput = $row.find('input[type="text"]');
                const qtyToDispatch = parseFloat(qtyInput.val());
                const availableQty = parseFloat(qtyInput.attr('value')) || parseFloat(qtyInput.val());
                const rsloc = $row.find('td').eq(2).text();
                const rspallet_num = $row.find('td').eq(3).text();
                const um = $row.find('td').eq(5).text();

                // Validate quantity
                if (isNaN(qtyToDispatch) || qtyToDispatch <= 0) {
                    qtyInput.addClass('is-invalid');
                    hasError = true;
                    return;
                }
                if (qtyToDispatch > availableQty) {
                    qtyInput.addClass('is-invalid');
                    hasError = true;
                    return;
                }
                qtyInput.removeClass('is-invalid');

                rows.push({
                    rssite: $("#rssite").val() || $("input[name='rssite']").val(),
                    rswhse: $("#rswhse").val(),
                    rsloc: rsloc,
                    rslot: $("#lot").val(),
                    rspallet_num: rspallet_num,
                    job: $("#jobcodispatch").val(),
                    item: $("#item").val(),
                    desc: $("#desc").val(),
                    um: um,
                    qty: qtyToDispatch,
                    datedispatch: $("#date").val(),
                    docno: $("#docno").val()
                });
            }
        });

        if (hasError) {
            alert("Please enter a valid quantity to dispatch (must be > 0 and ≤ available quantity) for all selected rows.");
            return;
        }

        if (rows.length === 0) {
            alert("Please select at least one row to dispatch.");
            return;
        }

        $.ajax({
            url: window.appUrl + '/irms/dispatching/process-goods-dispatch',
            method: 'POST',
            data: {
                rows: rows,
                _token: $('input[name="_token"]').val()
            },
            success: function (response) {
                $("body").append(`
                    <div class="modal fade" id="goodsDispatchedModal" tabindex="-1" aria-labelledby="goodsDispatchedModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="goodsDispatchedModalLabel">Success</h5>
                        </div>
                        <div class="modal-body text-center">
                            <i class="bi bi-check-circle-fill text-success" style="font-size:2rem;"></i>
                            <p class="mt-3 mb-0">Goods dispatched successfully!</p>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-success px-4" id="modalDispatchRedirectBtn">OK</button>
                        </div>
                        </div>
                    </div>
                    </div>
                `);
                $("#goodsDispatchedModal").modal("show");
                $("#modalDispatchRedirectBtn").on("click", function () {
                    $("#goodsDispatchedModal").modal("hide");
                    window.location.href = window.appUrl + "/irms/dispatching";
                });
                $("#goodsDispatchedModal").on("hidden.bs.modal", function () {
                    $(this).remove();
                });
            }
        });
    });
});