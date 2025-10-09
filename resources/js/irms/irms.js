import "bootstrap";

import "admin-lte";

import "./datatables.js";
import "./goods-receiving.js";
import "./goods-dispatching.js";
import "./charts.js";

// window.appUrl = "{{ url('') }}";
// window.sessionCheckUrl = "{{ url('/irms/session') }}";
// window.loginUrl = "{{ route('login') }}";

// In your Blade file or main JS file that runs after the table is loaded

// Enhanced client-side listener for real-time updates
window.Echo.channel("active-users").listen("UserStatusUpdated", (e) => {
    const row = document.querySelector(`#user-row-${e.userId}`);

    if (e.status === "offline" && row) {
        // 1. Instant removal for logout events
        row.remove();
        console.log(`User ${e.userId} logged out and row removed.`);
    } else if (
        e.status === "online" &&
        typeof updateActiveUsersTable === "function"
    ) {
        // 2. Refresh the whole table for login events (if polling function exists)
        // This is simpler than creating a new row manually.
        updateActiveUsersTable();
        console.log(
            `User ${e.userId} logged in. Triggering full table refresh.`
        );
    }
});

setInterval(function () {
    const currentPath = window.location.pathname;
    if (currentPath.indexOf("/irms") !== -1) {
        fetch(window.sessionCheckUrl)
            .then((response) => response.json())
            .then((data) => {
                if (!data.valid) {
                    window.location.href = window.loginUrl;
                }
            });
    }
}, 5000);

$(document).ready(function () {
    const isSa =
        $("select#rssite").length > 0 && $("input[name='rssite']").length === 0;

    function setFieldsEnabled(enabled) {
        $(
            "#date, #rswhse, #jobcoreceive, #jobcodispatch, #lot, #item, #pallet_size, #um, #rsbaynum, #docno"
        ).prop("disabled", !enabled);
    }

    if (isSa) {
        setFieldsEnabled(false);
        $("#rssite").on("change", function () {
            setFieldsEnabled(true);
        });
    } else {
        setFieldsEnabled(true);
    }

    // Row click to show modal (if you want a view modal for item locations)
    $("#itemloc-table tbody").on("click", "tr", function () {
        const $row = $(this);
        // Example: fill modal fields
        $("#view-itemloc-job").text($row.data("job") || "");
        $("#view-itemloc-desc").text($row.data("desc") || "");
        $("#view-itemloc-qty").text($row.data("qty") || "0");
        $("#view-itemloc-um").text($row.data("um") || "");
        $("#view-itemloc-site-desc").text($row.data("rssite_desc") || "");
        $("#viewItemLocModal").modal("show");
    });

    // Controls
    // Show user view modal when a row is clicked
    $("#users-table tbody").on("click", "tr", function () {
        const $row = $(this);
        // Store current row data for use in edit modal
        $("#btnEditUser")
            .data("userid", $row.data("userid"))
            .data("name", $row.data("name"))
            .data("email", $row.data("email"))
            .data("site", $row.data("site"))
            .data("site_desc", $row.data("site_desc"))
            .data("level", $row.data("level"))
            .data("gender", $row.data("gender"))
            .data("department", $row.data("department"))
            .data("position", $row.data("position"))
            .data("profile", $row.data("profile"))
            .data("section", $row.data("section"));

        // Fill view modal
        $("#view-user-site_desc").text($row.data("site_desc") || "");
        $("#view-user-id").text($row.data("userid") || "");
        $("#view-user-name").text($row.data("name") || "");
        $("#view-user-email").text($row.data("email") || "");
        $("#view-user-level").text($row.data("level") || "");
        $("#view-user-gender").text($row.data("gender") || "");
        $("#view-user-department").text($row.data("department") || "-");
        $("#view-user-position").text($row.data("position") || "-");
        $("#view-user-create_date").text($row.data("create_date") || "");
        $("#view-user-label-name").text($row.data("name") || "");
        $("#view-user-profile").attr("src", $row.data("profile"));
        $("#view-user-section").text($row.data("section") || "-");

        $("#viewUserModal").modal("show");
    });

    // When Edit button in view modal is clicked, show edit modal with values
    $("#btnEditUser").on("click", function () {
        const userid = $(this).data("userid");
        const name = $(this).data("name");
        const email = $(this).data("email");
        const site = $(this).data("site");
        const level = $(this).data("level");
        const gender = $(this).data("gender");
        const department = $(this).data("department");
        const position = $(this).data("position");
        const profile = $(this).data("profile");
        const section = $(this).data("section");

        // Set values in edit modal
        $("#edit-user-label-name").text(name || userid);
        $("#edit-user-profile-preview").attr("src", profile);
        $("#edit-rssite").val(site);
        $("#edit-userid").val(userid);
        $("#edit-userid-hidden").val(userid);
        $("#edit-name").val(name);
        $("#edit-email").val(email);
        $("#edit-gender").val(gender);
        $("#edit-level").val(level);
        $("#edit-password").val("");
        $("#edit-existing-profile-pic").val(profile);
        $("#edit-department").val(department || "");
        $("#edit-position").val(position || "");
        $("#edit-section").val(section || "");

        // Hide view modal then show edit modal
        $("#viewUserModal").modal("hide");
        $("#editUserModal").modal("show");
    });

    $("#edit_profile_pic").on("change", function (e) {
        const input = this;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $("#edit-user-profile-preview").attr("src", e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    });

    // Show warehouse view modal when a row is clicked
    $("#warehouse-table tbody").on("click", "tr", function () {
        const $row = $(this);
        // Store current row data for use in edit modal
        $("#editWarehouseBtn")
            .data("rssite", $row.data("rssite"))
            .data("site_desc", $row.data("site_desc"))
            .data("rswhse", $row.data("rswhse"))
            .data("name", $row.data("name"))
            .data("addr", $row.data("addr"));
        // Fill view modal
        $("#view-warehouse-site-desc").text($row.data("rssite_desc") || "");
        $("#view-warehouse-code").text($row.data("rswhse") || "");
        $("#view-warehouse-name").text($row.data("name") || "");
        $("#view-warehouse-addr").text($row.data("addr") || "");
        $("#view-warehouse-label-name").text($row.data("name") || "");
        $("#viewWarehouseModal").modal("show");
    });

    // When Edit button in view modal is clicked, show edit modal with values
    $("#editWarehouseBtn").on("click", function () {
        const rssite = $(this).data("rssite");
        const rswhse = $(this).data("rswhse");
        const name = $(this).data("name");
        const addr = $(this).data("addr");
        // Set select value for site
        $("#edit-rssite").val(rssite);
        // Set input values
        $("#edit-rswhse").val(rswhse);
        $("#edit-name").val(name);
        $("#edit-addr").val(addr);
        // Set hidden original keys
        $("#edit-orig-rssite").val(rssite);
        $("#edit-orig-rswhse").val(rswhse);

        $("#viewWarehouseModal").modal("hide");
        $("#editWarehouseModal").modal("show");
    });

    $("#rackTable tbody").on("click", "tr", function () {
        const $row = $(this);

        $("#view-rack-warehouse").text($row.data("rswhse") || "");
        $("#view-rack-baynum").text($row.data("rsbaynum") || "");
        $("#view-rack-location").text($row.data("rsloc") || "");
        $("#view-rack-description").text($row.data("rsdesc") || "");
        $("#view-rack-quantity").text($row.data("qty") || "0");
        $("#view-rack-createDate").text($row.data("createDate") || "");
        $("#viewRackModal").modal("show");
    });

    // // Receiving Form/Details toggle logic
    // $("#receiving-details").hide();
    // $("#goodsReceivingForm").on("submit", function (e) {
    //     e.preventDefault();

    //     // Pass all form data to the summary in receiving-details-form
    //     $("#details-site").text($("#rssite option:selected").text() || $("#rssite").val() || '-');
    //     $("#details-date").text($("#date").val() || '-');
    //     $("#details-warehouse").text($("#rswhse option:selected").text() || $("#rswhse").val() || '-');
    //     $("#details-jobco").text($("#jobcoreceive").val() || '-');
    //     $("#details-lot").text($("#lot").val() || '-');
    //     $("#details-item").text($("#item").val() || '-');
    //     $("#details-description").text($("#desc").val() || '-');
    //     $("#details-pallet_size").text($("#pallet_size").val() || '-');
    //     $("#details-um").text($("#um").val() || '-');
    //     $("#details-bay").text($("#rsbaynum option:selected").text() || $("#rsbaynum").val() || '-');
    //     $("#details-docno").text($("#docno").val() || '-');

    //     // Get form values
    //     const rssite = $("#rssite").val() || $("input[name='rssite']").val();
    //     const rswhse = $("#rswhse").val();
    //     const rsbaynum = $("#rsbaynum").val();
    //     const dateReceived = $("#date").val();
    //     const um = $("#um").val();

    //     // Format pallet size for summary/details: show "0" if integer 0, else show decimal only if needed
    //     let palletSize = $("#pallet_size").val();
    //     let palletSizeNum = parseFloat(palletSize);
    //     if (isNaN(palletSizeNum) || palletSizeNum === 0) {
    //         palletSize = "0";
    //     } else if (palletSizeNum % 1 === 0) {
    //         palletSize = palletSizeNum.toString();
    //     } else {
    //         palletSize = palletSizeNum.toFixed(2).replace(/\.00$/, "");
    //     }
    //     $("#details-pallet_size").text(palletSize);

    //     // AJAX to get rsloc list
    //     $.ajax({
    //         url: window.appUrl + '/irms/receiving/rsloc-list',
    //         method: 'POST',
    //         data: {
    //             rssite: rssite,
    //             rswhse: rswhse,
    //             rsbaynum: rsbaynum,
    //             item: $("#item").val(),
    //             pallet_size: $("#pallet_size").val(),
    //             _token: $('input[name="_token"]').val()
    //         },
    //         success: function (data) {
    //             const tbody = $("#receivingTable tbody");
    //             tbody.empty();
    //             if (data.length > 0) {
    //                 data.forEach(function(row, idx) {
    //                     // Format qty_onHand: show "0" if integer 0, else show decimal only if needed
    //                     let qtyOnHandNum = parseFloat(row.qty_onHand);
    //                     let qtyOnHand;
    //                     if (isNaN(qtyOnHandNum) || qtyOnHandNum === 0) {
    //                         qtyOnHand = "0";
    //                     } else if (qtyOnHandNum % 1 === 0) {
    //                         qtyOnHand = qtyOnHandNum.toString();
    //                     } else {
    //                         qtyOnHand = qtyOnHandNum.toFixed(2).replace(/\.00$/, "");
    //                     }
    //                     tbody.append(`
    //                         <tr>
    //                             <td>${idx + 1}</td>
    //                             <td class="text-center align-middle"><input type="checkbox" name="select_row[]" value="${idx + 1}" class="big-checkbox"></td>
    //                             <td>${row.rsloc}</td>
    //                             <td><input type="text" class="form-control" value="" disabled></td>
    //                             <td><input type="text" class="form-control text-end" value="" disabled></td>
    //                             <td class="text-end">${qtyOnHand}</td>
    //                             <td>${um}</td>
    //                             <td></td>
    //                         </tr>
    //                     `);
    //                 });
    //             } else {
    //                 tbody.append('<tr><td colspan="8" class="text-center">No Rack Location found.</td></tr>');
    //             }
    //         }
    //     });

    //     // Hide form, show details
    //     $(".card:has(#goodsReceivingForm)").hide();
    //     $("#receiving-details").fadeIn();
    // });

    // $("#btnBackReceiving").on("click", function () {
    //     $("#receiving-details").hide();
    //     $(".card:has(#goodsReceivingForm)").fadeIn();
    // });

    // Reset fields when site is changed
    $("#rssite").on("change", function () {
        // Set date to today
        const today = new Date().toISOString().split("T")[0];
        $("#date").val(today);

        // Select first option for warehouse and bay
        $("#rswhse").prop("selectedIndex", 0);
        $("#rsbaynum").prop("selectedIndex", 0);

        // Reset other fields
        $("#jobco").val("");
        $("#lot").val("");
        $("#item").val("");
        $("#pallet_size").val("");
        $("#um").val("");
        $("#docno").val("");
        $("#item-desc").text("");
    });

    // Disable date greater than today
    const dateInput = document.getElementById("date");
    if (dateInput) {
        const today = new Date().toISOString().split("T")[0];
        dateInput.setAttribute("max", today);
    }

    // Cache form data in localStorage
    // const RECEIVING_FORM_KEY = "irms_goods_receiving_form";
    // // Save form fields to localStorage on change/input
    // $("#goodsReceivingForm :input").on("change input", function () {
    //     const data = {};
    //     $("#goodsReceivingForm :input").each(function () {
    //         if (this.name && this.type !== "submit" && this.type !== "button") {
    //             data[this.name] = $(this).val();
    //         }
    //     });
    //     localStorage.setItem(RECEIVING_FORM_KEY, JSON.stringify(data));
    // });
    // // Restore form fields from localStorage on page load
    // $(document).ready(function () {
    //     const saved = localStorage.getItem(RECEIVING_FORM_KEY);
    //     if (saved) {
    //         const data = JSON.parse(saved);

    //         // Set site first
    //         if (data.rssite) {
    //             $("#rssite").val(data.rssite);
    //             setFieldsEnabled(true);

    //             // Filter warehouse and bay options to match selected site
    //             filterOptions(document.getElementById("rswhse"), data.rssite);
    //             filterOptions(document.getElementById("rsbaynum"), data.rssite);
    //         }

    //         // Now set warehouse and bay after filtering
    //         if (data.rswhse) $("#rswhse").val(data.rswhse);
    //         if (data.rsbaynum) $("#rsbaynum").val(data.rsbaynum);

    //         // Set other fields
    //         Object.entries(data).forEach(([name, value]) => {
    //             if (name !== "rssite" && name !== "rswhse" && name !== "rsbaynum") {
    //                 $(`#goodsReceivingForm [name="${name}"]`).val(value);
    //             }
    //         });
    //     }
    // });
    // $("#btnlogout, #btnReceive").on("click", function () {
    //     localStorage.removeItem(RECEIVING_FORM_KEY);
    // });

    // // Enable/disable row inputs based on checkbox
    // $(document).on('change', '#receivingTable input[type="checkbox"].big-checkbox', function () {
    //     const $row = $(this).closest('tr');
    //     const enabled = $(this).is(':checked');
    //     $row.find('input[type="text"]').prop('disabled', !enabled);

    //     // Get Pallet Size and Date Received from summary/details
    //     let palletSize = $("#details-pallet_size").text() || $("#pallet_size").val();
    //     let dateReceived = $("#details-date").text() || $("#date").val();

    //     // If checked, set Qty to Receive and Date Received
    //     if (enabled) {
    //         $row.find('input[type="text"]').eq(1).val(palletSize); // Qty to Receive
    //         $row.find('td').eq(7).text(dateReceived); // Date Received cell
    //     } else {
    //         $row.find('input[type="text"]').eq(1).val('');
    //         $row.find('td').eq(7).text(''); // Clear Date Received
    //     }
    // });

    // // Select All / Unselect All logic
    // $("#btnSelectAll").on("click", function () {
    //     const checkboxes = $("#receivingTable input[type='checkbox'].big-checkbox");
    //     const allChecked = checkboxes.length > 0 && checkboxes.filter(":checked").length === checkboxes.length;

    //     if (allChecked) {
    //         checkboxes.prop('checked', false).trigger('change');
    //         $(this).find('span').text('Select all');
    //         $(this).find('i').removeClass('bi-x-circle-fill').addClass('bi-check-circle-fill');
    //     } else {
    //         checkboxes.prop('checked', true).trigger('change');
    //         $(this).find('span').text('Unselect all');
    //         $(this).find('i').removeClass('bi-check-circle-fill').addClass('bi-x-circle-fill');
    //     }
    // });

    // // When populating table rows, make sure inputs are disabled by default
    // // Example row (inside your AJAX success):
    // // <td><input type="text" class="form-control" value="" disabled></td>
    // // <td><input type="text" class="form-control text-end" value="" disabled></td>
    // $("#btnReceive").on("click", function () {
    //     const rows = [];
    //     $("#receivingTable tbody tr").each(function () {
    //         const $row = $(this);
    //         const checked = $row.find('input[type="checkbox"].big-checkbox').is(':checked');
    //         if (checked) {
    //             rows.push({
    //                 rssite: $("#rssite").val() || $("input[name='rssite']").val(),
    //                 rswhse: $("#rswhse").val(),
    //                 rsbaynum: $("#rsbaynum").val(),
    //                 rsloc: $row.find('td').eq(2).text(),
    //                 rslot: $("#lot").val(),
    //                 rspallet_num: $row.find('input[type="text"]').eq(0).val(),
    //                 job: $("#jobcoreceive").val(),
    //                 item: $("#item").val(),
    //                 desc: $("#desc").val(),
    //                 um: $("#um").val(),
    //                 qty: $row.find('input[type="text"]').eq(1).val(),
    //                 datercvd: $("#date").val(),
    //                 docnum: $("#docno").val()
    //             });
    //         }
    //     });

    //     if (rows.length === 0) {
    //         alert("Please select at least one row to receive.");
    //         return;
    //     }

    //     $.ajax({
    //         url: window.appUrl + '/irms/receiving/process-goods-received',
    //         method: 'POST',
    //         data: {
    //             rows: rows,
    //             _token: $('input[name="_token"]').val()
    //         },
    //         success: function (response) {
    //             // Show modal
    //             $("body").append(`
    //                 <div class="modal fade" id="goodsReceivedModal" tabindex="-1" aria-labelledby="goodsReceivedModalLabel" aria-hidden="true">
    //                   <div class="modal-dialog modal-dialog-centered">
    //                     <div class="modal-content">
    //                       <div class="modal-header bg-success text-white">
    //                         <h5 class="modal-title" id="goodsReceivedModalLabel">Success</h5>
    //                       </div>
    //                       <div class="modal-body text-center">
    //                         <i class="bi bi-check-circle-fill text-success" style="font-size:2rem;"></i>
    //                         <p class="mt-3 mb-0">Goods received successfully!</p>
    //                       </div>
    //                       <div class="modal-footer justify-content-center">
    //                         <button type="button" class="btn btn-success px-4" id="modalRedirectBtn">OK</button>
    //                       </div>
    //                     </div>
    //                   </div>
    //                 </div>
    //             `);
    //             $("#goodsReceivedModal").modal("show");
    //             $("#modalRedirectBtn").on("click", function () {
    //                 $("#goodsReceivedModal").modal("hide");
    //                 window.location.href = window.appUrl + "/irms/receiving";
    //             });
    //             // Remove modal from DOM after hidden
    //             $("#goodsReceivedModal").on("hidden.bs.modal", function () {
    //                 $(this).remove();
    //             });
    //         }
    //     });
    // });

    // // Dispatching Form/Details toggle logic
    // $("#dispatching-details").hide();
    // $("#goodsDispatchingForm").on("submit", function (e) {
    //     e.preventDefault();

    //     // Pass all form data to the summary in dispatching-details-form
    //     $("#details-site").text($("#rssite option:selected").text() || $("#rssite").val() || '-');
    //     $("#details-date").text($("#date").val() || '-');
    //     $("#details-warehouse").text($("#rswhse").val() || '-');
    //     $("#details-jobco").text($("#jobcodispatch").val() || '-');
    //     $("#details-lot").text($("#lot").val() || '-');
    //     $("#details-item").text($("#item").val() || '-');
    //     $("#details-desc").text($("#desc").val() || '-');
    //     $("#details-um").text($("#um").val() || '-');
    //     $("#details-docno").text($("#docno").val() || '-');

    //     // AJAX to get item in rsloc list
    //     $.ajax({
    //         url: window.appUrl + '/irms/dispatching/item-in-rsloc-list',
    //         method: 'POST',
    //         data: {
    //             rssite: $("#rssite").val() || $("input[name='rssite']").val(),
    //             job: $("#jobcodispatch").val(),
    //             _token: $('input[name="_token"]').val()
    //         },
    //         success: function (data) {
    //             const tbody = $("#dispatchingTable tbody");
    //             tbody.empty();
    //             if (data.length > 0) {
    //                 data.forEach(function(row, idx) {
    //                     let qtyNum = parseFloat(row.qty);
    //                     let qty = isNaN(qtyNum) || qtyNum === 0 ? "0" : (qtyNum % 1 === 0 ? qtyNum.toString() : qtyNum.toFixed(2).replace(/\.00$/, ""));
    //                     tbody.append(`
    //                         <tr>
    //                             <td>${idx + 1}</td>
    //                             <td class="text-center align-middle"><input type="checkbox" name="select_row[]" value="${idx + 1}" class="big-checkbox"></td>
    //                             <td>${row.rsloc}</td>
    //                             <td>${row.rspallet_num || ""}</td>
    //                             <td class="text-end"><input type="text" class="form-control text-end" value="${qty}" disabled></td>
    //                             <td>${row.um || ""}</td>
    //                             <td>${row.datercvd || ""}</td>
    //                         </tr>
    //                     `);
    //                 });
    //             } else {
    //                 tbody.append('<tr><td colspan="8" class="text-center">No Rack Location found.</td></tr>');
    //             }
    //         }
    //     });

    //     // Hide form, show details
    //     $(".card:has(#goodsDispatchingForm)").hide();
    //     $("#dispatching-details").fadeIn();
    // });

    // // Back button to return to form
    // $("#btnBackDispatching").on("click", function () {
    //     $("#dispatching-details").hide();
    //     $(".card:has(#goodsDispatchingForm)").fadeIn();
    // });

    // // Enable/disable Qty to Dispatch input based on checkbox
    // $(document).on('change', '#dispatchingTable input[type="checkbox"].big-checkbox', function () {
    //     const $row = $(this).closest('tr');
    //     const enabled = $(this).is(':checked');
    //     $row.find('input[type="text"]').prop('disabled', !enabled);
    // });

    // // Select All / Unselect All logic for dispatchingTable
    // $("#btnSelectAll").on("click", function () {
    //     const checkboxes = $("#dispatchingTable input[type='checkbox'].big-checkbox");
    //     const allChecked = checkboxes.length > 0 && checkboxes.filter(":checked").length === checkboxes.length;

    //     if (allChecked) {
    //         checkboxes.prop('checked', false).trigger('change');
    //         $(this).find('span').text('Select all');
    //         $(this).find('i').removeClass('bi-x-circle-fill').addClass('bi-check-circle-fill');
    //     } else {
    //         checkboxes.prop('checked', true).trigger('change');
    //         $(this).find('span').text('Unselect all');
    //         $(this).find('i').removeClass('bi-check-circle-fill').addClass('bi-x-circle-fill');
    //     }
    // });

    // $("#btnDispatch").on("click", function () {
    //     const rows = [];
    //     let hasError = false;

    //     $("#dispatchingTable tbody tr").each(function () {
    //         const $row = $(this);
    //         const checked = $row.find('input[type="checkbox"].big-checkbox').is(':checked');
    //         if (checked) {
    //             const qtyInput = $row.find('input[type="text"]');
    //             const qtyToDispatch = parseFloat(qtyInput.val());
    //             const availableQty = parseFloat(qtyInput.attr('value')) || parseFloat(qtyInput.val());
    //             const rsloc = $row.find('td').eq(2).text();
    //             const rspallet_num = $row.find('td').eq(3).text();
    //             const um = $row.find('td').eq(5).text();

    //             // Validate quantity
    //             if (isNaN(qtyToDispatch) || qtyToDispatch <= 0) {
    //                 qtyInput.addClass('is-invalid');
    //                 hasError = true;
    //                 return;
    //             }
    //             if (qtyToDispatch > availableQty) {
    //                 qtyInput.addClass('is-invalid');
    //                 hasError = true;
    //                 return;
    //             }
    //             qtyInput.removeClass('is-invalid');

    //             rows.push({
    //                 rssite: $("#rssite").val() || $("input[name='rssite']").val(),
    //                 rswhse: $("#rswhse").val(),
    //                 rsloc: rsloc,
    //                 rslot: $("#lot").val(),
    //                 rspallet_num: rspallet_num,
    //                 job: $("#jobcodispatch").val(),
    //                 item: $("#item").val(),
    //                 desc: $("#desc").val(),
    //                 um: um,
    //                 qty: qtyToDispatch,
    //                 datedispatch: $("#date").val(),
    //                 docno: $("#docno").val()
    //             });
    //         }
    //     });

    //     if (hasError) {
    //         alert("Please enter a valid quantity to dispatch (must be > 0 and ≤ available quantity) for all selected rows.");
    //         return;
    //     }

    //     if (rows.length === 0) {
    //         alert("Please select at least one row to dispatch.");
    //         return;
    //     }

    //     $.ajax({
    //         url: window.appUrl + '/irms/dispatching/process-goods-dispatch',
    //         method: 'POST',
    //         data: {
    //             rows: rows,
    //             _token: $('input[name="_token"]').val()
    //         },
    //         success: function (response) {
    //             $("body").append(`
    //                 <div class="modal fade" id="goodsDispatchedModal" tabindex="-1" aria-labelledby="goodsDispatchedModalLabel" aria-hidden="true">
    //                 <div class="modal-dialog modal-dialog-centered">
    //                     <div class="modal-content">
    //                     <div class="modal-header bg-success text-white">
    //                         <h5 class="modal-title" id="goodsDispatchedModalLabel">Success</h5>
    //                     </div>
    //                     <div class="modal-body text-center">
    //                         <i class="bi bi-check-circle-fill text-success" style="font-size:2rem;"></i>
    //                         <p class="mt-3 mb-0">Goods dispatched successfully!</p>
    //                     </div>
    //                     <div class="modal-footer justify-content-center">
    //                         <button type="button" class="btn btn-success px-4" id="modalDispatchRedirectBtn">OK</button>
    //                     </div>
    //                     </div>
    //                 </div>
    //                 </div>
    //             `);
    //             $("#goodsDispatchedModal").modal("show");
    //             $("#modalDispatchRedirectBtn").on("click", function () {
    //                 $("#goodsDispatchedModal").modal("hide");
    //                 window.location.href = window.appUrl + "/irms/dispatching";
    //             });
    //             $("#goodsDispatchedModal").on("hidden.bs.modal", function () {
    //                 $(this).remove();
    //             });
    //         }
    //     });
    // });
});

document.addEventListener("DOMContentLoaded", function () {
    // Fade in content wrapper
    const wrapper = document.querySelector(".app-content-wrapper");
    if (wrapper) {
        setTimeout(() => {
            wrapper.classList.add("visible");
        }, 100);
    }

    const alert = document.getElementById("alerts");
    if (alert) {
        setTimeout(() => {
            alert.style.opacity = "0";
            setTimeout(() => {
                alert.style.display = "none";
            }, 700); // matches the transition duration
        }, 3000); // show for 3 seconds
    }

    //Rack Location Add Form - Filter Warehouse and Bay Number based on selected Site
    const siteSelect = document.getElementById("rssite");
    const whseSelect = document.getElementById("rswhse");
    const baySelect = document.getElementById("rsbaynum");
    const rslocInput = document.getElementById("rsloc");
    const rsdecInput = document.getElementById("rsdec");
    const descInput = document.getElementById("desc");

    function filterOptions(select, siteValue) {
        if (!select) return; // Prevent error if element doesn't exist
        Array.from(select.options).forEach((option) => {
            if (!option.value) return;
            option.style.display =
                option.getAttribute("data-site") === siteValue ? "" : "none";
        });
        if (
            select.selectedIndex > 0 &&
            select.options[select.selectedIndex].style.display === "none"
        ) {
            select.selectedIndex = 0;
        }
    }

    // When adding event listeners, check if the element exists
    if (siteSelect && whseSelect && baySelect) {
        siteSelect.addEventListener("change", function () {
            whseSelect.selectedIndex = 0;
            baySelect.selectedIndex = 0;
            filterOptions(whseSelect, this.value);
            filterOptions(baySelect, this.value);
        });

        // whseSelect.addEventListener("change", function () {
        //     baySelect.selectedIndex = 0;
        //     if (rslocInput) rslocInput.value = "";
        //     if (rsdecInput) rsdecInput.value = "";
        // });

        // baySelect.addEventListener("change", function () {
        //     if (rslocInput) rslocInput.value = "";
        //     if (rsdecInput) rsdecInput.value = "";
        // });

        // Initial filter on page load if old value exists
        if (siteSelect.value) {
            filterOptions(whseSelect, siteSelect.value);
            filterOptions(baySelect, siteSelect.value);
        }
    }

    // Auto-fill Lot when typing in Job / CO
    // const jobcoInput = document.getElementById('jobcoreceive');
    // const lotInput = document.getElementById('lot');
    // if (jobcoInput && lotInput) {
    //     jobcoInput.addEventListener('input', function () {
    //         lotInput.value = this.value ? this.value + '-1' : '';
    //     });
    // }

    $("#jobcoreceive").on("input", function () {
        const job = $(this).val();
        const lotInput = document.getElementById("lot");
        let rssite = $("#rssite").val() || $("input[name='rssite']").val();
        if (!rssite) return;

        $.ajax({
            url: window.appUrl + "/irms/receiving/job-item-details",
            method: "POST",
            data: {
                job: job,
                rssite: rssite,
                _token: $('input[name="_token"]').val(),
            },
            success: function (data) {
                if (data.length > 0) {
                    $("#item").val(data[0].item || "");
                    $("#um").val(data[0].u_m || "");
                    let itemdesc;
                    if (data[0].description && data[0].Uf_itemdesc_ext) {
                        itemdesc =
                            data[0].description +
                            " - " +
                            data[0].Uf_itemdesc_ext;
                    } else if (data[0].description) {
                        itemdesc = data[0].description;
                    } else if (data[0].Uf_itemdesc_ext) {
                        itemdesc = data[0].Uf_itemdesc_ext;
                    } else {
                        itemdesc = "";
                    }
                    $("#desc").val(itemdesc || "");

                    let palletSize = data[0].Uf_Item_PalletSize;
                    let palletSizeNum = parseFloat(palletSize);
                    if (isNaN(palletSizeNum) || palletSizeNum === 0) {
                        palletSize = "0";
                    } else if (palletSizeNum % 1 === 0) {
                        palletSize = palletSizeNum.toString();
                    } else {
                        palletSize = palletSizeNum
                            .toFixed(2)
                            .replace(/\.00$/, "");
                    }
                    $("#pallet_size").val(palletSize);
                    lotInput.value = job ? job + "-1" : "";
                } else {
                    $("#item").val("");
                    $("#um").val("");
                    $("#desc").val("");
                    $("#pallet_size").val("");
                    lotInput.value = "";
                }
            },
        });
    });

    $("#jobcodispatch").on("input", function () {
        const job = $(this).val();
        const rssite = $("#rssite").val() || $("input[name='rssite']").val();
        const lotInputDispatch = document.getElementById("lot");

        if (!job || !rssite) return;

        $.ajax({
            url: window.appUrl + "/irms/dispatching/job-item-details",
            method: "POST",
            data: {
                jobco: job,
                rssite: rssite,
                _token: $('input[name="_token"]').val(),
            },
            success: function (data) {
                if (data && Object.keys(data).length > 0) {
                    $("#rswhse").val(data.rswhse || "");
                    $("#item").val(data.item || "");
                    $("#um").val(data.um || "");
                    $("#desc").val(data.desc || "");

                    lotInputDispatch.value = job ? job + "-1" : "";
                } else {
                    $("#rswhse").val("");
                    $("#item").val("");
                    $("#um").val("");
                    $("#desc").val("");
                    lotInputDispatch.value = "";
                }
            },
        });
    });
});

$(
    "#viewUserModal, #editUserModal, #viewWarehouseModal, #editWarehouseModal, #viewBayModal, #viewRackModal"
).on("hide.bs.modal", function () {
    if (document.activeElement && this.contains(document.activeElement)) {
        document.activeElement.blur();
    }
});
