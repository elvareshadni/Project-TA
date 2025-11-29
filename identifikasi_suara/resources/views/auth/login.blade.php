@extends('auth.index')

@section('content')
<div class="login-container">
    <div class="form-section">
        <h2 class="text-center mb-1 fw-bold text-primary">Admin Login</h2>
        <p class="text-center mb-4">Masuk ke dashboard admin</p>

        @if (session('status'))
            <p id="errorMsg" style="display:block;color:#16a34a" class="mb-3">
            </p>
        @endif

        <form method="POST" action="{{ route('auth.login.submit') }}">
            @csrf

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
                    <div class="field-error" style="color:#ff4d4f;margin-top:5px">
                    </div>
                @enderror
            </div>

            {{-- Password --}}
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
                    <div class="field-error" style="color:#ff4d4f;margin-top:5px">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Error login umum (misalnya kredensial salah) --}}
            @if ($errors->has('email') && !$errors->has('password'))
                <p id="errorMsg" style="display:block;color:#ff4d4f" class="mb-3">
                    {{ $errors->first('email') }}
                </p>
            @endif

            {{-- Tombol Login --}}
            <button type="submit" class="btn-primary w-100 mb-3">Masuk</button>
        </form>
    </div>

    {{-- Panel kanan (animasi + sambutan) --}}
    <div class="info-section text-white d-flex flex-column justify-content-center align-items-center">
        <div class="audio-wave mb-4">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>

        <h2 class="fw-bold mb-3">Halo, Sobat!</h2>
        <p class="text-center mb-0">
            Selamat datang di aplikasi <b>Suara Ku</b>.<br>
            Silakan isi data pribadimu untuk melanjutkan.
        </p>
    </div>
</div>

{{-- CSS Animasi --}}
<style>
.audio-wave {
    display: flex;
    justify-content: center;
    align-items: flex-end;
    height: 40px;
    gap: 5px;
}

.audio-wave span {
    display: block;
    width: 6px;
    height: 10px;
    background: #ffffff;
    border-radius: 5px;
    animation: wave 1.2s infinite ease-in-out;
}

.audio-wave span:nth-child(1) { animation-delay: 0s; }
.audio-wave span:nth-child(2) { animation-delay: 0.1s; }
.audio-wave span:nth-child(3) { animation-delay: 0.2s; }
.audio-wave span:nth-child(4) { animation-delay: 0.3s; }
.audio-wave span:nth-child(5) { animation-delay: 0.4s; }
.audio-wave span:nth-child(6) { animation-delay: 0.5s; }
.audio-wave span:nth-child(7) { animation-delay: 0.6s; }
.audio-wave span:nth-child(8) { animation-delay: 0.7s; }
.audio-wave span:nth-child(9) { animation-delay: 0.8s; }
.audio-wave span:nth-child(10) { animation-delay: 0.9s; }

@keyframes wave {
    0%, 100% {
        height: 10px;
        opacity: 0.5;
    }
    50% {
        height: 35px;
        opacity: 1;
    }
}
</style>
@endsection
