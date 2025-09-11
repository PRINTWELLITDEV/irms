@extends('irms.irms-partials.app')
@section('title', 'IRMS Dashboard')

@section('content')
    <div class="wrapper">
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <h1>Manage Users</h1>
            </div>
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-12 text-center mt-2 mb-5">
                            <!-- Header Row (3 columns) -->
                            <div class="row text-start d-flex justify-center align-items-center flex-wrap py-3">
                                <div class="col">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Order By
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" class="dropdown-item">Name</a></li>
                                            <li><a href="#" class="dropdown-item">User ID</a></li>
                                            <li><a href="#" class="dropdown-item">Recently Added</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col">
                                    <form id="searchForm" method="GET" action="{{ route('rsusers.index') }}" class="input-group d-flex justify-content-center align-items-center">
                                        <div class="w-50">
                                            <input id="search" name="search" type="text" class="form-control" value="{{ request('search') }}" autocomplete="off" />
                                        </div>
                                        <button type="submit" class="btn btn-warning ms-2">Search</button>
                                    </form>
                                </div>
                                <div class="col text-end">
                                    <button type="button" id="btnAddUsername" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#btnAddUser">
                                        Add User
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">Site</th>
                                            <th scope="col">User ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">User Type</th>
                                            <th scope="col">Level</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="users-table-body">
                                        @forelse($users as $user)
                                            <tr>
                                                <td>{{ $user->rssite }}</td>
                                                <td>{{ $user->userid }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->user_type }}</td>
                                                <td>{{ $user->level }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary">Edit</button>
                                                    <button class="btn btn-sm btn-danger">Delete</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">No data available</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <div class="row">
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col">

                                            </div>
                                        </div>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->

    <div class="modal fade" id="btnAddUser" aria-labelledby="addUserLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addUserLabel">Add Users</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success">Add Users</button>
                </div>
            </div>
        </div>
    </div>

    <script>


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
            return function(...args) {
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
            searchInput.addEventListener('input', debounce(function() {
                fetchUsers(this.value);
            }, 400));
        }

        // Optional: prevent form submit on enter
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchUsers(searchInput.value);
            });
        }
    </script>
@endsection

