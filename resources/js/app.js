import 'bootstrap';

import 'admin-lte';

$(document).ready(function () {
    // Data tables
    // Users table
    const usersTable = $('#users-table').DataTable({
        paging: true,
        info: true,
        lengthChange: false,
        searching: true,
        pageLength: 10,
        language: {
            emptyTable: "No data available"
        },
        // columnDefs: [
        //     { orderable: false, targets: [4] } // 0: Profile, 6: Action
        // ],
    });
    $('#userSearch').on('keyup', function () {
        usersTable.search(this.value).draw();
    });

    // Warehouse table
    const warehouseTable = $('#warehouse-table').DataTable({
        paging: true,
        info: true,
        lengthChange: false,
        searching: true,
        pageLength: 10,
        language: {
            emptyTable: "No warehouses found"
        },
        // columnDefs: [
        //     { orderable: false, targets: [0] }
        // ]
    });
    $('#whseSearch').on('keyup', function () {
        warehouseTable.search(this.value).draw();
    });

    // Bay Location table
    const bayLocationTable = $('#bayloc-table').DataTable({
        paging: true,
        info: true,
        lengthChange: false,
        searching: true,
        pageLength: 10,
        language: {
            emptyTable: "No bay locations found"
        },
        // columnDefs: [
        //     { orderable: false, targets: [0, 4] }
        // ]
    });
    $('#baylocSearch').on('keyup', function () {
        bayLocationTable.search(this.value).draw();
    });

    // Hide filter boxes initially
    $('.dataTables_filter').hide();

    // Show user view modal when a row is clicked
    $('#users-table tbody').on('click', 'tr', function () {
        const $row = $(this);
        $('#view-user-profile').attr('src', $row.data('profile'));
        $('#view-user-name').text($row.data('name'));
        $('#view-user-id').text($row.data('userid'));
        $('#view-user-email').text($row.data('email'));
        $('#view-user-site_desc').text($row.data('site_desc') || '-');
        $('#view-user-level').text($row.data('level'));
        const gender = $row.data('gender');
        $('#view-user-gender').text(
            gender ? gender.charAt(0).toUpperCase() + gender.slice(1).toLowerCase() : '-'
        );
        $('#view-user-create_date').text($row.data('create_date'));
        $('#view-user-label-name').text($row.data('userid'));
        $('#viewUserModal').modal('show');
    });

    // Show warehouse view modal when a row is clicked
    $('#warehouse-table tbody').on('click', 'tr', function () {
        const $row = $(this);
        // Store current row data for use in edit modal
        $('#editWarehouseBtn')
            .data('rssite', $row.data('rssite'))
            .data('rswhse', $row.data('rswhse'))
            .data('name', $row.data('name'))
            .data('addr', $row.data('addr'));
        // Fill view modal
        $('#view-warehouse-site-desc').text($row.data('rssite_desc') || '-');
        $('#view-warehouse-code').text($row.data('rswhse') || '-');
        $('#view-warehouse-name').text($row.data('name') || '-');
        $('#view-warehouse-addr').text($row.data('addr') || '-');
        $('#view-warehouse-label-name').text($row.data('name') || '-');
        $('#viewWarehouseModal').modal('show');
    });

    // When settings button is clicked
    $('#warehouse-table').on('click', '.btn-settings', function () {
        const rssiteDesc = $(this).data('rssite_desc');
        const rswhse = $(this).data('rswhse');
        const name = $(this).data('name');
        const addr = $(this).data('addr');

        $('#ws-site').text(rssiteDesc || '');
        $('#ws-whse').text(rswhse || '');
        $('#ws-name').text(name || '');
        $('#ws-addr').text(addr || '');

        // Store data for edit modal on the Edit button if needed
        $('#editWarehouseBtn')
            .data('rssite', $(this).data('rssite'))
            .data('rswhse', rswhse)
            .data('name', name)
            .data('addr', addr);
    });

    // When Edit modal is about to be shown, set values and select the correct site
    $('#editWarehouseModal').on('show.bs.modal', function () {
        const editBtn = $('#editWarehouseBtn');
        const rssite = editBtn.data('rssite');
        const rswhse = editBtn.data('rswhse');
        const name = editBtn.data('name');
        const addr = editBtn.data('addr');

        // Set select value for site
        $('#edit-rssite').val(rssite);

        // Set input values
        $('#edit-rswhse').val(rswhse);
        $('#edit-name').val(name);
        $('#edit-addr').val(addr);

        // Set hidden original keys
        $('#edit-orig-rssite').val(rssite);
        $('#edit-orig-rswhse').val(rswhse);
    });

    // When Edit button in view modal is clicked, show edit modal with values
    $('#editWarehouseBtn').on('click', function () {
        const rssite = $(this).data('rssite');
        const rswhse = $(this).data('rswhse');
        const name = $(this).data('name');
        const addr = $(this).data('addr');
        // Set select value for site
        $('#edit-rssite').val(rssite);
        // Set input values
        $('#edit-rswhse').val(rswhse);
        $('#edit-name').val(name);
        $('#edit-addr').val(addr);
        // Set hidden original keys
        $('#edit-orig-rssite').val(rssite);
        $('#edit-orig-rswhse').val(rswhse);
    });

});

document.addEventListener('DOMContentLoaded', function () {

    const wrapper = document.querySelector('.content-wrapper');
    if (wrapper) {
        setTimeout(() => {
            wrapper.classList.add('visible');
        }, 100);
    }

    const alert = document.getElementById('success-alert');
    if (alert) {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 700); // matches the transition duration
        }, 3000); // show for 3 seconds
    }



});


