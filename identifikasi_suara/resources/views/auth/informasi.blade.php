@extends('auth.index')

@section('content')
<div class="login-container">
    <div class="form-section">
        <h3 class="text-center mb-4 fw-bold text-primary">Informasi Pribadi</h3>
        <form id="userForm">
            <div class="mb-3">
                <input type="text" class="form-control" id="nama" placeholder="Masukkan nama" required>
            </div>

            <div class="mb-3">
                <input type="email" class="form-control" id="email" placeholder="Masukkan email" required>
                <small style="font-style: italic; color: #6c757d;">
                    *Pastikan email yang Anda masukkan aktif.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Kelamin </label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="Laki-laki" checked>
                    <label class="form-check-label" for="male">Laki-laki</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="Perempuan">
                    <label class="form-check-label" for="female">Perempuan</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Usia (Tahun) *</label>
                <select class="form-select" id="usia" required>
                    <option value="">Pilih Usia</option>
                    @for ($i = 10; $i <= 30; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-3">Simpan</button>
            <a href="/login" class="btn btn-outline-primary w-100 mt-3">Login sebagai Admin</a>
        </form>
    </div>

    <!-- Bagian Sambutan -->
<div class="info-section text-white d-none d-md-flex flex-column justify-content-center align-items-center" 
     style="padding-top: 200px; height: auto;">

    <!-- Animasi Audio di Atas -->
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

    <!-- Teks Sambutan di Bawah -->
    <h2 class="fw-bold mb-3">Halo, Sobat!</h2>
    <p class="text-center mb-4">
        Selamat datang di aplikasi <b>Suaraku</b>.<br>
        Silakan isi data pribadimu untuk melanjutkan.
    </p>

    <div class="mb-4 text-center">
        <a href="{{ route('tentang-kami') }}" class="btn btn-sm btn-outline-light rounded-pill px-4">
            <i class="fa-solid fa-circle-info me-1"></i> Tentang Kami
        </a>
    </div>
</div>

<!-- CSS Animasi -->
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
    background: white;
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


</div>

<script>
document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const data = {
        nama: document.getElementById('nama').value,
        email: document.getElementById('email').value,
        gender: document.querySelector('input[name="gender"]:checked').value,
        usia: document.getElementById('usia').value,
    };

    fetch('/save-user', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',  
            'X-CSRF-TOKEN': '{{ csrf_token() }}'  
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success') {
            Swal.fire({
                title: 'Selamat Datang!',
                text: 'Halo, ' + data.nama + '! Data Anda berhasil disimpan.',
                icon: 'success',
                confirmButtonText: 'Lanjut',
                confirmButtonColor: '#0053d6',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = response.redirect;
                }
            });
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan, coba lagi.',
                icon: 'error',
                confirmButtonColor: '#0053d6'
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan sistem.',
            icon: 'error',
            confirmButtonColor: '#0053d6'
        });
    });
});
</script>
@endsection
