$(document).ready(function () {
    // Dispatching Form/Details toggle logic
    $("#dispatching-details").hide();
    $("#goodsDispatchingForm").on("submit", function (e) {
        e.preventDefault();

        // Pass all form data to the summary in dispatching-details-form
        $("#details-site").text(
            $("#rssite option:selected").text() || $("#rssite").val() || "-"
        );
        $("#details-date").text(formatDateMDY($("#date").val()));
        $("#details-warehouse").text($("#rswhse").val() || "-");
        $("#details-jobco").text($("#jobcodispatch").val() || "-");
        $("#details-lot").text($("#lot").val() || "-");
        $("#details-item").text($("#item").val() || "-");
        $("#details-desc").text($("#desc").val() || "-");
        $("#details-um").text($("#um").val() || "-");
        $("#details-docno").text($("#docno").val() || "-");

        // AJAX to get item in rsloc list
        $.ajax({
            url: window.appUrl + "/irms/dispatching/item-in-rsloc-list",
            method: "POST",
            data: {
                rssite: $("#rssite").val() || $("input[name='rssite']").val(),
                job: $("#jobcodispatch").val(),
                _token: $('input[name="_token"]').val(),
            },
            success: function (data) {
                const tbody = $("#dispatchingTable tbody");
                tbody.empty();
                if (data.length > 0) {
                    data.forEach(function (row, idx) {
                        let qtyNum = parseFloat(row.qty);
                        let qty =
                            isNaN(qtyNum) || qtyNum === 0
                                ? "0"
                                : qtyNum % 1 === 0
                                ? qtyNum.toString()
                                : qtyNum.toFixed(2).replace(/\.00$/, "");
                        tbody.append(`
                            <tr>
                                <td>${idx + 1}</td>
                                <td class="text-center align-middle"><input type="checkbox" name="select_row[]" value="${
                                    idx + 1
                                }" class="big-checkbox"></td>
                                <td>${row.rsloc}</td>
                                <td>${row.rspallet_num || ""}</td>
                                <td class="text-end">${qty}</td>
                                <td class="text-end"><input type="text" class="form-control text-end" value="" disabled></td>
                                <td>${row.um || ""}</td>
                                <td></td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append(
                        '<tr><td colspan="8" class="text-center">No Rack Location found.</td></tr>'
                    );
                }
            },
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
    $(document).on(
        "change",
        '#dispatchingTable input[type="checkbox"].big-checkbox',
        function () {
            const $row = $(this).closest("tr");
            const enabled = $(this).is(":checked");
            const qtyAvailable = $row.find("td").eq(4).text().trim();
            const $qtyInput = $row.find('input[type="text"].text-end');
            const selectedDate = $("#date").val();
            const formattedDate = formatDateMDY(selectedDate);

            $qtyInput.prop("disabled", !enabled);

            if (enabled) {
                $qtyInput.val(qtyAvailable); // Show available qty
                $row.find("td").eq(7).text(formattedDate); // Show selected date in Date Dispatched column
            } else {
                $qtyInput.val(""); // Clear input
                $qtyInput.removeClass("is-invalid");
                $row.find("td").eq(7).text(""); // Clear date when unchecked
            }
        }
    );

    // Select All / Unselect All logic for dispatchingTable
    $("#btnSelectAll").on("click", function () {
        const checkboxes = $(
            "#dispatchingTable input[type='checkbox'].big-checkbox"
        );
        const allChecked =
            checkboxes.length > 0 &&
            checkboxes.filter(":checked").length === checkboxes.length;

        if (allChecked) {
            checkboxes.prop("checked", false).trigger("change");
            $(this).find("span").text("Select all");
            $(this)
                .find("i")
                .removeClass("bi-x-circle-fill")
                .addClass("bi-check-circle-fill");
        } else {
            checkboxes.prop("checked", true).trigger("change");
            $(this).find("span").text("Unselect all");
            $(this)
                .find("i")
                .removeClass("bi-check-circle-fill")
                .addClass("bi-x-circle-fill");
        }
    });

    // Move cursor to end and show typing indicator for Qty to Dispatch input
    $(document).on(
        "focus",
        '#dispatchingTable input[type="text"].text-end',
        function () {
            const input = this;
            setTimeout(() => {
                input.selectionStart = input.selectionEnd = input.value.length;
            }, 0);
            $(input).addClass("typing-indicator");
        }
    );

    // Validate Qty to Dispatch input (must not exceed available qty)
    $(document).on(
        "input",
        '#dispatchingTable input[type="text"].text-end',
        function () {
            const $input = $(this);
            let val = $input.val();

            // Remove leading zeros unless the value is "0" or "0." (for decimals)
            if (/^0\d+/.test(val)) {
                val = val.replace(/^0+/, "");
                $input.val(val);
            }

            const availableQty =
                parseFloat($input.attr("value")) || parseFloat($input.val());
            const qty = parseFloat(val);

            // Only allow positive numbers, no letters/symbols
            if (
                !/^\d*\.?\d*$/.test(val) ||
                isNaN(qty) ||
                qty <= 0 ||
                qty > availableQty
            ) {
                $input.addClass("is-invalid");
            } else {
                $input.removeClass("is-invalid");
            }
        }
    );

    // btnDispatch click: show modal if any qty > available qty
    $("#btnDispatch").on("click", function (e) {
        let hasError = false;
        let checkedCount = 0;
        $("#dispatchingTable tbody tr").each(function () {
            const $row = $(this);
            const checked = $row.find('input[type="checkbox"].big-checkbox').is(":checked");
            if (checked) {
                checkedCount++;
                const qtyInput = $row.find('input[type="text"].text-end');
                const val = qtyInput.val();
                const qty = parseFloat(val);
                const availableQty = parseFloat(qtyInput.attr("value")) || parseFloat(qtyInput.val());
                if (!/^\d*\.?\d*$/.test(val) || isNaN(qty) || qty <= 0 || qty > availableQty) {
                    qtyInput.addClass("is-invalid");
                    hasError = true;
                }
            }
        });
        if (hasError) {
            e.preventDefault();
            if ($("#dispatchQtyErrorModal").length === 0) {
                $("body").append(`
                    <div class="modal fade" id="dispatchQtyErrorModal" tabindex="-1" aria-labelledby="dispatchQtyErrorModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="dispatchQtyErrorModalLabel">Invalid Quantity</h5>
                          </div>
                          <div class="modal-body text-center">
                            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:2rem;"></i>
                            <p class="mt-3 mb-0">You must enter a positive number not greater than the available quantity. Letters and symbols are not allowed.</p>
                          </div>
                          <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">OK</button>
                          </div>
                        </div>
                      </div>
                    </div>
                `);
            }
            $("#dispatchQtyErrorModal").modal("show");
            $("#dispatchQtyErrorModal").on("hidden.bs.modal", function () {
                $(this).remove();
            });
            return false;
        }

        if (checkedCount === 0) {
            e.preventDefault();
            if ($("#noRowCheckedModal").length === 0) {
                $("body").append(`
                    <div class="modal fade" id="noRowCheckedModal" tabindex="-1" aria-labelledby="noRowCheckedModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title" id="noRowCheckedModalLabel">No Item Location Selected</h5>
                          </div>
                          <div class="modal-body text-center">
                            <i class="bi bi-exclamation-circle-fill text-warning" style="font-size:2rem;"></i>
                            <p class="mt-3 mb-0">Please select at least one item location to dispatch.</p>
                          </div>
                          <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-warning px-4" data-bs-dismiss="modal">OK</button>
                          </div>
                        </div>
                      </div>
                    </div>
                `);
            }
            $("#noRowCheckedModal").modal("show");
            $("#noRowCheckedModal").on("hidden.bs.modal", function () {
                $(this).remove();
            });
            return false;
        }

        const rows = [];
        $("#dispatchingTable tbody tr").each(function () {
            const $row = $(this);
            const checked = $row
                .find('input[type="checkbox"].big-checkbox')
                .is(":checked");
            if (checked) {
                const qtyInput = $row.find('input[type="text"]');
                const qtyToDispatch = parseFloat(qtyInput.val());
                const availableQty =
                    parseFloat(qtyInput.attr("value")) ||
                    parseFloat(qtyInput.val());
                const rsloc = $row.find("td").eq(2).text();
                const rspallet_num = $row.find("td").eq(3).text();
                const um = $row.find("td").eq(5).text();

                // Validate quantity
                if (
                    isNaN(qtyToDispatch) ||
                    qtyToDispatch <= 0 ||
                    qtyToDispatch > availableQty ||
                    !/^\d*\.?\d*$/.test(qtyInput.val())
                ) {
                    qtyInput.addClass("is-invalid");
                    hasError = true;
                    return;
                }
                qtyInput.removeClass("is-invalid");

                rows.push({
                    rssite:
                        $("#rssite").val() || $("input[name='rssite']").val(),
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
                    docno: $("#docno").val(),
                });
            }
        });

        if (hasError) {
            alert(
                "Please enter a valid quantity to dispatch (must be > 0 and ≤ available quantity) for all selected rows."
            );
            return;
        }

        if (rows.length === 0) {
            alert("Please select at least one row to dispatch.");
            return;
        }

        $.ajax({
            url: window.appUrl + "/irms/dispatching/process-goods-dispatch",
            method: "POST",
            data: {
                rows: rows,
                _token: $('input[name="_token"]').val(),
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
            },
        });
    });

    function formatDateMDY(dateStr) {
        if (!dateStr) return "-";
        const date = new Date(dateStr);
        if (isNaN(date)) return dateStr;
        const months = [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
        ];
        return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
    }
});
