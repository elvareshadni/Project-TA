@extends('auth.index')

@section('content')
<div class="login-container">
    
    <!-- Left Column: Form Section -->
    <div class="form-section">
        <h2 class="fw-extrabold mb-1" style="font-family: 'Outfit', sans-serif; font-size: 26px; color: var(--text-dark); letter-spacing: 0.5px;">Informasi Pribadi</h2>
        <p class="text-muted mb-2" style="font-size: 13.5px; font-weight: 400;">Lengkapi data dirimu dibawah ini</p>
        
        <form id="userForm">
            <!-- Nama Lengkap -->
            <div class="mb-1">
                <label class="form-label" for="nama">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama" placeholder="Masukkan nama" required>
            </div>

            <!-- Email -->
            <div class="mb-1">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Masukkan email" required>
                <div class="mt-1" style="font-size: 11px; font-style: italic; color: #64748b;">
                    *Pastikan email yang Anda masukkan aktif.
                </div>
            </div>

            <!-- No HP -->
            <div class="mb-1">
                <label class="form-label" for="no_hp">No. HP</label>
                <input type="tel" class="form-control" id="no_hp" placeholder="Masukkan No. HP" required>
            </div>

            <!-- Gender & Usia Row -->
            <div class="row gy-3 gx-2 mb-4 align-items-end">
                <div class="col-12 col-md-7">
                    <label class="form-label">Jenis Kelamin</label>
                    <div class="d-flex gap-3 align-items-center mt-2">
                        <div class="form-check form-check-inline m-0">
                            <input class="form-check-input" type="radio" name="gender" id="male" value="Laki-laki" checked>
                            <label class="form-check-label" for="male">Laki-laki</label>
                        </div>
                        <div class="form-check form-check-inline m-0">
                            <input class="form-check-input" type="radio" name="gender" id="female" value="Perempuan">
                            <label class="form-check-label" for="female">Perempuan</label>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-5">
                    <label class="form-label" for="usia">Usia (Tahun)*</label>
                    <select class="form-select" id="usia" required>
                        <option value="">Pilih Usia</option>
                        @for ($i = 1; $i <= 99; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <button type="submit" class="btn btn-primary w-100 mb-2" id="btnSimpan">Simpan</button>
            <a href="/login" class="btn btn-outline-primary w-100">Login sebagai Admin</a>
        </form>
    </div>

    <!-- Right Column: Info Section -->
<div class="info-section text-center">

    <!-- Logo -->
    <div class="d-flex flex-column align-items-center mb-2">
        <img src="{{ asset('img/logo-suarakuu.png') }}"
             alt="Logo Suaraku"
             style="width:200px; height:auto;">

        <span class="badge rounded-pill border border-white border-opacity-25 px-3 py-1 mt-2"
              style="font-size:10px; font-weight:500; background-color:rgba(255,255,255,.08); letter-spacing:.5px;">
            Speech Emotion Recognition
        </span>
    </div>

    <!-- Greeting -->
    <div class="d-flex flex-column align-items-center">
        <h3 class="fw-extrabold text-white mb-2"
            style="font-family:'Outfit',sans-serif; font-size:24px;">
            Halo, Sobat!
        </h3>

        <p class="text-white px-2 mb-3"
           style="font-size:13px; line-height:1.5;">
            Selamat datang di aplikasi <b>Suaraku</b>.
            Silakan isi data pribadimu untuk melanjutkan.
        </p>

            <!-- Features details block -->
            <div class="d-flex flex-column gap-3 w-100 text-start px-3 mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle text-white" style="width: 38px; height: 38px; background-color: rgba(255, 255, 255, 0.12); flex-shrink: 0;">
                        <i class="fa-regular fa-face-smile fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-white fw-bold" style="font-size: 13.5px;">Deteksi Emosi</h6>
                        <p class="mb-0 text-white-50" style="font-size: 11.5px;">Analisis emosi dari suaramu</p>
                    </div>
                </div>
            </div>

            <!-- Visual equalizer animation -->
            <div class="audio-wave mb-3">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <!-- About Button at bottom -->
        <div>
            <a href="{{ route('tentang-kami') }}" class="btn btn-sm btn-outline-light rounded-pill px-4" style="border-color: rgba(255, 255, 255, 0.35); font-size: 12px; font-weight: 600; padding: 7px 20px;">
                Tentang Kami
            </a>
        </div>

    </div>
</div>

<script>
document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const btnSimpan = document.getElementById('btnSimpan');
    const originalText = btnSimpan.innerHTML;

    // Set button loading state
    btnSimpan.disabled = true;
    btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';

    // Show SweetAlert loading state immediately
    Swal.fire({
        title: 'Menyimpan data...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const data = {
        nama: document.getElementById('nama').value,
        email: document.getElementById('email').value,
        no_hp: document.getElementById('no_hp').value,
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
            // Redirect immediately while loading popup remains visible
            window.location.href = response.redirect;
        } else {
            // Restore button
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = originalText;
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan, coba lagi.',
                icon: 'error',
                confirmButtonColor: '#0c56d0'
            });
        }
    })
    .catch(err => {
        console.error(err);
        // Restore button
        btnSimpan.disabled = false;
        btnSimpan.innerHTML = originalText;
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan sistem.',
            icon: 'error',
            confirmButtonColor: '#0c56d0'
        });
    });
});
</script>
@endsection
