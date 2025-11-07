import { has } from "lodash";
import Swal from "sweetalert2";

// Receiving Form/Details toggle logic
$("#receiving-details").hide();
$("#goodsReceivingForm .btn-primary").on("click", function (e) {
    e.preventDefault();

    const form = $("#goodsReceivingForm");
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
                title: response.message || "Goods receiving processed successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            })
            $(".card:has(#goodsReceivingForm)").hide();
            $("#receiving-details").fadeIn();
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
                timerProgressBar: true
            });
        }
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
                                <td>${idx + 1}</td>
                                <td class="text-center align-middle"><input type="checkbox" name="select_row[]" value="${
                                    idx + 1
                                }" class="big-checkbox"></td>
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
                tbody.append(
                    '<tr><td colspan="8" class="text-center">No Rack Location found.</td></tr>'
                );
            }
        },
    });
});

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
        } else {
            $row.find('input[type="text"]').eq(1).val("");
            $row.find("td").eq(7).text(""); // Clear Date Received
        }
    }
);

// Select All / Unselect All logic
$("#btnSelectAll").on("click", function () {
    const checkboxes = $("#receivingTable input[type='checkbox'].big-checkbox");
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

$("#btnReceive").on("click", function (e) {
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
                    "Receiving quantity exceeds the available job quantity.";
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
            rows.push({
                rssite: $("#rssite").val() || $("input[name='rssite']").val(),
                rswhse: $("#rswhse").val(),
                rsbaynum: $("#rsbaynum").val(),
                rsloc: $row.find("td").eq(2).text(),
                rslot: $("#lot").val(),
                rspallet_num: $row.find('input[type="text"]').eq(0).val(),
                job: $("#jobcoreceive").val(),
                item: $("#item").val(),
                desc: $("#desc").val(),
                um: $("#um").val(),
                qty: $row.find('input[type="text"]').eq(1).val(),
                datercvd: $("#date").val(),
                docnum: $("#docno").val(),
            });
        }
    });

    if (rows.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "No Item Location Selected",
            text: "Please select at least one item location to receive.",
            confirmButtonText: "OK",
        });
        return;
    }

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
            let msg = "An error occurred.";
            if (
                xhr.responseJSON &&
                xhr.responseJSON.message &&
                xhr.responseJSON.message.includes(
                    "Receive quantity exceeds available job quantity"
                )
            ) {
                msg = "Receiving quantity exceeds the available job quantity.";
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            Swal.fire({
                icon: "error",
                title: "Receiving Failed",
                text: msg,
                confirmButtonText: "OK",
            });
        },
    });
});

// Typing indicator and validation for Qty input
$(document).on(
    "focus",
    '#receivingTable input[type="text"].text-end',
    function () {
        // Move cursor to end
        const input = this;
        setTimeout(() => {
            input.selectionStart = input.selectionEnd = input.value.length;
        }, 0);

        // Show typing indicator (optional, you can style this as needed)
        $(input).addClass("typing-indicator");
    }
);

$(document).on(
    "input",
    '#receivingTable input[type="text"].text-end',
    function () {
        const $input = $(this);
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
        } else {
            $input.removeClass("is-invalid");
        }
    }
);

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
