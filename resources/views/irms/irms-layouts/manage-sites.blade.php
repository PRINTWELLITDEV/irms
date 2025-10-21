@extends('irms.irms-partials.app')
@section('title', 'IRMS Manage Sites')
@section('content')

<main class="app-main">
    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Sites</h1>
                        @if(session('success'))
                            <div id="alerts" class="alert alert-success py-1 px-3 mb-0" style="transition: opacity 0.7s;">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if($errors->any())
                            <div id="alerts" class="alert alert-danger py-1 px-3 mb-0" style="transition: opacity 0.7s;">{{ $errors->first() }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <button type="button" id="btnAddSite" class="btn btn-success d-flex align-items-center me-2"
                                        data-bs-toggle="modal" data-bs-target="#addSiteModal">
                                        <i class="bi bi-geo-alt d-none d-sm-inline me-2"></i>
                                        <span class="d-none d-sm-inline">Add Site</span>
                                        <i class="bi bi-geo-alt d-inline d-sm-none"></i>
                                    </button>

                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="siteSearch" class="form-control"
                                            placeholder="Search sites...">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    </div>
                                </div>

                                <div class="table-responsive table-view">
                                    <table id="sites-table" class="table table-striped table-hover align-middle display">
                                        <thead>
                                            <tr>
                                                <th>Site Code</th>
                                                <th>Site Description</th>
                                                <th>Address</th>
                                                <th>Link</th>
                                                <th>Logo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sites as $site)
                                                @php
                                                    $logo_pic_url = $site->logo_pic_url ?? 'uploads/user-profile/noprofile.png';
                                                    if (!file_exists(public_path($logo_pic_url)) || !$logo_pic_url) {
                                                        $logo_pic_url = 'uploads/user-profile/noprofile.png';
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{ $site->rssite }}</td>
                                                    <td>{{ $site->rssite_desc }}</td>
                                                    <td>{{ $site->address }}</td>
                                                    <td>
                                                        @if($site->site_link)
                                                            <a href="{{ $site->site_link }}" target="_blank">{{ $site->site_link }}</a>
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($site->logo_pic_url)
                                                            <img src="{{ asset($logo_pic_url) }}" alt="Site Logo" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div> <!-- /.card-body -->
                        </div> <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Add Site Modal -->
<div class="modal fade" id="addSiteModal" tabindex="-1" aria-labelledby="addSiteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addSiteForm" method="POST" action="{{ route('sites.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addSiteModalLabel">Add New Site</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="logo_pic_url" class="form-label">Logo Image</label>
                        <input type="file" class="form-control" id="logo_pic_url" name="logo_pic_url" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="rssite" class="form-label">Site Code</label>
                        <input type="text" class="form-control" id="rssite" name="rssite" required maxlength="8">
                    </div>
                    <div class="mb-3">
                        <label for="rssite_desc" class="form-label">Site Description</label>
                        <input type="text" class="form-control" id="rssite_desc" name="rssite_desc" required maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" maxlength="255"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="site_link" class="form-label">Site Link (URL)</label>
                        <input type="url" class="form-control" id="site_link" name="site_link" maxlength="255" placeholder="https://example.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Site</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection