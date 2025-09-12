import './bootstrap';

import 'bootstrap'; // ✅ This pulls in bootstrap.js + Popper

document.addEventListener('DOMContentLoaded', function () {
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

    const searchInput = document.getElementById('search');
    const usersTableBody = document.getElementById('users-table-body');
    const searchForm = document.getElementById('searchForm');

    function fetchUsers(query) {
        fetch(`{{ route('rsusers.index') }}?search=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                let html = '';
                if (data.users.length > 0) {
                    data.users.forEach(user => {
                        html += `
                                    <tr>
                                        <td>${user.rssite}</td>
                                        <td>${user.userid}</td>
                                        <td>${user.name}</td>
                                        <td>${user.email}</td>
                                        <td>${user.user_type}</td>
                                        <td>${user.level}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary">Edit</button>
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </td>
                                    </tr>
                                `;
                    });
                } else {
                    html = `<tr>
                                <td colspan="6" class="text-center text-muted">No data available</td>
                            </tr>`;
                }
                usersTableBody.innerHTML = html;
            });
    }

    if (searchInput && usersTableBody) {
        searchInput.addEventListener('input', debounce(function () {
            fetchUsers(this.value);
        }, 400));
    }

    // Optional: prevent form submit on enter
    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            e.preventDefault();
            fetchUsers(searchInput.value);
        });
    }
});


