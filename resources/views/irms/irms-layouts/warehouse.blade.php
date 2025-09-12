@extends('irms.irms-partials.app')

@section('title', 'IRMS Warehouse')

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <h1>Warehouse</h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <!-- Card Header -->
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="w-50">
                                    <input type="text" id="searchWarehouseInput" class="form-control"
                                        placeholder="Search Warehouse">
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body">
                                <table id="warehouse-table" class="table table-striped table-bordered table-hover">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th>Site</th>
                                            <th>Warehouse</th>
                                            <th>Description</th>
                                            <th>Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($warehouses as $whse)
                                            <tr>
                                                <td class="align-middle" style="width: 4rem;">{{ $whse->rssite }}</td>
                                                <td class="align-middle" style="width: 7rem;">{{ $whse->rswhse }}</td>
                                                <td class="align-middle">{{ $whse->name }}</td>
                                                <td class="align-middle">{{ $whse->addr }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-info btn-sm" title="Settings">
                                                        <i class="bi bi-gear"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No data found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- End Card Body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include DataTables CSS -->
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    @endpush

    <!-- Include jQuery and DataTables JS -->
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function () {
                const table = $('#warehouse-table').DataTable({
                    paging: true,
                    info: true,
                    lengthChange: false
                });

                // Bind custom search input
                $('#searchWarehouseInput').on('keyup', function () {
                    table.search(this.value).draw();
                });
            });
        </script>
    @endpush
@endsection
