<footer class="main-footer d-flex justify-content-between align-items-center">
    <strong>
        &copy; {{ date('Y') }}
        <a href="http://www.printwell.com.ph" class="pi-link">Printwell, Inc.</a>
    </strong>
    <div class="d-none d-sm-inline mx-2">
        <a href="{{ url('/irms') }}">IRMS</a>
    </div>
</footer>

@push('styles')
    <style>
        .main-footer {
            background-color: #f8f9fa !important;
            color: #212529;
            padding: 1rem 1.5rem;
        }

        .main-footer a.pi-link {
            color: #007bff;
            text-decoration: none;
        }

        .main-footer a.pi-link:hover {
            text-decoration: underline;
        }
    </style>
@endpush
