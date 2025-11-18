import "bootstrap";
import "admin-lte";

// Import chart functionality
import "./datatables.js";

// Import dashboard charts
import "./dashboard-charts.js";

// Import module-specific scripts
import "./rssite.js";
import "./rsusers.js";
import "./rswhse.js";
import "./rsbayloc.js";
import "./rsloc.js";
import "./rsitemloc.js";
import "./rstrans.js";

// Import goods management scripts
import "./goods-receiving.js";
import "./goods-dispatching.js";

/* =====================================================
   SESSION MANAGEMENT
===================================================== */
// Check session validity every hour for IRMS pages
setInterval(function () {
    const currentPath = window.location.pathname;
    if (currentPath.indexOf("/irms") !== -1) {
        fetch(window.sessionCheckUrl)
            .then((response) => response.json())
            .then((data) => {
                if (!data.valid) {
                    window.location.href = window.loginUrl;
                }
            })
            .catch(() => {
                // Silent fail - don't redirect on network errors
                console.warn("Session check failed - network error");
            });
    }
}, 3600000); // 1 hour = 3600000ms


/* =====================================================
   DOCUMENT READY EVENT HANDLERS
===================================================== */
$(document).ready(function () {
    // Initialize UI animations
    initializeUIAnimations();

    // Initialize table row click handlers
    initializeTableClickHandlers();

    // Initialize user management modal handlers
    initializeUserModals();

    // Initialize form field dependencies
    initializeFormDependencies();

    // Initialize auto-fill functionality
    initializeAutoFillHandlers();
});

/* =====================================================
   DOM CONTENT LOADED EVENT HANDLERS
===================================================== */
document.addEventListener("DOMContentLoaded", function () {
    // Initialize UI components
    initializeUIComponents();

    // Initialize form handlers
    initializeFormHandlers();

    // Initialize modal cleanup handlers
    initializeModalCleanup();
});

/* =====================================================
   UI INITIALIZATION FUNCTIONS
===================================================== */
function initializeUIAnimations() {
    // Fade in content wrapper with smooth transition
    const wrapper = document.querySelector(".app-content-wrapper");
    if (wrapper) {
        setTimeout(() => {
            wrapper.classList.add("visible");
        }, 100);
    }
}

function initializeUIComponents() {
    // Initialize tab navigation for mobile
    const tabLinks = document.querySelectorAll('#profileTab a[data-bs-toggle="tab"]');
    const tabContent = document.getElementById("profileTabContent");

    tabLinks.forEach((link) => {
        link.addEventListener("shown.bs.tab", function () {
            if (window.innerWidth <= 768 && tabContent) {
                tabContent.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                });
            }
        });
    });
}

/* =====================================================
   TABLE CLICK HANDLERS
===================================================== */
function initializeTableClickHandlers() {
    // Item Locations table row click handler
    $("#itemloc-table tbody").on("click", "tr", function () {
        const $row = $(this);
        populateItemLocationModal($row);
    });

    // Users table row click handler
    $("#users-table tbody").on("click", "tr", function () {
        const $row = $(this);
        populateUserViewModal($row);
    });
}

function populateItemLocationModal($row) {
    $("#view-itemloc-job").text($row.data("job") || "");
    $("#view-itemloc-desc").text($row.data("desc") || "");
    $("#view-itemloc-qty").text($row.data("qty") || "0");
    $("#view-itemloc-um").text($row.data("um") || "");
    $("#view-itemloc-site-desc").text($row.data("rssite_desc") || "");
    $("#viewItemLocModal").modal("show");
}

function populateUserViewModal($row) {
    // Store row data for edit modal
    $("#btnEditUser").data({
        userid: $row.data("userid"),
        name: $row.data("name"),
        email: $row.data("email"),
        site: $row.data("site"),
        site_desc: $row.data("site_desc"),
        level: $row.data("level"),
        gender: $row.data("gender"),
        department: $row.data("department"),
        position: $row.data("position"),
        profile: $row.data("profile"),
        section: $row.data("section")
    });

    // Populate view modal fields
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
}

/* =====================================================
   USER MODAL HANDLERS
===================================================== */
function initializeUserModals() {
    // Edit user button handler
    $("#btnEditUser").on("click", function () {
        const data = $(this).data();
        populateEditUserModal(data);
    });

    // Profile picture preview handler
    $("#edit_profile_pic").on("change", function (e) {
        previewProfilePicture(this);
    });
}

function populateEditUserModal(data) {
    // Set values in edit modal
    $("#edit-user-label-name").text(data.name || data.userid);
    $("#edit-user-profile-preview").attr("src", data.profile);
    $("#edit-rssite").val(data.site);
    $("#edit-userid").val(data.userid);
    $("#edit-userid-hidden").val(data.userid);
    $("#edit-name").val(data.name);
    $("#edit-email").val(data.email);
    $("#edit-gender").val(data.gender);
    $("#edit-level").val(data.level);
    $("#edit-password").val("");
    $("#edit-existing-profile-pic").val(data.profile);
    $("#edit-department").val(data.department || "");
    $("#edit-position").val(data.position || "");
    $("#edit-section").val(data.section || "");

    // Show edit modal after hiding view modal
    $("#viewUserModal").modal("hide");
    $("#editUserModal").modal("show");
}

function previewProfilePicture(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $("#edit-user-profile-preview").attr("src", e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

/* =====================================================
   FORM DEPENDENCIES
===================================================== */
function initializeFormDependencies() {
    // Initialize site-based filtering for warehouse and bay selects
    const siteSelect = document.getElementById("rssite");
    const whseSelect = document.getElementById("rswhse");
    const baySelect = document.getElementById("rsbaynum");

    if (siteSelect && whseSelect && baySelect) {
        siteSelect.addEventListener("change", function () {
            handleSiteChange(this.value, whseSelect, baySelect);
        });

        // Apply initial filter if site is pre-selected
        if (siteSelect.value) {
            handleSiteChange(siteSelect.value, whseSelect, baySelect);
        }
    }

    // Initialize lot auto-fill for receiving
    const jobcoInput = document.getElementById("jobcoreceive");
    const lotInput = document.getElementById("lot");
    if (jobcoInput && lotInput) {
        jobcoInput.addEventListener("input", function () {
            lotInput.value = this.value ? this.value + "-1" : "";
        });
    }
}

function handleSiteChange(siteValue, whseSelect, baySelect) {
    // Reset dependent selects
    whseSelect.selectedIndex = 0;
    baySelect.selectedIndex = 0;

    // Filter options based on selected site
    filterOptionsBySite(whseSelect, siteValue);
    filterOptionsBySite(baySelect, siteValue);
}

function filterOptionsBySite(select, siteValue) {
    if (!select) return;

    Array.from(select.options).forEach((option) => {
        if (!option.value) return;

        const shouldShow = option.getAttribute("data-site") === siteValue;
        option.style.display = shouldShow ? "" : "none";
    });

    // Reset selection if current option is now hidden
    const currentOption = select.options[select.selectedIndex];
    if (currentOption && currentOption.style.display === "none") {
        select.selectedIndex = 0;
    }
}

/* =====================================================
   AUTO-FILL HANDLERS
===================================================== */
function initializeAutoFillHandlers() {
    // Goods Receiving auto-fill
    $("#jobcoreceive").on("input", function () {
        handleReceivingJobInput($(this).val());
    });

    // Goods Dispatching auto-fill
    $("#jobcodispatch").on("input", function () {
        handleDispatchingJobInput($(this).val());
    });
}

function handleReceivingJobInput(job) {
    const rssite = $("#rssite").val() || $("input[name='rssite']").val();
    const lotInput = document.getElementById("lot");

    if (!job || !rssite) {
        clearReceivingFields();
        return;
    }

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
                populateReceivingFields(data[0], job);
            } else {
                clearReceivingFields();
            }
        },
        error: function () {
            clearReceivingFields();
        }
    });
}

function handleDispatchingJobInput(job) {
    const rssite = $("#rssite").val() || $("input[name='rssite']").val();
    const lotInput = document.getElementById("lot");

    if (!job || !rssite) {
        clearDispatchingFields();
        return;
    }

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
                populateDispatchingFields(data, job);
            } else {
                clearDispatchingFields();
            }
        },
        error: function () {
            clearDispatchingFields();
        }
    });
}

function populateReceivingFields(data, job) {
    $("#item").val(data.item || "");
    $("#um").val(data.u_m || "");

    // Build item description
    let itemdesc = "";
    if (data.description && data.Uf_itemdesc_ext) {
        itemdesc = data.description + " - " + data.Uf_itemdesc_ext;
    } else if (data.description) {
        itemdesc = data.description;
    } else if (data.Uf_itemdesc_ext) {
        itemdesc = data.Uf_itemdesc_ext;
    }
    $("#desc").val(itemdesc);

    // Format pallet size
    let palletSize = formatNumericValue(data.Uf_Item_PalletSize);
    $("#pallet_size").val(palletSize);

    // Set lot value
    document.getElementById("lot").value = job + "-1";
}

function populateDispatchingFields(data, job) {
    $("#rswhse").val(data.rswhse || "");
    $("#item").val(data.item || "");
    $("#um").val(data.um || "");
    $("#desc").val(data.desc || "");

    // Set lot value
    document.getElementById("lot").value = job + "-1";
}

function clearReceivingFields() {
    $("#item, #um, #desc, #pallet_size").val("");
    const lotInput = document.getElementById("lot");
    if (lotInput) lotInput.value = "";
}

function clearDispatchingFields() {
    $("#rswhse, #item, #um, #desc").val("");
    const lotInput = document.getElementById("lot");
    if (lotInput) lotInput.value = "";
}

function formatNumericValue(value) {
    const num = parseFloat(value);
    if (isNaN(num) || num === 0) {
        return "0";
    } else if (num % 1 === 0) {
        return num.toString();
    } else {
        return num.toFixed(2).replace(/\.00$/, "");
    }
}

/* =====================================================
   FORM HANDLERS
===================================================== */
function initializeFormHandlers() {
    // Change Password Form
    const changePasswordForm = document.getElementById("changePasswordForm");
    if (changePasswordForm) {
        changePasswordForm.addEventListener("submit", handleChangePasswordSubmit);
    }
}

function handleChangePasswordSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const msg = document.getElementById("changePasswordMsg");
    msg.innerHTML = "";

    const formData = new FormData(form);

    fetch(form.action, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value,
            Accept: "application/json",
        },
        body: formData,
    })
    .then(async (response) => {
        const data = await response.json();

        if (response.ok) {
            showPasswordChangeSuccess(msg, data.message);
            form.reset();
        } else {
            const errorMsg = data.message || Object.values(data.errors || {}).join("<br>") || "An error occurred.";
            showPasswordChangeError(msg, errorMsg);
        }
    })
    .catch(() => {
        showPasswordChangeError(msg, "Server error. Please try again.");
    });
}

function showPasswordChangeSuccess(msg, message) {
    msg.innerHTML = `<div id="changePasswordAlert" class="alert alert-success">${message}</div>`;
    setTimeout(() => fadeOutAlert("changePasswordAlert"), 1500);
}

function showPasswordChangeError(msg, message) {
    msg.innerHTML = `<div id="changePasswordAlert" class="alert alert-danger">${message}</div>`;
    setTimeout(() => fadeOutAlert("changePasswordAlert"), 2500);
}

function fadeOutAlert(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.style.transition = "opacity 0.7s";
        alert.style.opacity = "0";
        setTimeout(() => alert.remove(), 700);
    }
}

/* =====================================================
   MODAL CLEANUP
===================================================== */
function initializeModalCleanup() {
    // Clean up focus when modals are hidden
    $(
        "#viewUserModal, #editUserModal, #viewWarehouseModal, #editWarehouseModal, #viewBayModal, #viewRackModal"
    ).on("hide.bs.modal", function () {
        if (document.activeElement && this.contains(document.activeElement)) {
            document.activeElement.blur();
        }
    });
}
