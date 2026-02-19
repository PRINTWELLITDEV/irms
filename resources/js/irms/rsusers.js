import Swal from "sweetalert2";
import moment from "moment";

function loadUserTable() {
    $.get(
        window.appUrl + "/irms/manage-users/user-list",
        function (html) {
            if ($.fn.DataTable.isDataTable("#users-table")) {
                $("#users-table").DataTable().clear().destroy();
            }

            $("#userTableBody").html(html);

            const usersTable = $("#users-table").DataTable({
                pageLength: 5,
                fixedHeader: true,
                columnControl: ["order", ["searchList"]],
                ordering: {
                    indicators: false,
                    handler: true,
                },
                responsive: true,
                language: {
                    emptyTable: "No users found",
                },
            });
            $("#userSearch").on("keyup", function () {
                usersTable.search(this.value).draw();
            });
        }
    );
}

// Call on page load
if (window.location.pathname.includes('/manage-users')) {
    loadUserTable();
}

// setInterval(function() {
//     if (window.location.pathname.includes('/manage-users')) {
//         loadUserTable();
//     }
// }, 5000);

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
    $("#view-user-profile").attr(
        "src",
        window.appUrl + "/" + $row.data("profile")
    );
    $("#view-user-section").text($row.data("section") || "-");

    $("#viewUserModal").modal("show");
});

// When Edit button in view modal is clicked, show edit modal with values
$("#btnEditUser").on("click", function () {
    const userid = $(this).data("userid");
    const name = $(this).data("name");
    const email = $(this).data("email");
    const site = $(this).data("site");
    const site_desc = $(this).data("site_desc");
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
    $("#edit-rssite-desc").val(site_desc);
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

$("#addUserModal .btn-success").on("click", function (e) {
    e.preventDefault();
    const form = $("#addUserModal form")[0];
    const formData = new FormData(form);

    $.ajax({
        url: $(form).attr("action"),
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: data.message || "User added successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            $("#addUserModal").modal("hide");
            form.reset();
            setTimeout(loadUserTable, 500); // Add a 0.5s delay
        },
        error: function (xhr) {
            let msg = "An error occurred.";
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
});
