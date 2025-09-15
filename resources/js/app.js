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

    $('.dataTables_filter').hide();
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-lte-toggle="sidebar"]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector('.app-wrapper').classList.toggle('sidebar-collapsed');
        });
    });

    // Fullscreen toggle
    const fullscreenBtn = document.querySelector('[data-lte-toggle="fullscreen"]');
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
                fullscreenBtn.querySelector('[data-lte-icon="maximize"]').style.display = 'none';
                fullscreenBtn.querySelector('[data-lte-icon="minimize"]').style.display = '';
            } else {
                document.exitFullscreen();
                fullscreenBtn.querySelector('[data-lte-icon="maximize"]').style.display = '';
                fullscreenBtn.querySelector('[data-lte-icon="minimize"]').style.display = 'none';
            }
        });

        document.addEventListener('fullscreenchange', function () {
            if (!document.fullscreenElement) {
                fullscreenBtn.querySelector('[data-lte-icon="maximize"]').style.display = '';
                fullscreenBtn.querySelector('[data-lte-icon="minimize"]').style.display = 'none';
            }
        });
    }
    // Fade-in effect for content wrapper
    const wrapper = document.querySelector('.content-wrapper');
    if (wrapper) {
        setTimeout(() => {
            wrapper.classList.add('visible');
        }, 100); // slight delay for effect
    }

    //Modals Script Event Listener
    const btnTriggerModals = document.getElementById('btnAddUsername');
    const AddUserModals = document.getElementById('btnAddUser');

    btnTriggerModals.addEventListener('shown.bs.modal', () => {
        AddUserModals.focus()
    }
    )

    //Ajax for the Search Function
    function debounce(fn, delay) {
        let timer = null;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => fn.apply(this, args), delay);
        };
    }



});


