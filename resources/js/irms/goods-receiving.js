$(document).ready(function () {
    // Receiving Form/Details toggle logic
    $("#receiving-details").hide();
    $("#goodsReceivingForm").on("submit", function (e) {
        e.preventDefault();

        // Pass all form data to the summary in receiving-details-form
        $("#details-site").text($("#rssite option:selected").text() || $("#rssite").val() || '-');
        $("#details-date").text($("#date").val() || '-');
        $("#details-warehouse").text($("#rswhse option:selected").text() || $("#rswhse").val() || '-');
        $("#details-jobco").text($("#jobcoreceive").val() || '-');
        $("#details-lot").text($("#lot").val() || '-');
        $("#details-item").text($("#item").val() || '-');
        $("#details-description").text($("#desc").val() || '-');
        $("#details-pallet_size").text($("#pallet_size").val() || '-');
        $("#details-um").text($("#um").val() || '-');
        $("#details-bay").text($("#rsbaynum option:selected").text() || $("#rsbaynum").val() || '-');
        $("#details-docno").text($("#docno").val() || '-');

        // Get form values
        const rssite = $("#rssite").val() || $("input[name='rssite']").val();
        const rswhse = $("#rswhse").val();
        const rsbaynum = $("#rsbaynum").val();
        const dateReceived = $("#date").val();
        const um = $("#um").val();

        // Format pallet size for summary/details: show "0" if integer 0, else show decimal only if needed
        let palletSize = $("#pallet_size").val();
        let palletSizeNum = parseFloat(palletSize);
        if (isNaN(palletSizeNum) || palletSizeNum === 0) {
            palletSize = "0";
        } else if (palletSizeNum % 1 === 0) {
            palletSize = palletSizeNum.toString();
        } else {
            palletSize = palletSizeNum.toFixed(2).replace(/\.00$/, "");
        }
        $("#details-pallet_size").text(palletSize);

        // AJAX to get rsloc list
        $.ajax({
            url: window.appUrl + '/irms/receiving/rsloc-list',
            method: 'POST',
            data: {
                rssite: rssite,
                rswhse: rswhse,
                rsbaynum: rsbaynum,
                item: $("#item").val(),
                pallet_size: $("#pallet_size").val(),
                _token: $('input[name="_token"]').val()
            },
            success: function (data) {
                const tbody = $("#receivingTable tbody");
                tbody.empty();
                if (data.length > 0) {
                    data.forEach(function(row, idx) {
                        // Format qty_onHand: show "0" if integer 0, else show decimal only if needed
                        let qtyOnHandNum = parseFloat(row.qty_onHand);
                        let qtyOnHand;
                        if (isNaN(qtyOnHandNum) || qtyOnHandNum === 0) {
                            qtyOnHand = "0";
                        } else if (qtyOnHandNum % 1 === 0) {
                            qtyOnHand = qtyOnHandNum.toString();
                        } else {
                            qtyOnHand = qtyOnHandNum.toFixed(2).replace(/\.00$/, "");
                        }
                        tbody.append(`
                            <tr>
                                <td>${idx + 1}</td>
                                <td class="text-center align-middle"><input type="checkbox" name="select_row[]" value="${idx + 1}" class="big-checkbox"></td>
                                <td>${row.rsloc}</td>
                                <td><input type="text" class="form-control" value="" disabled></td>
                                <td><input type="text" class="form-control text-end" value="" disabled></td>
                                <td class="text-end">${qtyOnHand}</td>
                                <td>${um}</td>
                                <td></td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append('<tr><td colspan="8" class="text-center">No Rack Location found.</td></tr>');
                }
            }
        });

        // Hide form, show details
        $(".card:has(#goodsReceivingForm)").hide();
        $("#receiving-details").fadeIn();
    });

    $("#btnBackReceiving").on("click", function () {
        $("#receiving-details").hide();
        $(".card:has(#goodsReceivingForm)").fadeIn();
    });

    // Enable/disable row inputs based on checkbox
    $(document).on('change', '#receivingTable input[type="checkbox"].big-checkbox', function () {
        const $row = $(this).closest('tr');
        const enabled = $(this).is(':checked');
        $row.find('input[type="text"]').prop('disabled', !enabled);

        // Get Pallet Size and Date Received from summary/details
        let palletSize = $("#details-pallet_size").text() || $("#pallet_size").val();
        let dateReceived = $("#details-date").text() || $("#date").val();

        // If checked, set Qty to Receive and Date Received
        if (enabled) {
            $row.find('input[type="text"]').eq(1).val(palletSize); // Qty to Receive
            $row.find('td').eq(7).text(dateReceived); // Date Received cell
        } else {
            $row.find('input[type="text"]').eq(1).val('');
            $row.find('td').eq(7).text(''); // Clear Date Received
        }
    });

    // Select All / Unselect All logic
    $("#btnSelectAll").on("click", function () {
        const checkboxes = $("#receivingTable input[type='checkbox'].big-checkbox");
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

    // When populating table rows, make sure inputs are disabled by default
    // Example row (inside your AJAX success):
    // <td><input type="text" class="form-control" value="" disabled></td>
    // <td><input type="text" class="form-control text-end" value="" disabled></td>
    $("#btnReceive").on("click", function () {
        const rows = [];
        $("#receivingTable tbody tr").each(function () {
            const $row = $(this);
            const checked = $row.find('input[type="checkbox"].big-checkbox').is(':checked');
            if (checked) {
                rows.push({
                    rssite: $("#rssite").val() || $("input[name='rssite']").val(),
                    rswhse: $("#rswhse").val(),
                    rsbaynum: $("#rsbaynum").val(),
                    rsloc: $row.find('td').eq(2).text(),
                    rslot: $("#lot").val(), 
                    rspallet_num: $row.find('input[type="text"]').eq(0).val(),
                    job: $("#jobcoreceive").val(),
                    item: $("#item").val(),
                    desc: $("#desc").val(),
                    um: $("#um").val(),
                    qty: $row.find('input[type="text"]').eq(1).val(),
                    datercvd: $("#date").val(),
                    docnum: $("#docno").val()
                });
            }
        });

        if (rows.length === 0) {
            alert("Please select at least one row to receive.");
            return;
        }

        $.ajax({
            url: window.appUrl + '/irms/receiving/process-goods-received',
            method: 'POST',
            data: {
                rows: rows,
                _token: $('input[name="_token"]').val()
            },
            success: function (response) {
                // Show modal
                $("body").append(`
                    <div class="modal fade" id="goodsReceivedModal" tabindex="-1" aria-labelledby="goodsReceivedModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="goodsReceivedModalLabel">Success</h5>
                          </div>
                          <div class="modal-body text-center">
                            <i class="bi bi-check-circle-fill text-success" style="font-size:2rem;"></i>
                            <p class="mt-3 mb-0">Goods received successfully!</p>
                          </div>
                          <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-success px-4" id="modalRedirectBtn">OK</button>
                          </div>
                        </div>
                      </div>
                    </div>
                `);
                $("#goodsReceivedModal").modal("show");
                $("#modalRedirectBtn").on("click", function () {
                    $("#goodsReceivedModal").modal("hide");
                    window.location.href = window.appUrl + "/irms/receiving";
                });
                // Remove modal from DOM after hidden
                $("#goodsReceivedModal").on("hidden.bs.modal", function () {
                    $(this).remove();
                });
            }
        });
    });
});