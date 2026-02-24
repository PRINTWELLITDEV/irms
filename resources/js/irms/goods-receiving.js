import Swal from "sweetalert2";

document.addEventListener("DOMContentLoaded", function () {
    let isSubmittingReceive = false;

    const upper = function (str) {
        return str ? str.toString().toUpperCase() : "";
    };

    // Receiving Form/Details toggle logic
    $("#receiving-details").hide();
    $("#goodsReceivingForm .btn-primary").on("click", function (e) {
        e.preventDefault();

        // Show loading state
        const $button = $(this);
        const $status = $("#formStatus");
        const originalText = $button.html();

        $button
            .prop("disabled", true)
            .html('<i class="bi bi-hourglass-split me-2"></i>Processing...');
        $status.show();

        const form = $("#goodsReceivingForm");
        const formData = form.serialize();

        // Add form validation
        let isValid = true;
        form.find("input[required], select[required]").each(function () {
            const $field = $(this);
            if (!$field.val()) {
                $field.addClass("is-invalid");
                isValid = false;
            } else {
                $field.removeClass("is-invalid");
            }
        });

        if (!isValid) {
            $button.prop("disabled", false).html(originalText);
            $status.hide();
            Swal.fire({
                icon: "error",
                title: "Validation Error",
                text: "Please fill in all required fields.",
                confirmButtonText: "OK",
            });
            return;
        }

        $.ajax({
            url: form.attr("action"),
            method: "POST",
            data: formData,
            success: function (response) {
                $button
                    .removeClass("btn-primary")
                    .addClass("btn-success")
                    .html('<i class="bi bi-check-circle me-2"></i>Success!');

                setTimeout(() => {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title:
                            response.message ||
                            "Goods receiving processed successfully!",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });

                    $(".card:has(#goodsReceivingForm)").hide();
                    $("#receiving-details").fadeIn();

                    // Reset button state
                    $button
                        .prop("disabled", false)
                        .removeClass("btn-success")
                        .addClass("btn-primary")
                        .html(originalText);
                    $status.hide();

                    // Update description tooltip
                    $("#details-description").attr(
                        "title",
                        $("#details-description").text()
                    );

                    // Update unit display
                    $("#details-um").text($("#um").val() || "-");
                }, 1000);
            },
            error: function (xhr) {
                let msg = "An error occurred.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).join("<br>");
                }

                $button.prop("disabled", false).html(originalText);
                $status.hide();

                Swal.fire({
                    icon: "error",
                    title: "Processing Failed",
                    html: msg,
                    confirmButtonText: "OK",
                });
            },
        });

        // Pass all form data to the summary in receiving-details-form
        $("#details-site").text(
            $("#rssite option:selected").text() || $("#rssite").val() || "-"
        );
        $("#details-date").text(formatDateMDY($("#date").val()));
        $("#details-warehouse").text(
            $("#rswhse option:selected").text() || $("#rswhse").val() || "-"
        );
        $("#details-jobco").text($("#jobcoreceive").val() || "-");
        $("#details-lot").text($("#lot").val() || "-");
        $("#details-item").text($("#item").val() || "-");
        $("#details-description").text($("#desc").val() || "-");
        $("#details-pallet_size").text($("#pallet_size").val() || "-");
        $("#details-um").text($("#um").val() || "-");
        $("#details-bay").text(
            $("#rsbaynum option:selected").text() || $("#rsbaynum").val() || "-"
        );
        $("#details-docno").text($("#docno").val() || "-");

        // Get form values
        const rssite = $("#rssite").val() || $("input[name='rssite']").val();
        const rswhse = $("#rswhse").val();
        const rsbaynum = $("#rsbaynum").val();
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
            url: window.appUrl + "/irms/receiving/rsloc-list",
            method: "POST",
            data: {
                rssite: rssite,
                rswhse: rswhse,
                rsbaynum: rsbaynum,
                item: $("#item").val(),
                pallet_size: $("#pallet_size").val(),
                _token: $('input[name="_token"]').val(),
            },
            success: function (data) {
                const tbody = $("#receivingTable tbody");
                tbody.empty();
                if (data.length > 0) {
                    data.forEach(function (row, idx) {
                        // Format qty_onHand: show "0" if integer 0, else show decimal only if needed
                        let qtyOnHandNum = parseFloat(row.qty_onHand);
                        let qtyOnHand;
                        if (isNaN(qtyOnHandNum) || qtyOnHandNum === 0) {
                            qtyOnHand = "0";
                        } else if (qtyOnHandNum % 1 === 0) {
                            qtyOnHand = qtyOnHandNum.toString();
                        } else {
                            qtyOnHand = qtyOnHandNum
                                .toFixed(2)
                                .replace(/\.00$/, "");
                        }
                        tbody.append(`
                        <tr>
                            <td class="text-center">${idx + 1}</td>
                            <td class="text-center align-middle">
                                <input type="checkbox" name="select_row[]" value="${
                                    idx + 1
                                }" class="big-checkbox">
                            </td>
                            <td><strong>${row.rsloc}</strong></td>
                            <td><input type="text" class="form-control" value="" disabled></td>
                            <td class="position-relative">
                                <input type="text" class="form-control text-end" value="" disabled>
                            </td>
                            <td class="text-end"><span class="badge bg-light text-dark">${qtyOnHand}</span></td>
                            <td class="text-center"><span class="badge bg-primary">${um}</span></td>
                            <td class="text-center text-muted">-</td>
                        </tr>
                    `);
                    });
                } else {
                    tbody.append(
                        '<tr><td colspan="8" class="text-center text-muted"><i class="bi bi-inbox me-2"></i>No Rack Locations found.</td></tr>'
                    );
                }
                updateCounters();
            },
        });
    });

    // Back button to return to form
    $("#btnBackReceiving").on("click", function () {
        $("#receiving-details").hide();
        $(".card:has(#goodsReceivingForm)").fadeIn();
    });

    // Enable/disable row inputs based on checkbox
    $(document).on(
        "change",
        '#receivingTable input[type="checkbox"].big-checkbox',
        function () {
            const $row = $(this).closest("tr");
            const enabled = $(this).is(":checked");
            $row.find('input[type="text"]').prop("disabled", !enabled);

            // Get Pallet Size and Date Received from summary/details
            let palletSize =
                $("#details-pallet_size").text() || $("#pallet_size").val();
            let dateReceived = $("#details-date").text() || $("#date").val();

            // If checked, set Qty to Receive and Date Received
            if (enabled) {
                $row.find('input[type="text"]').eq(1).val(palletSize); // Qty to Receive
                $row.find("td").eq(7).text(formatDateMDY(dateReceived)); // Date Received cell
                $row.addClass("table-success");
            } else {
                $row.find('input[type="text"]').eq(1).val("");
                $row.find("td").eq(7).text("-"); // Clear Date Received
                $row.removeClass("table-success");
            }

            // Update counters
            updateCounters();
        }
    );

    // Select All / Unselect All logic
    $("#btnR_SelectAll").on("click", function () {
        const checkboxes = $("#receivingTable input[type='checkbox'].big-checkbox");
        const allChecked = checkboxes.length > 0 && checkboxes.filter(":checked").length === checkboxes.length;

        if (allChecked) {
            checkboxes.prop("checked", false).trigger("change");
            // Force update after all are unchecked
            $(this).find("i").removeClass("bi-x-circle-fill bi-check-circle-fill").addClass("bi-check-circle");
            $("#btnR_SelectAll").find("span").text("Select All");
        } else {
            checkboxes.prop("checked", true).trigger("change");
            // Force update after all are checked
            $(this).find("i").removeClass("bi-check-circle bi-check-circle-fill").addClass("bi-x-circle-fill");
            $("#btnR_SelectAll").find("span").text("Unselect All");

        }
    });

    // Always update Select All/Unselect All button when checkboxes change
    $(document).on("change", "#receivingTable input[type='checkbox'].big-checkbox", function () {
        const checkboxes = $("#receivingTable input[type='checkbox'].big-checkbox");
        const allChecked = checkboxes.length > 0 && checkboxes.filter(":checked").length === checkboxes.length;

        if (allChecked) {
            $("#btnR_SelectAll").find("i").removeClass("bi-check-circle bi-check-circle-fill").addClass("bi-x-circle-fill");
            $("#btnR_SelectAll").find("span").text("Unselect All");
        } else {
            $("#btnR_SelectAll").find("i").removeClass("bi-x-circle-fill bi-check-circle-fill").addClass("bi-check-circle");
            $("#btnR_SelectAll").find("span").text("Select All");
        }
    });

    // Move cursor to end and show typing indicator for Qty to Receive input
    $(document).on(
        "focus",
        '#receivingTable input[type="text"].text-end',
        function () {
            const input = this;
            setTimeout(() => {
                input.selectionStart = input.selectionEnd = input.value.length;
            }, 0);
            $(input).addClass("typing-indicator");
        }
    );

    // Validate Qty to Receive input (must not exceed pallet size)
    $(document).on(
        "input",
        '#receivingTable input[type="text"].text-end',
        function () {
            const $input = $(this);
            const $icon = $input.siblings(".invalid-feedback-icon");
            let val = $input.val();

            // Remove leading zeros unless the value is "0" or "0." (for decimals)
            if (/^0\d+/.test(val)) {
                val = val.replace(/^0+/, "");
                $input.val(val);
            }

            const palletSize = parseFloat(
                $("#details-pallet_size").text() || $("#pallet_size").val()
            );
            const qty = parseFloat(val);

            // Only allow positive numbers, no letters/symbols
            if (
                !/^\d*\.?\d*$/.test(val) ||
                isNaN(qty) ||
                qty <= 0 ||
                qty > palletSize
            ) {
                $input.addClass("is-invalid");
                $icon.show();
            } else {
                $input.removeClass("is-invalid");
                $icon.hide();
            }

            updateCounters();
        }
    );

    // btnReceive click: validate and process receiving
    $("#btnReceive").on("click", function (e) {
        if (isSubmittingReceive) {
            return;
        }

        const $receiveBtn = $(this);
        let hasError = false;
        let checkedCount = 0;
        let errorMsg = "";

        $("#receivingTable tbody tr").each(function () {
            const $row = $(this);
            const checked = $row
                .find('input[type="checkbox"].big-checkbox')
                .is(":checked");
            if (checked) {
                checkedCount++;
                const qtyInput = $row.find('input[type="text"].text-end');
                const val = qtyInput.val();
                const qty = parseFloat(val);
                const palletSize = parseFloat(
                    $("#details-pallet_size").text() || $("#pallet_size").val()
                );

                if (!/^\d*\.?\d*$/.test(val)) {
                    qtyInput.addClass("is-invalid");
                    hasError = true;
                    errorMsg = "Letters and symbols are not allowed.";
                } else if (isNaN(qty) || qty <= 0) {
                    qtyInput.addClass("is-invalid");
                    hasError = true;
                    errorMsg =
                        "You must enter a positive number and not greater than the pallet size.";
                } else if (qty > palletSize) {
                    qtyInput.addClass("is-invalid");
                    hasError = true;
                    errorMsg =
                        "Receiving quantity exceeds the available pallet size.";
                } else {
                    qtyInput.removeClass("is-invalid");
                }
            }
        });

        if (hasError) {
            e.preventDefault();
            Swal.fire({
                icon: "error",
                title: "Invalid Quantity",
                html: errorMsg,
                confirmButtonText: "OK",
            });
            return false;
        }

        if (checkedCount === 0) {
            e.preventDefault();
            Swal.fire({
                icon: "warning",
                title: "No Item Location Selected",
                text: "Please select at least one item location to receive.",
                confirmButtonText: "OK",
            });
            return false;
        }

        const rows = [];
        $("#receivingTable tbody tr").each(function () {
            const $row = $(this);
            const checked = $row
                .find('input[type="checkbox"].big-checkbox')
                .is(":checked");
            if (checked) {
                const qtyInput = $row.find('input[type="text"].text-end');
                const qtyToReceive = parseFloat(qtyInput.val());
                const palletSize = parseFloat(
                    $("#details-pallet_size").text() || $("#pallet_size").val()
                );

                // Fixed: Get rsloc from the correct column (3rd column, index 2)
                const rsloc = $row.find("td").eq(2).text().trim();

                // Fixed: Get pallet number from the correct input (4th column, index 3)
                const rspallet_num =
                    $row.find("td").eq(3).find('input[type="text"]').val() ||
                    "";

                // Fixed: Get unit of measure from the correct column (7th column, index 6)
                const um = $row.find("td").eq(6).find("span").text().trim();

                // Validate quantity
                if (
                    isNaN(qtyToReceive) ||
                    qtyToReceive <= 0 ||
                    qtyToReceive > palletSize ||
                    !/^\d*\.?\d*$/.test(qtyInput.val())
                ) {
                    qtyInput.addClass("is-invalid");
                    hasError = true;
                    return;
                }
                qtyInput.removeClass("is-invalid");

                // Validate that we have required fields
                if (!rsloc) {
                    console.error("Missing rsloc for row:", $row);
                    hasError = true;
                    return;
                }

                rows.push({
                    rssite: upper($("#rssite").val() || $("input[name='rssite']").val()),
                    rswhse: upper($("#rswhse").val() || $("input[name='rswhse']").val()),
                    rsbaynum: upper($("#rsbaynum").val() || $("input[name='rsbaynum']").val()),
                    rsloc: rsloc,
                    rslot: upper($("#lot").val() || $("input[name='lot']").val()),
                    rspallet_num: rspallet_num,
                    job: upper($("#jobcoreceive").val()),
                    item: $("#item").val(),
                    desc: $("#desc").val(),
                    um: um,
                    qty: qtyToReceive,
                    datercvd: $("#date").val(),
                    docnum: $("#docno").val() || "",
                });
            }
        });

        if (hasError) {
            Swal.fire({
                icon: "error",
                title: "Invalid Data",
                text: "Please check all selected rows have valid data.",
                confirmButtonText: "OK",
            });
            return;
        }

        if (rows.length === 0) {
            Swal.fire({
                icon: "warning",
                title: "No Item Location Selected",
                text: "Please select at least one item location to receive.",
                confirmButtonText: "OK",
            });
            return;
        }

        // Debug: Log the data being sent
        console.log("Sending rows data:", rows);

        isSubmittingReceive = true;
        const originalReceiveHtml = $receiveBtn.html();
        $receiveBtn
            .prop("disabled", true)
            .html('<i class="bi bi-hourglass-split me-2"></i>Processing...');

        $.ajax({
            url: window.appUrl + "/irms/receiving/process-goods-received",
            method: "POST",
            data: {
                rows: rows,
                _token: $('input[name="_token"]').val(),
            },
            success: function (response) {
                const job = $("#jobcoreceive").val();
                Swal.fire({
                    icon: "success",
                    title: "Goods received successfully!",
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                }).then(() => {
                    window.location.href =
                        window.appUrl + "/irms/item-locations/" + job;
                });
            },
            error: function (xhr) {
                console.error("Ajax error:", xhr.responseText);
                let msg = "An error occurred.";
                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message &&
                    xhr.responseJSON.message.includes(
                        "Receive quantity exceeds available job quantity"
                    )
                ) {
                    msg =
                        "Receiving quantity exceeds the available job quantity.";
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: "error",
                    title: "Receiving Failed",
                    text: msg,
                    confirmButtonText: "OK",
                });

                isSubmittingReceive = false;
                $receiveBtn.prop("disabled", false).html(originalReceiveHtml);
            },
        });
    });

    // Add refresh locations button functionality
    $("#btnRefreshLocations").on("click", function () {
        const $button = $(this);
        const originalHtml = $button.html();
        
        $("#btnR_SelectAll").find("i").removeClass("bi-x-circle-fill bi-check-circle-fill").addClass("bi-check-circle");
        $("#btnR_SelectAll").find("span").text("Select All");

        $button
            .prop("disabled", true)
            .html('<i class="bi bi-arrow-clockwise spin me-1"></i>Loading...');

        // Re-trigger the location loading
        const rssite = $("#rssite").val() || $("input[name='rssite']").val();
        const rswhse = $("#rswhse").val();
        const rsbaynum = $("#rsbaynum").val();
        const item = $("#item").val();
        const pallet_size = $("#pallet_size").val();

        if (!rssite || !rswhse || !rsbaynum || !item) {
            $button.prop("disabled", false).html(originalHtml);
            return;
        }

        // AJAX to get rsloc list (same as in the main form submission)
        $.ajax({
            url: window.appUrl + "/irms/receiving/rsloc-list",
            method: "POST",
            data: {
                rssite: rssite,
                rswhse: rswhse,
                rsbaynum: rsbaynum,
                item: item,
                pallet_size: pallet_size,
                _token: $('input[name="_token"]').val(),
            },
            success: function (data) {
                const tbody = $("#receivingTable tbody");
                tbody.empty();
                if (data.length > 0) {
                    const um = $("#um").val();
                    data.forEach(function (row, idx) {
                        // Format qty_onHand
                        let qtyOnHandNum = parseFloat(row.qty_onHand);
                        let qtyOnHand;
                        if (isNaN(qtyOnHandNum) || qtyOnHandNum === 0) {
                            qtyOnHand = "0";
                        } else if (qtyOnHandNum % 1 === 0) {
                            qtyOnHand = qtyOnHandNum.toString();
                        } else {
                            qtyOnHand = qtyOnHandNum
                                .toFixed(2)
                                .replace(/\.00$/, "");
                        }
                        tbody.append(`
                        <tr>
                            <td class="text-center">${idx + 1}</td>
                            <td class="text-center align-middle">
                                <input type="checkbox" name="select_row[]" value="${
                                    idx + 1
                                }" class="big-checkbox">
                            </td>
                            <td><strong>${row.rsloc}</strong></td>
                            <td><input type="text" class="form-control" value="" disabled></td>
                            <td class="position-relative">
                                <input type="text" class="form-control text-end" value="" disabled>
                            </td>
                            <td class="text-end"><span class="badge bg-light text-dark">${qtyOnHand}</span></td>
                            <td class="text-center"><span class="badge bg-primary">${um}</span></td>
                            <td class="text-center text-muted">-</td>
                        </tr>
                    `);
                    });
                } else {
                    tbody.append(
                        '<tr><td colspan="8" class="text-center text-muted"><i class="bi bi-inbox me-2"></i>No Rack Locations found.</td></tr>'
                    );
                }
                updateCounters();
            },
            complete: function () {
                $button.prop("disabled", false).html(originalHtml);
            },
        });
    });

    // Function to update counters
    function updateCounters() {
        const checkedBoxes = $(
            "#receivingTable input[type='checkbox'].big-checkbox:checked"
        );
        const selectedCount = checkedBoxes.length;

        let totalQty = 0;
        checkedBoxes.each(function () {
            const qtyInput = $(this)
                .closest("tr")
                .find('input[type="text"].text-end');
            const qty = parseFloat(qtyInput.val()) || 0;
            totalQty += qty;
        });

        $("#selectedCount").text(selectedCount);
        $("#totalQty").text(totalQty.toFixed(2).replace(/\.00$/, ""));
    }

    // Update counter when quantity changes
    $(document).on(
        "input",
        '#receivingTable input[type="text"].text-end',
        function () {
            updateCounters();
        }
    );

    // Real-time validation for receiving form
    $(
        "#goodsReceivingForm input[required], #goodsReceivingForm select[required]"
    ).on("input change", function () {
        const $field = $(this);
        if ($field.val()) {
            $field.removeClass("is-invalid").addClass("is-valid");
        } else {
            $field.removeClass("is-valid");
        }
    });

    // Enhanced form reset with confirmation
    window.resetGoodsReceivingForm = function () {
        Swal.fire({
            title: "Reset Form?",
            text: "All entered data will be lost. Are you sure?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, reset it!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("goodsReceivingForm").reset();
                // Clear auto-filled fields
                $("#item, #desc, #um, #pallet_size, #lot").val("");
                // Remove validation classes
                $("#goodsReceivingForm .is-invalid").removeClass("is-invalid");
                $("#goodsReceivingForm .is-valid").removeClass("is-valid");

                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "success",
                    title: "Form has been reset",
                    showConfirmButton: false,
                    timer: 2000,
                });
            }
        });
    };

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

    // Add CSS for spinning animation
    const style = document.createElement("style");
    style.textContent = `
    .spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
    document.head.appendChild(style);
});
