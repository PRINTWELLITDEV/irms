import "bootstrap";

import "admin-lte";

$.extend($.fn.dataTable.defaults, {
    searching: false,
    ordering: false,
});
setInterval(function() {
    // Get current path
    const currentPath = window.location.pathname;
    // Only run session check if current path contains /irms
    if (currentPath.indexOf('/irms') !== -1) {
        fetch(window.sessionCheckUrl)
            .then(response => response.json())
            .then(data => {
                if (!data.valid) {
                    window.location.href = "{{ url('/login') }}";
                }
            });
    }
}, 5000);

$(document).ready(function () {
    // Users table
    const usersTable = $("#users-table").DataTable({
        paging: true,
        info: true,
        lengthChange: false,
        searching: false,
        columnControl: ['order', 'colVisDropdown'],
        ordering: {
            indicator: false,
            handler: false,
        },
        pageLength: 12,
        language: {
            emptyTable: "No data available",
        },

        // columnDefs: [
        // //     { orderable: false, targets: [4] } // 0: Profile, 6: Action
        //     {
        //         target: 0,
        //         type: 'anti-the'
        //     }
        // ],
    });
    $("#userSearch").on("keyup", function () {
        usersTable.search(this.value).draw();
    });


    // Warehouse table
    const warehouseTable = $("#warehouse-table").DataTable({
        paging: true,
        info: true,
        lengthChange: false,
        searching: true,
        pageLength: 10,
        language: {
            emptyTable: "No warehouses found",
        },
        // columnDefs: [
        //     { orderable: false, targets: [0] }
        // ]
    });
    $("#whseSearch").on("keyup", function () {
        warehouseTable.search(this.value).draw();
    });

    // Bay Location table
    const bayLocationTable = $("#bayloc-table").DataTable({
        paging: true,
        info: true,
        lengthChange: false,
        searching: true,
        pageLength: 10,
        language: {
            emptyTable: "No bay locations found",
        },
        // columnDefs: [
        //     { orderable: false, targets: [0, 4] }
        // ]
    });
    $("#baylocSearch").on("keyup", function () {
        bayLocationTable.search(this.value).draw();
    });

    const rackTable = $('#rackTable').DataTable({
        paging: true,
        info: true,
        lengthChange: false,
        searching: true,
        pageLength: 10,
        language: {
            emptyTable: "No rack locations found"
        },
        responsive: true,
        stripeClasses: []
    });
    $('#rackSearch').on('keyup', function () {
        rackTable.search(this.value).draw();
    });

    $('#rackTable').on('draw.dt', function() {
        // Hide the manual empty row if DataTables is active
        $('#no-rack-row').hide();
    });

    // Hide filter boxes initially
    $(".dataTables_filter").hide();

    // Show user view modal when a row is clicked
    $("#users-table tbody").on("click", "tr", function () {
        const $row = $(this);
        $("#view-user-profile").attr("src", $row.data("profile"));
        $("#view-user-name").text($row.data("name"));
        $("#view-user-id").text($row.data("userid"));
        $("#view-user-email").text($row.data("email"));
        $("#view-user-site").text($row.data("site"));
        $("#view-user-site_desc").text($row.data("site_desc") || "-");
        $("#view-user-level").text($row.data("level"));
        const gender = $row.data("gender");
        $("#view-user-gender").text(
            gender
                ? gender.charAt(0).toUpperCase() + gender.slice(1).toLowerCase()
                : "-"
        );
        $("#view-user-create_date").text($row.data("create_date"));
        $("#view-user-label-name").text($row.data("name"));
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

    // // When settings button is clicked
    // $('#warehouse-table').on('click', '.btn-settings', function () {
    //     const rssiteDesc = $(this).data('rssite_desc');
    //     const rswhse = $(this).data('rswhse');
    //     const name = $(this).data('name');
    //     const addr = $(this).data('addr');

    //     $('#ws-site').text(rssiteDesc || '');
    //     $('#ws-whse').text(rswhse || '');
    //     $('#ws-name').text(name || '');
    //     $('#ws-addr').text(addr || '');

    //     // Store data for edit modal on the Edit button if needed
    //     $('#editWarehouseBtn')
    //         .data('rssite', $(this).data('rssite'))
    //         .data('rswhse', rswhse)
    //         .data('name', name)
    //         .data('addr', addr);
    // });

    // // When Edit modal is about to be shown, set values and select the correct site
    // $('#editWarehouseModal').on('show.bs.modal', function () {
    //     const editBtn = $('#editWarehouseBtn');
    //     const rssite = editBtn.data('rssite');
    //     const rswhse = editBtn.data('rswhse');
    //     const name = editBtn.data('name');
    //     const addr = editBtn.data('addr');

    //     // Set select value for site
    //     $('#edit-rssite').val(rssite);

    //     // Set input values
    //     $('#edit-rswhse').val(rswhse);
    //     $('#edit-name').val(name);
    //     $('#edit-addr').val(addr);

    //     // Set hidden original keys
    //     $('#edit-orig-rssite').val(rssite);
    //     $('#edit-orig-rswhse').val(rswhse);
    // });

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

    let selectedUserData = null;

    // open view modal when a row is clicked and populate view modal
    document
        .querySelectorAll("#users-table tbody tr[data-userid]")
        .forEach((row) => {
            row.addEventListener("click", function () {
                const d = this.dataset;
                selectedUserData = {
                    userid: d.userid,
                    name: d.name,
                    email: d.email,
                    rssite: d.site,
                    rssite_desc: d.site_desc,
                    level: d.level,
                    gender: d.gender,
                    profile: d.profile,
                    create_date: d.create_date,
                };

                // populate view modal fields
                document.getElementById("view-user-label-name").textContent =
                    selectedUserData.name || selectedUserData.userid;
                document.getElementById("view-user-profile").src =
                    selectedUserData.profile ||
                    '{{ asset("uploads/user-profile/noprofile.png") }}';
                document.getElementById("view-user-site_desc").textContent =
                    selectedUserData.rssite_desc ||
                    selectedUserData.rssite ||
                    "-";
                document.getElementById("view-user-id").textContent =
                    selectedUserData.userid || "-";
                document.getElementById("view-user-name").textContent =
                    selectedUserData.name || "-";
                document.getElementById("view-user-email").textContent =
                    selectedUserData.email || "-";
                document.getElementById("view-user-level").textContent =
                    selectedUserData.level || "-";
                document.getElementById("view-user-gender").textContent =
                    selectedUserData.gender
                        ? selectedUserData.gender.charAt(0).toUpperCase() +
                          selectedUserData.gender.slice(1)
                        : "-";
                document.getElementById("view-user-create_date").textContent =
                    selectedUserData.create_date || "-";

                // show view modal
                const viewEl = document.getElementById("viewUserModal");
                const viewModal =
                    bootstrap.Modal.getInstance(viewEl) ||
                    new bootstrap.Modal(viewEl);
                viewModal.show();
            });
        });

    // Edit button in view modal -> populate edit form and show edit modal
    document
        .getElementById("btnEditUser")
        ?.addEventListener("click", function () {
            if (!selectedUserData) return;

            // populate edit form fields
            document.getElementById("edit-user-label-name").textContent =
                selectedUserData.name || selectedUserData.userid;
            document.getElementById("edit-user-profile-preview").src =
                selectedUserData.profile ||
                '{{ asset("uploads/user-profile/noprofile.png") }}';
            document.getElementById("edit-rssite").value =
                selectedUserData.rssite || "";
            document.getElementById("edit-userid").value =
                selectedUserData.userid || "";
            document.getElementById("edit-userid-hidden").value =
                selectedUserData.userid || "";
            document.getElementById("edit-name").value =
                selectedUserData.name || "";
            document.getElementById("edit-email").value =
                selectedUserData.email || "";
            document.getElementById("edit-gender").value =
                selectedUserData.gender || "";
            document.getElementById("edit-level").value =
                selectedUserData.level || "";
            document.getElementById("edit-password").value = "";

            // set form action to the appropriate resource URL (adjust if your route differs)
            const form = document.getElementById("editUserForm");
            form.action = `/rsusers/${encodeURIComponent(
                selectedUserData.userid
            )}`;

            // hide view modal then show edit modal
            const viewEl = document.getElementById("viewUserModal");
            bootstrap.Modal.getInstance(viewEl)?.hide();

            const editEl = document.getElementById("editUserModal");
            const editModal = new bootstrap.Modal(editEl);
            editModal.show();
        });

    // preview selected profile image in edit modal
    document
        .getElementById("edit_profile_pic")
        ?.addEventListener("change", function (e) {
            const f = this.files && this.files[0];
            if (!f) return;
            const img = document.getElementById("edit-user-profile-preview");
            img.src = URL.createObjectURL(f);
        });

    // Delete handler (from view modal)
    document
        .getElementById("btnDeleteUser")
        ?.addEventListener("click", async function () {
            if (!selectedUserData || !selectedUserData.userid) return;
            if (
                !confirm(
                    "Delete user " +
                        selectedUserData.userid +
                        "? This cannot be undone."
                )
            )
                return;

            try {
                const token =
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "{{ csrf_token() }}";
                const res = await fetch(
                    `/rsusers/${encodeURIComponent(selectedUserData.userid)}`,
                    {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": token,
                            Accept: "application/json",
                        },
                    }
                );
                if (res.ok) {
                    location.reload();
                } else {
                    alert("Delete failed");
                }
            } catch (err) {
                console.error(err);
                alert("Delete failed");
            }
        });

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
