import Swal from "sweetalert2";

document.addEventListener("DOMContentLoaded", function () {
    let isSubmittingDispatch = false; // add this

    // Dispatching Form/Details toggle logic
    $("#dispatching-details").hide();
    $("#goodsDispatchingForm .btn-danger").on("click", function (e) {
        e.preventDefault();

        // Show loading state
        const $button = $(this);
        const $status = $("#formStatus");
        const originalText = $button.html();

        $button
            .prop("disabled", true)
            .html('<i class="bi bi-hourglass-split me-2"></i>Processing...');
        $status.show();

        const form = $("#goodsDispatchingForm");
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
                    .removeClass("btn-danger")
                    .addClass("btn-success")
                    .html('<i class="bi bi-check-circle me-2"></i>Success!');

                setTimeout(() => {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title:
                            response.message ||
                            "Goods dispatching processed successfully!",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });

                    $(".card:has(#goodsDispatchingForm)").hide();
                    $("#dispatching-details").fadeIn();

                    // Reset button state
                    $button
                        .prop("disabled", false)
                        .removeClass("btn-success")
                        .addClass("btn-danger")
                        .html(originalText);
                    $status.hide();
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

        // Fetch qtyOnHand before showing details
        $.ajax({
            url: window.appUrl + "/irms/dispatching/job-item-details",
            method: "POST",
            data: {
                rssite: $("#rssite").val() || $("input[name='rssite']").val(),
                jobco: $("#jobcodispatch").val(),
                _token: $('input[name="_token"]').val(),
            },
            success: function (data) {
                // Set hidden input for qtyOnHand
                $("#qtyOnHand").val(data.qtyOnHand || "0");

                // Format qtyOnHand with commas and decimals only if needed
                let qtyOnHand = parseFloat(data.qtyOnHand || "0");
                let qtyOnHandDisplay =
                    isNaN(qtyOnHand) || qtyOnHand === 0
                        ? "0"
                        : qtyOnHand % 1 === 0
                        ? qtyOnHand.toLocaleString()
                        : qtyOnHand
                              .toLocaleString(undefined, {
                                  minimumFractionDigits: 2,
                                  maximumFractionDigits: 2,
                              })
                              .replace(/\.00$/, "");

                $("#details-qty-on-hand").text(qtyOnHandDisplay);

                // Update the rest of the details
                $("#details-site").text(
                    $("#rssite option:selected").text() ||
                        $("#rssite").val() ||
                        "-"
                );
                $("#details-date").text(formatDateMDY($("#date").val()));
                $("#details-warehouse").text($("#rswhse").val() || "");
                $("#details-jobco").text($("#jobcodispatch").val() || "");
                $("#details-lot").text($("#lot").val() || "");
                $("#details-item").text($("#item").val() || "");
                $("#details-desc").text($("#desc").val() || "");
                $("#details-desc").attr("title", $("#desc").val() || "");
                $("#details-um").text($("#um").val() || "");
                $("#details-docno").text($("#docno").val() || "-");

                // AJAX to get item in rsloc list
                $.ajax({
                    url: window.appUrl + "/irms/dispatching/item-in-rsloc-list",
                    method: "POST",
                    data: {
                        rssite:
                            $("#rssite").val() ||
                            $("input[name='rssite']").val(),
                        job: $("#jobcodispatch").val(),
                        _token: $('input[name="_token"]').val(),
                    },
                    success: function (data) {
                        const tbody = $("#dispatchingTable tbody");
                        tbody.empty();
                        if (data.length > 0) {
                            const um = $("#um").val();
                            data.forEach(function (row, idx) {
                                let qtyNum = parseFloat(row.qty);
                                let qty =
                                    isNaN(qtyNum) || qtyNum === 0
                                        ? "0"
                                        : qtyNum % 1 === 0
                                        ? qtyNum.toString()
                                        : qtyNum
                                              .toFixed(2)
                                              .replace(/\.00$/, "");
                                tbody.append(`
                                <tr>
                                    <td class="text-center">${idx + 1}</td>
                                    <td class="text-center align-middle">
                                        <input type="checkbox" name="select_row[]" value="${
                                            idx + 1
                                        }" class="big-checkbox">
                                    </td>
                                    <td><strong>${row.rsloc}</strong></td>
                                    <td>${row.rspallet_num || ""}</td>
                                    <td class="text-end"><span class="badge bg-light text-dark">${qty}</span></td>
                                    <td class="position-relative">
                                        <input type="text" class="form-control text-end" value="" disabled>
                                    </td>
                                    <td class="text-center"><span class="badge bg-warning text-dark">${um}</span></td>
                                    <td class="text-center text-muted">-</td>
                                </tr>
                            `);
                            });
                        } else {
                            tbody.append(
                                '<tr><td colspan="8" class="text-center text-muted"><i class="bi bi-inbox me-2"></i>No Rack Location found.</td></tr>'
                            );
                        }
                        updateCounters();
                    },
                });
            },
        });
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
            const qtyAvailable = $row
                .find("td")
                .eq(4)
                .find("span")
                .text()
                .trim();
            const $qtyInput = $row.find('input[type="text"].text-end');
            const selectedDate = $("#date").val();
            const formattedDate = formatDateMDY(selectedDate);

            $qtyInput.prop("disabled", !enabled);

            if (enabled) {
                $qtyInput.val(qtyAvailable); // Show available qty
                $row.find("td").eq(7).text(formattedDate); // Show selected date in Date Dispatched column
                $row.addClass("table-warning");
            } else {
                $qtyInput.val(""); // Clear input
                $qtyInput.removeClass("is-invalid");
                $row.find("td").eq(7).text("-"); // Clear date when unchecked
                $row.removeClass("table-warning");
            }

            updateCounters();
        }
    );

    // Select All / Unselect All logic for dispatchingTable
    $("#btnD_SelectAll").on("click", function () {
        const checkboxes = $(
            "#dispatchingTable input[type='checkbox'].big-checkbox"
        );
        const allChecked =
            checkboxes.length > 0 &&
            checkboxes.filter(":checked").length === checkboxes.length;

        if (allChecked) {
            checkboxes.prop("checked", false).trigger("change");
            $(this).find("span").text("Select All");
            $(this)
                .find("i")
                .removeClass("bi-x-circle-fill")
                .addClass("bi-check-circle-fill");
        } else {
            checkboxes.prop("checked", true).trigger("change");
            $(this).find("span").text("Unselect All");
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
            const $icon = $input.siblings(".invalid-feedback-icon");
            let val = $input.val();

            // Remove leading zeros unless the value is "0" or "0." (for decimals)
            if (/^0\d+/.test(val)) {
                val = val.replace(/^0+/, "");
                $input.val(val);
            }

            const $row = $input.closest("tr");
            const availableQtyText = $row
                .find("td")
                .eq(4)
                .find("span")
                .text()
                .trim();
            const availableQty = parseFloat(availableQtyText);
            const qty = parseFloat(val);

            // Only allow positive numbers, no letters/symbols
            if (
                !/^\d*\.?\d*$/.test(val) ||
                isNaN(qty) ||
                qty <= 0 ||
                qty > availableQty
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

    // btnDispatch click: show modal if any qty > available qty
    $("#btnDispatch").on("click", function (e) {
        if (isSubmittingDispatch) return false;

        let hasError = false;
        let checkedCount = 0;
        let errorMsg = "";

        $("#dispatchingTable tbody tr").each(function () {
            const $row = $(this);
            const checked = $row
                .find('input[type="checkbox"].big-checkbox')
                .is(":checked");
            if (checked) {
                checkedCount++;
                const qtyInput = $row.find('input[type="text"].text-end');
                const val = qtyInput.val();
                const qty = parseFloat(val);
                const availableQtyText = $row
                    .find("td")
                    .eq(4)
                    .find("span")
                    .text()
                    .trim();
                const availableQty = parseFloat(availableQtyText);

                if (!/^\d*\.?\d*$/.test(val)) {
                    qtyInput.addClass("is-invalid");
                    hasError = true;
                    errorMsg = "Letters and symbols are not allowed.";
                } else if (isNaN(qty) || qty <= 0) {
                    qtyInput.addClass("is-invalid");
                    hasError = true;
                    errorMsg =
                        "You must enter a positive number and not greater than the available quantity.";
                } else if (qty > availableQty) {
                    qtyInput.addClass("is-invalid");
                    hasError = true;
                    errorMsg =
                        "Dispatching quantity exceeds the available job quantity.";
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
                text: "Please select at least one item location to dispatch.",
                confirmButtonText: "OK",
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
                const qtyInput = $row.find('input[type="text"].text-end');
                const qtyToDispatch = parseFloat(qtyInput.val());
                const availableQtyText = $row
                    .find("td")
                    .eq(4)
                    .find("span")
                    .text()
                    .trim();
                const availableQty = parseFloat(availableQtyText);
                const rsloc = $row.find("td").eq(2).text();
                const rspallet_num = $row.find("td").eq(3).text();
                const um = $row.find("td").eq(6).find("span").text();

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
            Swal.fire({
                icon: "error",
                title: "Invalid Quantity",
                text: "Please enter a valid quantity to dispatch (must be > 0 and ≤ available quantity) for all selected rows.",
                confirmButtonText: "OK",
            });
            return;
        }

        if (rows.length === 0) {
            Swal.fire({
                icon: "warning",
                title: "No Item Location Selected",
                text: "Please select at least one item location to dispatch.",
                confirmButtonText: "OK",
            });
            return;
        }

        isSubmittingDispatch = true;
        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.prop("disabled", true).html('<i class="bi bi-hourglass-split me-2"></i>Dispatching...');

        $.ajax({
            url: window.appUrl + "/irms/dispatching/process-goods-dispatch",
            method: "POST",
            data: {
                rows: rows,
                _token: $('input[name="_token"]').val(),
            },
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Goods dispatched successfully!",
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                }).then(() => {
                    window.location.href = window.appUrl + "/irms/dispatching";
                });
            },
            error: function (xhr) {
                let msg = "An error occurred.";
                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message &&
                    xhr.responseJSON.message.includes(
                        "Dispatch quantity exceeds available item location quantity"
                    )
                ) {
                    msg = "Dispatch quantity exceeds available job quantity.";
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: "error",
                    title: "Dispatch Failed",
                    text: msg,
                    confirmButtonText: "OK",
                });

                isSubmittingDispatch = false;
                $btn.prop("disabled", false).html(originalHtml);
            },
        });
    });

    // Add refresh locations button functionality
    $("#btnRefreshLocations").on("click", function () {
        const $button = $(this);
        const originalHtml = $button.html();

        $("#btnD_SelectAll").find("i").removeClass("bi-x-circle-fill bi-check-circle-fill").addClass("bi-check-circle");
        $("#btnD_SelectAll").find("span").text("Select All");

        $button
            .prop("disabled", true)
            .html('<i class="bi bi-arrow-clockwise spin me-1"></i>Loading...');

        // Re-trigger the location loading
        const rssite = $("#rssite").val() || $("input[name='rssite']").val();
        const job = $("#jobcodispatch").val();

        if (!rssite || !job) {
            $button.prop("disabled", false).html(originalHtml);
            return;
        }

        // AJAX to get item in rsloc list
        $.ajax({
            url: window.appUrl + "/irms/dispatching/item-in-rsloc-list",
            method: "POST",
            data: {
                rssite: rssite,
                job: job,
                _token: $('input[name="_token"]').val(),
            },
            success: function (data) {
                const tbody = $("#dispatchingTable tbody");
                tbody.empty();
                if (data.length > 0) {
                    const um = $("#um").val();
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
                            <td class="text-center">${idx + 1}</td>
                            <td class="text-center align-middle">
                                <input type="checkbox" name="select_row[]" value="${
                                    idx + 1
                                }" class="big-checkbox">
                            </td>
                            <td><strong>${row.rsloc}</strong></td>
                            <td>${row.rspallet_num || ""}</td>
                            <td class="text-end"><span class="badge bg-light text-dark">${qty}</span></td>
                            <td class="position-relative">
                                <input type="text" class="form-control text-end" value="" disabled>
                            </td>
                            <td class="text-center"><span class="badge bg-warning text-dark">${um}</span></td>
                            <td class="text-center text-muted">-</td>
                        </tr>
                    `);
                    });
                } else {
                    tbody.append(
                        '<tr><td colspan="8" class="text-center text-muted"><i class="bi bi-inbox me-2"></i>No Rack Location found.</td></tr>'
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
            "#dispatchingTable input[type='checkbox'].big-checkbox:checked"
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
        '#dispatchingTable input[type="text"].text-end',
        function () {
            updateCounters();
        }
    );

    // Real-time validation for dispatching form
    $(
        "#goodsDispatchingForm input[required], #goodsDispatchingForm select[required]"
    ).on("input change", function () {
        const $field = $(this);
        if ($field.val()) {
            $field.removeClass("is-invalid").addClass("is-valid");
        } else {
            $field.removeClass("is-valid");
        }
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
