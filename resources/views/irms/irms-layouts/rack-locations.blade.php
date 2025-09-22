@extends('irms.irms-partials.app')

@section('title', 'IRMS Rack Locations')

@section('content')
<div class="wrapper">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Rack Locations</h1>
                        @if(session('success'))
                            <div id="success-alert" class="alert alert-success py-1 px-3 mb-0" style="transition: opacity 0.7s;">
                                {{ session('success') }}
                            </div>
                        @endif
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
                                    <input type="text" id="rackSearch" class="form-control" placeholder="Search rack location...">
                                    <span class="input-group-text">
                                        <i class="bi bi-search"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="rackTable" class="table table-striped table-bordered table-hover align-middle">
                                    <thead class="table-dark text-center">
                                    <tr>
                                        @if(auth()->user()->userid === 'sa')
                                        <th>Site</th>
                                        @endif
                                        <th>Warehouse</th>
                                        <th>Bay No.</th>
                                        <th>Location</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Create Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($racklocs as $rsloc)
                                        <tr>
                                            @if(auth()->user()->userid === 'sa')
                                            <td class="text-center align-middle">
                                                <img src="{{ asset($rsloc->logo_pic_url) }}" alt="logo" class="mx-auto d-block" width="40" height="40" style="object-fit:contain;vertical-align:middle;">
                                            </td>
                                            @endif
                                            <td>{{ $rsloc->rswhse }}</td>
                                            <td>{{ $rsloc->rsbaynum }}</td>
                                            <td>{{ $rsloc->rsloc }}</td>
                                            <td>{{ $rsloc->rsdesc }}</td>
                                            <td>{{ number_format($rsloc->qty, 0) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($rsloc->createdate)->format('d M Y | h:i A') }}</td>
                                        </tr>
                                    @empty
                                    @endforelse
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

<!-- Add Rack Location Modal -->
<div class="modal fade" id="addRackModal" tabindex="-1" aria-labelledby="addRackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-l">
        <div class="modal-content">
            <form action="{{ route('racklocations.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="addRackModalLabel">Add Rack Location</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                    <div class="mb-3">
                        @if(auth()->user()->userid === 'sa')
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-building"></i></span>
                            <select name="rssite" id="rssite" class="form-select" required>
                                <option disabled selected>Select Site</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->rssite }}" {{ old('rssite') == $site->rssite ? 'selected' : '' }}>
                                        {{ $site->rssite_desc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @else
                            <input type="hidden" name="rssite" id="rssite" value="{{ auth()->user()->site }}" readonly>
                        @endif
                        @error('rssite') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-archive"></i></span>
                            <select name="rswhse" id="rswhse" class="form-select" required>
                                <option disabled selected>Select Warehouse</option>
                                @foreach($warehouses as $whse)
                                    @if(auth()->user()->userid === 'sa' || $whse->rssite === auth()->user()->rssite)
                                        <option value="{{ $whse->rswhse }}" data-site="{{ $whse->rssite }}">
                                            {{ $whse->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        @error('rswhse') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-hash"></i></span>
                            <select name="rsbaynum" id="rsbaynum" class="form-select" required>
                                <option disabled selected>Select Bay Number</option>
                                @foreach($baynums as $bay)
                                    @if(auth()->user()->userid === 'sa' || $bay->rssite === auth()->user()->rssite)
                                        <option value="{{ $bay->rsbaynum }}" data-site="{{ $bay->rssite }}">
                                            {{ $bay->rsbaynum }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        @error('rsbaynum') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-box"></i></span>
                            <input type="text" class="form-control" id="rsloc" name="rsloc" value="{{ old('rsloc') }}" required maxlength="15" placeholder="Rack Location">
                        </div>
                        @error('rsloc') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                            <input type="text" class="form-control" id="rsdec" name="rsdec" value="{{ old('rsdec') }}" maxlength="13" placeholder="Description">
                        </div>
                        @error('rsdec') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
