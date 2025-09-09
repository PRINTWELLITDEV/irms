@extends('partials.app')

@section('title', 'IRMS Login')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-4">IRMS Login</h3>

        <!-- Error Message -->
        <div id="error-message" class="alert alert-danger d-none"></div>

        <form id="login-form">
            @csrf

            <!-- Site -->
            <div class="mb-3">
                <label for="rssite" class="form-label">Site</label>
                <select name="rssite" id="rssite" class="form-control" required>
                    <option disabled selected>Select Site</option>
                    @foreach ($sites as $site)
                        <option value="{{ $site->rssite }}">{{ $site->rssite_desc }}</option>
                    @endforeach
                </select>
            </div>

            <!-- User ID -->
            <div class="mb-3">
                <label for="userid" class="form-label">User ID</label>
                <input type="text" name="userid" id="userid" class="form-control" required>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById("login-form").addEventListener("submit", async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    // Clear previous errors
    const errorBox = document.getElementById("error-message");
    errorBox.classList.add("d-none");
    errorBox.innerHTML = "";

    try {
        const response = await fetch("{{ route('login') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": formData.get("_token"),
                "Accept": "application/json",
            },
            body: formData,
        });

        const data = await response.json();

        if (response.ok && data.success) {
            window.location.href = data.redirect;
        } else {
            errorBox.innerHTML = data.message || "Invalid credentials.";
            errorBox.classList.remove("d-none");
        }

    } catch (error) {
        errorBox.innerHTML = "Something went wrong. Please try again.";
        errorBox.classList.remove("d-none");
    }
});
</script>
@endsection
