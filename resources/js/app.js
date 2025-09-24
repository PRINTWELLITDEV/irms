import "bootstrap";

import "admin-lte";

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

$.extend($.fn.dataTable.defaults, {
    paging: true,
    info: true,
    lengthChange: false,
    searching: true,
    pageLength: 10,
    responsive: true,
    autoWidth: false,
    layout: {
        topStart: null, // Hides search box
        topEnd: null, // Hides page length / buttons
        bottomStart: "info", // Keep info text
        bottomEnd: "paging", // Keep pagination
    },
    columnDefs: [{ targets: "_all", type: "string" }],
});

$(document).ready(function () {
    const isSa = $("select#rssite").length > 0 && $("input[name='rssite']").length === 0;

    function setFieldsEnabled(enabled) {
        $("#date, #rswhse, #jobco, #lot, #item, #pallet_size, #um, #rsbaynum, #docno").prop("disabled", !enabled);
    }

    if (isSa) {
        setFieldsEnabled(false);
        $("#rssite").on("change", function () {
            setFieldsEnabled(true);
        });
    } else {
        setFieldsEnabled(true);
    }

    // Hide filter boxes initially
    // $(".dataTables_filter").hide();
    // $(".dt-layout-cell").hide();

    // Users table
    const usersTable = $("#users-table").DataTable({
        pageLength: 5,
        language: {
            emptyTable: "No data available",
        },
    });
    $("#userSearch").on("keyup", function () {
        usersTable.search(this.value).draw();
    });

    // Warehouse table
    const warehouseTable = $("#warehouse-table").DataTable({
        fixedHeader: true,
        pageLength: 15,
        columnControl: ["order", ['colVisDropdown']],
        ordering: {
            indicators: false,
            handler: true,
        },
        responsive: true,
        language: {
            emptyTable: "No warehouses found",
        },
    });
    $("#whseSearch").on("keyup", function () {
        warehouseTable.search(this.value).draw();
    });

    // Bay Location table
    const bayLocationTable = $("#bayloc-table").DataTable({
        fixedHeader: true,
        pageLength: 15,
        columnControl: ["order", ['colVisDropdown']],
        ordering: {
            indicators: false,
            handler: true,
        },
        responsive: true,
        language: {
            emptyTable: "No Bay found",
        },
    });

    //Bay Location Search Function
    $("#baylocSearch").on("keyup", function () {
        bayLocationTable.search(this.value).draw();
    });

    //Bay Location Table row selection function
    $("#rackTable").on("click", "tr", function () {
        const $row = $(this);

        $("#view-bay-number").text($row.data("rsbaynum") || "-");
        $("#view-created-date").text($row.data("createdate") || "-");
        $("#view-created-by").text($row.data("createdby") || "-");
    });



    // Rack Location table
    const rackTable = $("#rackTable").DataTable({
        fixedHeader: true,
        pageLength: 15,
        columnControl: ["order", ['colVisDropdown']],
        ordering: {
            indicators: false,
            handler: true,
        },
        responsive: true,
        language: {
            emptyTable: "No Rack found",
        },
    });
    $("#rackSearch").on("keyup", function () {
        rackTable.search(this.value).draw();
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
        $("#view-user-site_desc").text($row.data("site_desc") || "-");
        $("#view-user-id").text($row.data("userid") || "-");
        $("#view-user-name").text($row.data("name") || "-");
        $("#view-user-email").text($row.data("email") || "-");
        $("#view-user-level").text($row.data("level") || "-");
        $("#view-user-gender").text($row.data("gender") || "-");
        $("#view-user-department").text($row.data("department") || "-");
        $("#view-user-position").text($row.data("position") || "-");
        $("#view-user-create_date").text($row.data("create_date") || "-");
        $("#view-user-label-name").text($row.data("name") || "-");
        $("#view-user-profile").attr("src", $row.data("profile"));
        $("#view-user-section").text($row.data("section") || "-");

        $("#viewUserModal").modal("show");
    });

    // Profile picture preview for Add User modal
    $("#profile_pic_url").on("change", function (e) {
        const input = this;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $("#add-user-profile-preview").attr("src", e.target.result);
                $("#add-user-profile-preview-container").show();
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            $("#add-user-profile-preview").attr(
                "src",
                '{{ asset("uploads/user-profile/noprofile.png") }}'
            );
            $("#add-user-profile-preview-container").hide();
        }
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
        $("#edit-department").val(department);
        $("#edit-position").val(position);
        $("#edit-section").val(section);

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
        $("#view-warehouse-site-desc").text($row.data("rssite_desc") || "-");
        $("#view-warehouse-code").text($row.data("rswhse") || "-");
        $("#view-warehouse-name").text($row.data("name") || "-");
        $("#view-warehouse-addr").text($row.data("addr") || "-");
        $("#view-warehouse-label-name").text($row.data("name") || "-");
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


    // Receiving Form/Details toggle logic
    $("#receiving-details").hide();
    $("#goodsReceivingForm").on("submit", function (e) {
        e.preventDefault();

        // Pass all form data to the summary in receiving-details-form
        $("#details-site").text($("#rssite option:selected").text() || $("#rssite").val() || '-');
        $("#details-date").text($("#date").val() || '-');
        $("#details-warehouse").text($("#rswhse option:selected").text() || $("#rswhse").val() || '-');
        $("#details-jobco").text($("#jobco").val() || '-');
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
            url: window.appUrl + '/irms/whse-goodsreceiving/rsloc-list',
            method: 'POST',
            data: {
                rssite: rssite,
                rswhse: rswhse,
                rsbaynum: rsbaynum,
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
    
    // Reset fields when site is changed
    $("#rssite").on("change", function () {
        // Set date to today
        const today = new Date().toISOString().split('T')[0];
        $("#date").val(today);

        // Select first option for warehouse and bay
        $("#rswhse").prop("selectedIndex", 0);
        $("#rsbaynum").prop("selectedIndex", 0);

        // Reset other fields
        $("#jobco").val('');
        $("#lot").val('');
        $("#item").val('');
        $("#pallet_size").val('');
        $("#um").val('');
        $("#docno").val('');
        $("#item-desc").text('');
    });

    // Disable date greater than today
    const dateInput = document.getElementById('date');
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('max', today);
    }

    // Dispatching Form/Details toggle logic
    $("#dispatching-details").hide();
<<<<<<< HEAD

=======
>>>>>>> c62679298258dca6919fffe064497660a5fcc13a
    $("#goodsDispatchingForm").on("submit", function (e) {
        e.preventDefault();

        const site = $("#rssite option:selected").text() || $("#rssite").val() || '-';
        const warehouse = $("#rswhse").val() || '-';
        const bay = $("#rsbaynum").val() || '-';

        $("#details-site").val(site);
        $("#details-date").val($("#date").val() || '-');
        $("#details-warehouse").val(warehouse);
        $("#details-jobco").val($("#jobco").val() || '-');
        $("#details-lot").val($("#lot").val() || '-');
        $("#details-item").val($("#item").val() || '-');
        $("#details-bay").val(bay);
        $("#details-docno").val($("#docno").val() || '-');
        $("#details-pallet_size").val($("#pallet_size").val() || '-');
        $("#details-um").val($("#um").val() || '-');

        $(".card:has(#goodsDispatchingForm)").hide();
        $("#dispatching-details").fadeIn();
    });
    $("#btnBackDispatching").on("click", function () {
        $("#dispatching-details").hide();
        $(".card:has(#goodsDispatchingForm)").fadeIn();
        // $("html, body").animate({ scrollTop: $(".card:has(#goodsDispatchingForm)").offset().top }, -300);
    });
<<<<<<< HEAD
        //Rack Viewing Modals
    $("#rackTable tbody").on("click", "tr", function () {
        const $row = $(this);

        $("#view-rack-warehouse").text($row.data("rswhse") || "-");
        $("#view-rack-baynum").text($row.data("rsbaynum") || "-");
        $("#view-rack-location").text($row.data("rsloc") || "-");
        $("#view-rack-description").text($row.data("rsdesc") || "-");
        $("#view-rack-quantity").text($row.data("qty") || "-");
        $("#view-rack-createDate").text($row.data("createDate") || "-");
        $("#viewRackModal").modal("show");
    });
=======

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
>>>>>>> c62679298258dca6919fffe064497660a5fcc13a
});

document.addEventListener("DOMContentLoaded", function () {
    // Fade in content wrapper
    const wrapper = document.querySelector(".content-wrapper");
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

    function filterOptions(select, siteValue) {
        Array.from(select.options).forEach((option) => {
            if (!option.value) return; // skip placeholder
            option.style.display =
                option.getAttribute("data-site") === siteValue ? "" : "none";
        });
        // Reset selection if current value is not visible
        if (
            select.selectedIndex > 0 &&
            select.options[select.selectedIndex].style.display === "none"
        ) {
            select.selectedIndex = 0;
        }
    }

    // Reset warehouse, bay, and other inputs when site changes
    if (siteSelect) {
        siteSelect.addEventListener("change", function () {
            whseSelect.selectedIndex = 0;
            baySelect.selectedIndex = 0;
            filterOptions(whseSelect, this.value);
            filterOptions(baySelect, this.value);

            // Blank other inputs
            if (rslocInput) rslocInput.value = "";
            if (rsdecInput) rsdecInput.value = "";
        });
    }

    // Reset bay and other inputs when warehouse changes
    if (whseSelect) {
        whseSelect.addEventListener("change", function () {
            baySelect.selectedIndex = 0;

            // Blank other inputs
            if (rslocInput) rslocInput.value = "";
            if (rsdecInput) rsdecInput.value = "";
        });
    }

    // Reset other inputs when bay changes
    if (baySelect) {
        baySelect.addEventListener("change", function () {
            if (rslocInput) rslocInput.value = "";
            if (rsdecInput) rsdecInput.value = "";
        });
    }

    // Initial filter on page load if old value exists
    if (siteSelect && siteSelect.value) {
        filterOptions(whseSelect, siteSelect.value);
        filterOptions(baySelect, siteSelect.value);
    }

    // Auto-fill Lot when typing in Job / CO
    const jobcoInput = document.getElementById('jobco');
    const lotInput = document.getElementById('lot');
    if (jobcoInput && lotInput) {
        jobcoInput.addEventListener('input', function () {
            lotInput.value = this.value ? this.value + '-1' : '';
        });
    }

    $("#jobco").on("input", function () {
        const job = $(this).val();
        let rssite = $("#rssite").val() || $("input[name='rssite']").val();
        if (!rssite) return;

        $.ajax({
            url: window.appUrl + '/irms/whse-goodsreceiving/job-item-details',
            method: 'POST',
            data: {
                job: job,
                rssite: rssite,
                _token: $('input[name="_token"]').val()
            },
            success: function (data) {
                if (data.length > 0) {
                    $("#item").val(data[0].item || '');
                    $("#um").val(data[0].u_m || '');
                    // $("#item-desc").text(data[0].description || '');
                    // $("#item-desc-ext").text(data[0].Uf_itemdesc_ext || '');
                    let itemdesc;
                    if (data[0].description && data[0].Uf_itemdesc_ext) {
                        itemdesc = data[0].description + ' - ' + data[0].Uf_itemdesc_ext;
                    } else if (data[0].description) {
                        itemdesc = data[0].description;
                    } else if (data[0].Uf_itemdesc_ext) {
                        itemdesc = data[0].Uf_itemdesc_ext;
                    } else {
                        itemdesc = '';
                    }
                    $("#desc").val(itemdesc);

                    let palletSize = data[0].Uf_Item_PalletSize;
                    let palletSizeNum = parseFloat(palletSize);
                    if (isNaN(palletSizeNum) || palletSizeNum === 0) {
                        palletSize = "0";
                    } else if (palletSizeNum % 1 === 0) {
                        palletSize = palletSizeNum.toString();
                    } else {
                        palletSize = palletSizeNum.toFixed(2).replace(/\.00$/, "");
                    }
                    $("#pallet_size").val(palletSize);
                    // $("#pallet_size").val(data[0].Uf_Item_PalletSize || '');
                } else {
                    $("#item").val('');
                    $("#um").val('');
                    $("#item-desc").text('');
                    $("#pallet_size").val('');
                }
            }
        });
    });
});



// Chart JS
(function () {
    // utility: random integer in [min,max]
    function randInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
    function randArray(len, min = 0, max = 100) {
        return Array.from({ length: len }, () => randInt(min, max));
    }

    // safe init for knob (unchanged)
    try {
        if ($(".tron-knob").length) {
            $(".tron-knob").knob({
                min: 0,
                max: 100,
                readOnly: false,
                width: 150,
                height: 150,
                angleOffset: -125,
                angleArc: 250,
                rotation: "anticlockwise",
                fgColor: "#00cccc",
                bgColor: "#f0f0f0",
                inputColor: "#111",
                font: "Arial, sans-serif",
                fontWeight: "bold",
            });
        }
    } catch (e) {
        console.warn("Knob init failed", e);
    }

    // replace DOMContentLoaded wrapper with an init function that runs immediately if DOM is already ready
    function initDashboardCharts() {
        // helper: create chart only if canvas exists
        function createChart(canvasId, configFactory) {
            const el = document.getElementById(canvasId);
            if (!el) return null;
            const ctx = el.getContext("2d");
            try {
                return new Chart(ctx, configFactory());
            } catch (err) {
                console.error("Chart creation error for", canvasId, err);
                return null;
            }
        }

        // labels
        const labels = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ];
        const site_labels = ["PI", "FPC", "PWPC"];
        const day_name_label = [
            "Sunday",
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
        ];

        // generate random data for each dataset
        const dataA = randArray(labels.length, 5, 95);
        const dataB = randArray(labels.length, 5, 95);
        const dataC = randArray(day_name_label.length, 0, 120);
        const dataC2 = randArray(day_name_label.length, 0, 120);
        const dataD = randArray(site_labels.length, 10, 80);
        const dataE = randArray(site_labels.length, 5, 95);
        const dataF = randArray(site_labels.length, 5, 95);

        // Bar / Pie / Line / chart2 (unchanged)
        createChart("chart_bar", function () {
            return {
                type: "bar",
                data: {
                    labels: site_labels,
                    datasets: [
                        {
                            label: "Occupied",
                            data: dataE,
                            backgroundColor: "rgba(54,162,235,0.6)",
                            borderColor: "rgba(54,162,235,1)",
                            borderWidth: 1,
                        },
                        {
                            label: "Available",
                            data: dataF,
                            backgroundColor: "rgba(255,159,64,0.6)",
                            borderColor: "rgba(255,159,64,1)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                },
            };
        });

        createChart("chart_line2", function () {
            return {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "Monthly Transactions",
                            data: randArray(labels.length, 50, 200),
                            backgroundColor: "rgba(54,162,235,0.6)",
                            borderColor: "rgba(54,162,235,1)",
                            borderWidth: 1,
                        },
                        {
                            label: "Target",
                            data: randArray(labels.length, 5, 100), // Constant target line
                            type: "line",
                            borderColor: "rgba(255,99,132,1)",
                            borderWidth: 2,
                            fill: false,
                            pointRadius: 0,
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    interaction: {
                        intersect: false,
                        mode: "index",
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: "Transaction Count",
                            },
                        },
                    },
                    plugins: {
                        legend: {
                            position: "bottom",
                        },
                        title: {
                            display: true,
                            text: "Monthly Transaction Recap",
                        },
                    },
                },
            };
        });

        createChart("chart_pie", function () {
            return {
                type: "pie",
                data: {
                    labels: site_labels,
                    datasets: [
                        {
                            data: dataD,
                            backgroundColor: ["#ff6384", "#36a2eb", "#ffcd56"],
                        },
                    ],
                },
                options: { maintainAspectRatio: false, responsive: true },
            };
        });

        createChart("chart_line", function () {
            return {
                type: "line",
                data: {
                    labels: day_name_label,
                    datasets: [
                        {
                            label: "Incoming",
                            data: dataC,
                            borderColor: "#4bc0c0",
                            backgroundColor: "rgba(75,192,192,0.2)",
                            fill: true,
                        },
                        {
                            label: "Outgoing",
                            data: dataC2,
                            borderColor: "#c0a94bff",
                            backgroundColor: "rgba(205, 184, 60, 0.2)",
                            fill: true,
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                },
            };
        });

        createChart("chart2", function () {
            return {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "Transactions",
                            data: randArray(labels.length, 0, 120),
                            backgroundColor: "rgba(153,102,255,0.6)",
                            borderColor: "rgba(153,102,255,1)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                },
            };
        });

        // Real-time chart: push random y values every second
        // Real-time chart: push random y values every second
        (function createRealTimeChart() {
            const el = document.getElementById("realTimeAreaChart");
            if (!el) return;

            el.style.height = "250px";
            el.height = 250;
            const ctxRT = el.getContext("2d");

            const config = {
                type: "line",
                data: {
                    datasets: [
                        {
                            label: "Live Transactions",
                            backgroundColor: "rgba(75,192,192,0.4)",
                            borderColor: "rgb(75,192,192)",
                            fill: "origin",
                            data: dataD,
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: {
                        x: {
                            type: "realtime", // <--- Change type from 'time' to 'realtime'
                            realtime: {
                                // <--- ADD THIS BLOCK
                                delay: 2000,
                                onRefresh: (chart) => {
                                    chart.data.datasets.forEach((dataset) => {
                                        dataset.data.push({
                                            x: Date.now(),
                                            y: randInt(0, 120), // Use your randInt function
                                        });
                                    });
                                },
                            },
                            time: {
                                unit: "second",
                                tooltipFormat: "HH:mm:ss",
                            },
                            ticks: {
                                source: "auto",
                            },
                            grid: {
                                display: false,
                            },
                        },
                        y: {
                            beginAtZero: true,
                        },
                    },
                    plugins: {
                        legend: {
                            display: true,
                        },
                        tooltip: {
                            mode: "index",
                            intersect: false,
                        },
                    },
                },
            };

            const myRealTimeChart = new Chart(ctxRT, config);
        })();

        /* ===== Fixed: initialize myChart here (guarded) ===== */
        try {
            // Utility helpers used by myChart
            const Utils = {
                months: (config) => {
                    const labels = [
                        "January",
                        "February",
                        "March",
                        "April",
                        "May",
                        "June",
                        "July",
                        "August",
                        "September",
                        "October",
                        "November",
                        "December",
                    ];
                    const count = (config && config.count) || 12;
                    return labels.slice(0, count);
                },
                numbers: (config) => {
                    const count = (config && config.count) || 0;
                    const min = (config && config.min) || 0;
                    const max = (config && config.max) || 100;
                    const arr = [];
                    for (let i = 0; i < count; i++)
                        arr.push(
                            Math.floor(Math.random() * (max - min + 1) + min)
                        );
                    return arr;
                },
                CHART_COLORS: {
                    red: "rgb(255, 99, 132)",
                    blue: "rgb(54, 162, 235)",
                    yellow: "rgb(255, 205, 86)",
                    green: "rgb(75, 192, 192)",
                    purple: "rgb(153, 102, 255)",
                    orange: "rgb(255, 159, 64)",
                },
                transparentize: (color, opacity) => {
                    const alpha = opacity === undefined ? 0.5 : 1 - opacity;
                    return (
                        color.replace("rgb", "rgba").slice(0, -1) + `,${alpha})`
                    );
                },
                namedColor: (index) =>
                    Object.values(Utils.CHART_COLORS)[
                        index % Object.values(Utils.CHART_COLORS).length
                    ],
                rand: (min, max) =>
                    Math.floor(Math.random() * (max - min + 1) + min),
            };

            const myCtxEl = document.getElementById("myChart");
            if (myCtxEl && typeof Chart !== "undefined") {
                const DATA_COUNT = 7;
                const NUMBER_CFG = { count: DATA_COUNT, min: 0, max: 100 };
                const labelsLocal = Utils.months({ count: DATA_COUNT });
                const dataLocal = {
                    labels: labelsLocal,
                    datasets: [
                        {
                            label: "Dataset 1",
                            data: Utils.numbers(NUMBER_CFG),
                            borderColor: Utils.CHART_COLORS.red,
                            backgroundColor: Utils.transparentize(
                                Utils.CHART_COLORS.red,
                                0.5
                            ),
                            stack: "combined",
                            type: "bar",
                        },
                        {
                            label: "Dataset 2",
                            data: Utils.numbers(NUMBER_CFG),
                            borderColor: Utils.CHART_COLORS.blue,
                            backgroundColor: Utils.transparentize(
                                Utils.CHART_COLORS.blue,
                                0.5
                            ),
                            stack: "combined",
                        },
                    ],
                };
                const configLocal = {
                    type: "line",
                    data: dataLocal,
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: "Chart.js Stacked Line/Bar Chart",
                            },
                        },
                        scales: { y: { stacked: true } },
                    },
                };
                const ctx2 = myCtxEl.getContext("2d");
                const myChart = new Chart(ctx2, configLocal);
            }
        } catch (err) {
            console.error("myChart init error", err);
        }
    } // end initDashboardCharts

    // run init immediately if DOM already loaded, otherwise wait
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initDashboardCharts);
    } else {
        initDashboardCharts();
    }
})();
