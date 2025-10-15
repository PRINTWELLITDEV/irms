import "bootstrap";

import "admin-lte";

import "./datatables.js";
import "./goods-receiving.js";
import "./goods-dispatching.js";
import "./charts.js";

// window.appUrl = "{{ url('') }}";
// window.sessionCheckUrl = "{{ url('/irms/session') }}";
// window.loginUrl = "{{ route('login') }}";

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

    // --- Rack Map Filter: Show warehouse and bay options depending on rssite (map tab) ---
    // Map tab filter logic
    const mapSiteSelect = document.getElementById("mapRsSite");
    const mapWhseSelect = document.getElementById("mapRsWhse");
    const mapBaySelect = document.getElementById("mapRsBay");

    function filterMapOptions(select, siteValue) {
        if (!select) return;
        Array.from(select.options).forEach((option) => {
            if (!option.value) return;
            option.style.display =
                option.getAttribute("data-site") === siteValue ? "" : "none";
        });
        // Reset selection if current is hidden
        if (
            select.selectedIndex > 0 &&
            select.options[select.selectedIndex].style.display === "none"
        ) {
            select.selectedIndex = 0;
        }
    }

    // For 'sa', filter on change
    if (mapSiteSelect && mapWhseSelect && mapBaySelect) {
        mapSiteSelect.addEventListener("change", function () {
            mapWhseSelect.selectedIndex = 0;
            mapBaySelect.selectedIndex = 0;
            filterMapOptions(mapWhseSelect, this.value);
            filterMapOptions(mapBaySelect, this.value);
        });

        // Initial filter on page load if old value exists
        if (mapSiteSelect.value) {
            filterMapOptions(mapWhseSelect, mapSiteSelect.value);
            filterMapOptions(mapBaySelect, mapSiteSelect.value);
        }
    }

    $("#mapRsSite, #mapRsWhse, #mapRsBay").on("change", function () {
        let rssite = $("#mapRsSite").val();
        let rswhse = $("#mapRsWhse").val();
        let rsbaynum = $("#mapRsBay").val();

        if (rssite && rswhse && rsbaynum) {
            $.post({
                url: window.appUrl + "/irms/rack-locations/map-grid",
                data: {
                    rssite: rssite,
                    rswhse: rswhse,
                    rsbaynum: rsbaynum,
                    _token: $('input[name="_token"]').val(),
                },
                success: function (data) {
                    renderRackMapGrid(data, rsbaynum);
                },
            });
        }
    });

    function renderRackMapGrid(locations, rsbaynum) {
        // Levels
        let levels = [
            ...new Set(locations.map((l) => l.rsloc.match(/-(L\d+)-/)?.[1])),
        ].filter(Boolean)
            .sort((a, b) => parseInt(b.replace("L", "")) - parseInt(a.replace("L", "")));

        // Columns (C01, C02, ...)
        let columns = [
            ...new Set(
                locations.map((l) => {
                    let m = l.rsloc.match(/-C(\d+)-S/);
                    return m ? m[1] : null;
                })
            ),
        ].filter(Boolean)
            .map(Number);

        // Slots
        let slots = [
            ...new Set(locations.map((l) => l.rsloc.match(/-(S\d+)$/)?.[1])),
        ].filter(Boolean)
            .sort();

        // Sort columns: Odd baynum = descending, Even = ascending
        let baynumDigits = rsbaynum.match(/\d+/);
        let isOdd = baynumDigits && parseInt(baynumDigits[0]) % 2 === 1;
        if (isOdd) {
            columns.sort((a, b) => b - a); // Descending for odd
            slots.sort().reverse(); 
        } else {
            columns.sort((a, b) => a - b); // Ascending for even
            slots.sort(); 
        }

        // If any level/column/slot is missing, fill the grid with blanks
        let html =
            '<table class="table table-bordered text-center align-middle"><tbody>';
        levels.forEach((level) => {
            html += "<tr>";
            columns.forEach((colNum) => {
                let colStr = colNum.toString().padStart(2, "0");
                slots.forEach((slot) => {
                    let rsloc = `${rsbaynum}-${level}-C${colStr}-${slot}`;
                    let found = locations.find((l) => l.rsloc === rsloc);
                    html += `<td style="min-width:32px;height:100px;vertical-align:middle;font-size:0.8em;">${
                        found ? found.rsloc : ""
                    }</td>`;
                });
            });
            html += "</tr>";
        });
        html += "</tbody></table>";
        $("#rack-map-grid").html(html);
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const alert = document.getElementById("alerts");
    if (alert) {
        setTimeout(() => {
            alert.style.opacity = "0";
            setTimeout(() => {
                alert.style.display = "none";
            }, 700); // matches the transition duration
        }, 3000); // show for 3 seconds
    }

    // Scroll to tab-content on mobile when a tab is clicked
    const tabLinks = document.querySelectorAll('#profileTab a[data-bs-toggle="tab"]');
    const tabContent = document.getElementById('profileTabContent');

    tabLinks.forEach(link => {
        link.addEventListener('shown.bs.tab', function () {
            if (window.innerWidth <= 768 && tabContent) {
                tabContent.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Fade in content wrapper
    const wrapper = document.querySelector(".app-content-wrapper");
    if (wrapper) {
        setTimeout(() => {
            wrapper.classList.add("visible");
        }, 100);
    }

    //Rack Location Add Form - Filter Warehouse and Bay Number based on selected Site
    const siteSelect = document.getElementById("rssite");
    const whseSelect = document.getElementById("rswhse");
    const baySelect = document.getElementById("rsbaynum");
    // const rslocInput = document.getElementById("rsloc");
    // const rsdecInput = document.getElementById("rsdec");
    // const descInput = document.getElementById("desc");

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

$("#viewUserModal, #editUserModal, #viewWarehouseModal, #editWarehouseModal, #viewBayModal, #viewRackModal")
.on("hide.bs.modal", function () {
    if (document.activeElement && this.contains(document.activeElement)) {
        document.activeElement.blur();
    }

    // Change Password Form Submission with Fetch API
    const form = document.getElementById('changePasswordForm');
    if (!form) return;
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const msg = document.getElementById('changePasswordMsg');
        msg.innerHTML = '';
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            if (response.ok) {
                msg.innerHTML = `<div id="changePasswordAlert" class="alert alert-success">${data.message}</div>`;
                form.reset();
                setTimeout(() => {
                    const alert = document.getElementById('changePasswordAlert');
                    if (alert) {
                        alert.style.transition = "opacity 0.7s";
                        alert.style.opacity = "0";
                        setTimeout(() => alert.remove(), 700);
                    }
                }, 1500); // Show for 1.5 seconds, then fade out
            } else {
                let errorMsg = data.message || 'An error occurred.';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).join('<br>');
                }
                msg.innerHTML = `<div id="changePasswordAlert" class="alert alert-danger">${errorMsg}</div>`;
                setTimeout(() => {
                    const alert = document.getElementById('changePasswordAlert');
                    if (alert) {
                        alert.style.transition = "opacity 0.7s";
                        alert.style.opacity = "0";
                        setTimeout(() => alert.remove(), 700);
                    }
                }, 2500); // Show error a bit longer
            }
        })
        .catch(() => {
            msg.innerHTML = `<div class="alert alert-danger">Server error. Please try again.</div>`;
        });
    });
});
