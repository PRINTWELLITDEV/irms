@extends('irms.irms-partials.app')

@section('title', 'IRMS Rack Locations')

@section('content')
<div class="wrapper">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3">
                        <h1 class="d-inline-block mb-0">Rack Locations</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button type="button" id="btnAddRack" class="btn btn-success d-flex align-items-center"
                                        data-bs-toggle="modal" data-bs-target="#addRackModal">
                                    <i class="bi bi-plus-circle-fill d-none d-sm-inline me-2"></i>
                                    <span class="d-none d-sm-inline">Add Rack</span>
                                    <i class="bi bi-plus-circle-fill d-inline d-sm-none"></i>
                                </button>

                                <div class="input-group" style="max-width: 300px;">
                                    <input type="text" id="rackSearch" class="form-control" placeholder="Search rack locations...">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table display compact cell-border hover stripe ui celled table" id="users-table">
                                    <thead>
                                    <tr>
                                        <th>Site</th>
                                        <th>Warehouse</th>
                                        <th>Bay No.</th>
                                        <th>Location</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>FPC</td>
                                        <td>FBIC-BLDG#2</td>
                                        <td>2</td>
                                        <td>Mandaluyong City</td>
                                        <td>sdadsa</td>
                                        <td>dasdas</td>
                                        <td>sads</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Rack Modal -->
<div class="modal fade" id="addRackModal" tabindex="-1" aria-labelledby="addRackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRackModalLabel">Add Rack Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addRackForm">
                    <div class="mb-3">
                        <label for="rackLocation" class="form-label">Rack Location</label>
                        <input type="text" class="form-control" id="rackLocation" required>
                    </div>
                    <div class="mb-3">
                        <label for="rackCapacity" class="form-label">Rack Capacity</label>
                        <input type="number" class="form-control" id="rackCapacity" required>
                    </div>
                    <div class="mb-3">
                        <label for="rackDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="rackDescription" rows="3"></textarea>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Add Rack</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // JavaScript code for handling rack locations

    document.addEventListener('DOMContentLoaded', function () {
        const rackTable = document.getElementById('rackTable').getElementsByTagName('tbody')[0];

        // Function to fetch and display rack locations
        function loadRackLocations() {
            // Clear the table body
            rackTable.innerHTML = '';

            // Fetch rack locations data (replace with your actual data source)
            const rackLocations = [
                { id: 1, location: 'Rack 1', capacity: 42 },
                { id: 2, location: 'Rack 2', capacity: 36 },
                { id: 3, location: 'Rack 3', capacity: 48 }
            ];

            // Populate the table with data
            rackLocations.forEach(rack => {
                const row = rackTable.insertRow();
                row.innerHTML = `
                    <td>${rack.id}</td>
                    <td>${rack.location}</td>
                    <td>${rack.capacity}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editRack(${rack.id})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteRack(${rack.id})">Delete</button>
                    </td>
                `;
            });
        }

        // Call the function to load rack locations on page load
        loadRackLocations();

        // Handle form submission for adding a new rack
        document.getElementById('addRackForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Get form data
            const location = document.getElementById('rackLocation').value;
            const capacity = document.getElementById('rackCapacity').value;
            const description = document.getElementById('rackDescription').value;

            // TODO: Add code to save the new rack location (e.g., send to server)

            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addRackModal'));
            modal.hide();

            // Reload the rack locations
            loadRackLocations();
        });
    });

    // Edit and delete functions (to be implemented)
    function editRack(id) {
        alert('Edit rack with ID: ' + id);
    }

    function deleteRack(id) {
        if (confirm('Are you sure you want to delete this rack location?')) {
            alert('Delete rack with ID: ' + id);
        }
    }
</script>
@endsection
