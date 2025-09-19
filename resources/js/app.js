import "bootstrap";

import "admin-lte";

setInterval(function() {
    // Get current path
    const currentPath = window.location.pathname;
    // Only run session check if current path contains /irms
    if (currentPath.indexOf('/irms') !== -1) {
        fetch(window.sessionCheckUrl)
        .then(response => response.json())
        .then(data => {
            if (!data.valid) {
                window.location.href = '/login';
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
        topStart: null,   // Hides search box
        topEnd: null,     // Hides page length / buttons
        bottomStart: 'info',   // Keep info text
        bottomEnd: 'paging'    // Keep pagination
    },
    columnDefs: [
        { targets: '_all', type: 'string' }
    ]
});

$(document).ready(function () {
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
        // pageLength: 5,
        language: {
            emptyTable: "No warehouses found",
        },
    });
    $("#whseSearch").on("keyup", function () {
        warehouseTable.search(this.value).draw();
    });

    // Bay Location table
    const bayLocationTable = $("#bayloc-table").DataTable({
        language: {
            emptyTable: "No bay locations found",
        },
    });
    $("#baylocSearch").on("keyup", function () {
        bayLocationTable.search(this.value).draw();
    });
    // Rack Location table
    const rackTable = $('#rackTable').DataTable({
        language: {
            emptyTable: "No rack locations found"
        },
        
    });
    $('#rackSearch').on('keyup', function () {
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
            .data("profile", $row.data("profile"));

        // Fill view modal
        $("#view-user-site_desc").text($row.data("site_desc") || "-");
        $("#view-user-id").text($row.data("userid") || "-");
        $("#view-user-name").text($row.data("name") || "-");
        $("#view-user-email").text($row.data("email") || "-");
        $("#view-user-level").text($row.data("level") || "-");
        $("#view-user-gender").text($row.data("gender") || "-");
        $("#view-user-create_date").text($row.data("create_date") || "-");
        $("#view-user-label-name").text($row.data("name") || "-");
        $("#view-user-profile").attr("src", $row.data("profile"));

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
        const profile = $(this).data("profile");

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

        // Set form action to the appropriate resource URL (adjust if your route differs)
        // $("#editUserForm").attr("action", "/irms/manage-users/" + userid);

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
    });

    
});

document.addEventListener("DOMContentLoaded", function () {
    // Fade in content wrapper
    const wrapper = document.querySelector(".content-wrapper");
    if (wrapper) {
        setTimeout(() => {
            wrapper.classList.add("visible");
        }, 100);
    }

    const alert = document.getElementById("success-alert");
    if (alert) {
        setTimeout(() => {
            alert.style.opacity = "0";
            setTimeout(() => {
                alert.style.display = "none";
            }, 700); // matches the transition duration
        }, 3000); // show for 3 seconds
    }

    //Rack Location Add Form - Filter Warehouse and Bay Number based on selected Site
    const siteSelect = document.getElementById('rssite');
    const whseSelect = document.getElementById('rswhse');
    const baySelect = document.getElementById('rsbaynum');

    function filterOptions(select, siteValue) {
        Array.from(select.options).forEach(option => {
            if (!option.value) return; // skip placeholder
            option.style.display = option.getAttribute('data-site') === siteValue ? '' : 'none';
        });
        // Reset selection if current value is not visible
        if (select.selectedIndex > 0 && select.options[select.selectedIndex].style.display === 'none') {
            select.selectedIndex = 0;
        }
    }

    siteSelect.addEventListener('change', function () {
        const siteValue = this.value;
        filterOptions(whseSelect, siteValue);
        filterOptions(baySelect, siteValue);
    });

    // Initial filter on page load if old value exists
    if (siteSelect.value) {
        filterOptions(whseSelect, siteSelect.value);
        filterOptions(baySelect, siteSelect.value);
    }
});
