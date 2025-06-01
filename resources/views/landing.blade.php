@extends('layouts.simple')

@section('content')
    <!-- Page Content -->
    <div class="hero-static d-flex align-items-center"
        style="background-image: url('{{ asset('media/backgrounds/bg1.jpg') }}'); background-size: cover; background-repeat: no-repeat; background-position: center center;">
        <div class="content w-50">
            <div class="row justify-content-center ">
                     <div class="col-md-7 col-lg-7 col-xl-7" style="opacity: 0.95;">
                    <!-- Sign In Block -->
                    <div class="block block-rounded rounded-0 mb-0" style="opacity: 0.96;">
                        <div class="block-header block-header-default" style="opacity: 0.96;">
                            <h3 class="block-title text-primary">Sign In</h3>
                            <div class="block-options">
                                <a class="btn-block-option text-danger fs-sm" href="{{ route('password.request') }}">
                                    <i class="fa fa-key"></i>
                                    Forgot Password?</a>
                            </div>
                        </div>
                        <div class="block-content" style="opacity: 0.93;">
                            <div class="p-1 px-lg-1 px-xxl-1 py-lg-1">
                                {{-- <div class="text-center mb-3">
                                    <img src="{{ asset('media/logo.jpeg') }}" alt="SudEnergy Logo" class="img-fluid"
                                        style="max-height: 80px;">
                                </div> --}}
                                {{-- <p class="fw-medium text-muted text-center">
                                    Welcome, please login.
                                </p> --}}

                                <!-- Sign In Form -->
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <!-- Email Address -->
                                    <div class="mb-2">
                                        <label for="email" class="form-label">{{ __('Email') }}</label>
                                        <div class="input-group input-group-lg mt-1">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fa fa-envelope text-muted"></i>
                                            </span>
                                            <input id="email" class="form-control form-control-alt border-start-0"
                                                type="email" name="email" value="{{ old('email') }}" required autofocus
                                                autocomplete="username" placeholder="Email">
                                        </div>
                                        @if ($errors->has('email'))
                                            <div class="text-danger mt-2">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-2">
                                        <label for="password" class="form-label">{{ __('Password') }}</label>
                                        <div class="input-group input-group-lg mt-1">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fa fa-lock text-muted"></i>
                                            </span>
                                            <input id="password" class="form-control form-control-alt border-start-0"
                                                type="password" name="password" required autocomplete="current-password"
                                                placeholder="Password">
                                            <span class="input-group-text bg-white border-start-0" style="cursor:pointer;"
                                                onclick="togglePasswordVisibility()">
                                                <i class="fa fa-eye-slash text-muted" id="togglePasswordIcon"></i>
                                            </span>

                                        </div>
                                        @if ($errors->has('password'))
                                            <div class="text-danger mt-2">{{ $errors->first('password') }}</div>
                                        @endif
                                    </div>

                                    <!-- Remember Me -->
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input id="remember_me" type="checkbox"
                                                class="form-check-input rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                name="remember">
                                            <label class="form-check-label" for="remember_me">
                                                {{ __('Remember me') }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary btn-block w-100" id="signInBtn">
                                            <span id="signInBtnSpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                            <span id="signInBtnText"><i class="fa fa-fw fa-sign-in-alt me-1 opacity-80"></i> {{ __('Sign In') }}</span>
                                        </button>

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                const form = document.querySelector('form[action="{{ route('login') }}"]');
                                                const btn = document.getElementById('signInBtn');
                                                const spinner = document.getElementById('signInBtnSpinner');
                                                const btnText = document.getElementById('signInBtnText');

                                                if(form) {
                                                    form.addEventListener('submit', function () {
                                                        btn.disabled = true;
                                                        spinner.classList.remove('d-none');
                                                        btnText.innerHTML = '<i class="fa fa-fw fa-sign-in-alt me-1 opacity-80"></i> Signing In...';
                                                    });
                                                }
                                            });
                                        </script>
                                    </div>
                                </form>
                                <!-- END Sign In Form -->
                            </div>
                        </div>
                    </div>
                    <!-- END Sign In Block -->
                </div>
            </div>
            <div class="fs-sm text-muted text-center">
                <strong>Krismo 0.0.1</strong> &copy; <span data-toggle="year-copy"></span>
            </div>
        </div>
    </div>
    <!-- END Page Content -->

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>
@endsection
