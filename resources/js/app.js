import 'bootstrap';

import 'admin-lte';

$(document).ready(function () {
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
    });
    $('#baylocSearch').on('keyup', function () {
        bayLocationTable.search(this.value).draw();
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

    $('.dataTables_filter').hide();
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


