import Swal from "sweetalert2";

// Dispatching Form/Details toggle logic
$("#dispatching-details").hide();
$("#goodsDispatchingForm .btn-danger").on("click", function (e) {
    e.preventDefault();

    const form = $("#goodsDispatchingForm");
    const formData = form.serialize();

    $.ajax({
        url: form.attr("action"),
        method: "POST",
        data: formData,
        success: function (response) {
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: response.message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            $(".card:has(#goodsDispatchingForm)").hide();
            $("#dispatching-details").fadeIn();
        },
        error: function (xhr) {
            let msg = "An error occurred.";
            // Laravel validation error
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors).join("<br>");
            }
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "error",
                title: msg,
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
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

            // After getting data.qtyOnHand from AJAX
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

            // Now update the rest of the details as before
            $("#details-site").text(
                $("#rssite option:selected").text() || $("#rssite").val() || "-"
            );
            $("#details-date").text(formatDateMDY($("#date").val()));
            $("#details-warehouse").text($("#rswhse").val() || "");
            $("#details-jobco").text($("#jobcodispatch").val() || "");
            $("#details-lot").text($("#lot").val() || "");
            $("#details-item").text($("#item").val() || "");
            $("#details-desc").text($("#desc").val() || "");
            $("#details-um").text($("#um").val() || "");
            $("#details-docno").text($("#docno").val() || "");

            // AJAX to get item in rsloc list (as before)
            $.ajax({
                url: window.appUrl + "/irms/dispatching/item-in-rsloc-list",
                method: "POST",
                data: {
                    rssite:
                        $("#rssite").val() || $("input[name='rssite']").val(),
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
            // $(".card:has(#goodsDispatchingForm)").hide();
            // $("#dispatching-details").fadeIn();
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
            const availableQty =
                parseFloat(qtyInput.attr("value")) ||
                parseFloat(qtyInput.val());

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
        /*
            if ($("#noRowCheckedModal").length === 0) {
                $("body").append(`...modal html...`);
            }
            $("#noRowCheckedModal").modal("show");
            $("#noRowCheckedModal").on("hidden.bs.modal", function () {
                $(this).remove();
            });
            return false;
            */
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
            const qtyInput = $row.find('input[type="text"]');
            const qtyToDispatch = parseFloat(qtyInput.val());
            const availableQty =
                parseFloat(qtyInput.attr("value")) ||
                parseFloat(qtyInput.val());
            const rsloc = $row.find("td").eq(2).text();
            const rspallet_num = $row.find("td").eq(3).text();
            const um = $row.find("td").eq(6).text();

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
        },
    });
});

// After user selects Job/CO or on form submit, fetch job item details
$.ajax({
    url: window.appUrl + "/irms/dispatching/job-item-details",
    method: "POST",
    data: {
        rssite: $("#rssite").val() || $("input[name='rssite']").val(),
        jobco: $("#jobcodispatch").val(),
        _token: $('input[name="_token"]').val(),
    },
    success: function (data) {
        // Set hidden input for qtyOnHand (if you use it elsewhere)
        $("#qtyOnHand").val(data.qtyOnHand || "0");

        // After getting data.qtyOnHand from AJAX
        let qtyOnHand = parseFloat(data.qtyOnHand || "0");
        let qtyOnHandDisplay =
            isNaN(qtyOnHand) || qtyOnHand === 0
                ? "0"
                : qtyOnHand % 1 === 0
                ? qtyOnHand.toString()
                : qtyOnHand.toFixed(2).replace(/\.00$/, "");

        // Update the details section
        $("#details-qty-on-hand").text(qtyOnHandDisplay);
    },
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
