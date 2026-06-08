@extends('auth.index')

@section('content')
<div class="login-container">
    
    <!-- Left Column: Form Section -->
    <div class="form-section">
        <h2 class="fw-extrabold mb-1" style="font-family: 'Outfit', sans-serif; font-size: 26px; color: var(--text-dark); letter-spacing: -0.5px;">Admin Login</h2>
        <p class="text-muted mb-4" style="font-size: 13.5px; font-weight: 400;">Masuk ke dashboard admin</p>

        @if (session('status'))
            <p id="successMsg" style="display:block; color: #16a34a; font-size: 13px; font-weight: 500;" class="mb-3">
                {{ session('status') }}
            </p>
        @endif

        <form method="POST" action="{{ route('auth.login.submit') }}" id="loginForm">
            @csrf

            <!-- Email / Username input -->
            <div class="mb-3">
                <input
                    type="text"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="Email atau username"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    autofocus
                />
                @error('email')
                    <div class="field-error" style="color: #ff4d4f; font-size: 12px; margin-top: 5px; font-weight: 500;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Password input -->
            <div class="mb-4">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Password"
                    required
                    autocomplete="current-password"
                />
                @error('password')
                    <div class="field-error" style="color: #ff4d4f; font-size: 12px; margin-top: 5px; font-weight: 500;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- General Validation Error Display -->
            @if ($errors->has('email') && !$errors->has('password'))
                <p id="errorMsg" style="display:block; color: #ff4d4f; font-size: 13px; font-weight: 500;" class="mb-3">
                    {{ $errors->first('email') }}
                </p>
            @endif

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100 mb-2" id="btnMasuk">Masuk</button>
            <a href="{{ route('informasi') }}" class="btn btn-outline-primary w-100">Kembali</a>
        </form>
    </div>

    <!-- Right Column: Info Section -->
    <div class="info-section text-center">
        
        <!-- Logo and Pill subtext -->
        <div class="d-flex flex-column align-items-center">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <img src="{{ asset('img/logo-suarakuu.png') }}" alt="Logo Suaraku" class="logo-img">
                </div>
                <span class="fs-4 fw-extrabold text-white" style="font-family: 'Outfit', sans-serif; font-weight: 800; letter-spacing: -0.5px;">SUARAKU</span>
            </div>
            <span class="badge rounded-pill border border-white border-opacity-25 px-3 py-1.5" style="font-size: 10px; font-weight: 500; background-color: rgba(255, 255, 255, 0.08); letter-spacing: 0.5px;">Speech Emotion Recognition</span>
        </div>

        <!-- Greeting content -->
        <div class="my-auto d-flex flex-column align-items-center justify-content-center">
            <h3 class="fw-extrabold text-white mb-2" style="font-family: 'Outfit', sans-serif; font-size: 24px;">Halo, Sobat!</h3>
            <p class="text-white-50 px-2 mb-0" style="font-size: 13px; line-height: 1.6;">
                Selamat datang di aplikasi <b>Suara Ku</b>. Silakan isi data pribadimu untuk melanjutkan.
            </p>
        </div>

        <!-- Empty spacing buffer matching layout alignment of landing page -->
        <div style="height: 38px;"></div>

    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const btnMasuk = document.getElementById('btnMasuk');
    btnMasuk.disabled = true;
    btnMasuk.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Masuk...';

    Swal.fire({
        title: 'Memproses login...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
});
</script>
@endsection
