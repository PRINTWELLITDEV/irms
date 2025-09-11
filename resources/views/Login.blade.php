@extends('partials.app')

@section('title', 'IRMS Login')

@section('content')
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg p-4 mb-7" style="max-width: 400px; width: 100%;">
            <h1 class="text-center mb-4">IRMS</h1>

            <!-- Error Message -->
            <div id="error-message" class="alert alert-danger d-none"></div>

            <form id="login-form">
                @csrf

                <!-- Site -->
                <!-- <div class="mb-3">
                    <label for="rssite" class="form-label">Site</label>
                    <select name="rssite" id="rssite" class="form-control" required>
                        <option disabled selected>Select Site</option>
                        @foreach ($sites as $site)
                            <option value="{{ $site->rssite }}">{{ $site->rssite_desc }}</option>
                        @endforeach
                    </select>
                </div> -->

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

                <div class="d-grid mt-5 mb-4">
                    <button type="submit" id="btn_login" class="btn btn-primary">
                        <span id="buttonText">Log In</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" id="loadingSpinner"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        //Loading Spinner

        document.getElementById('btn_login').addEventListener('click', function () {
            // Show spinner and hide text
            document.getElementById('loadingSpinner').classList.remove('d-none');
            document.getElementById('buttonText').classList.add('d-none');

            // Simulate an action (e.g., an AJAX request)
            setTimeout(() => {
                // Hide spinner and show text when done
                document.getElementById('loadingSpinner').classList.add('d-none');
                document.getElementById('buttonText').classList.remove('d-none');
            }, 3000);
        });

        //Login
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

@section('footer')
    @include('partials.footer')
@endsection
