@extends('admin.index')
@section('title', 'Setting Waktu')

@section('content')
<h1 class="h3 mb-3 text-gray-800">Setting Waktu Identifikasi</h1>

<div class="card shadow mb-4">
    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <p class="mb-3">
            Atur durasi waktu (dalam detik) yang digunakan dalam proses identifikasi suara.
        </p>

        @php
            $min = 180;
            $max = 300;
            if (isset($setting) && $setting->durasi) {
                $parts = explode('-', $setting->durasi);
                if (count($parts) == 2) {
                    $minVal = (int)$parts[0];
                    $maxVal = (int)$parts[1];
                    if ($maxVal < 60) {
                        $min = $minVal * 60;
                        $max = $maxVal * 60;
                    } else {
                        $min = $minVal;
                        $max = $maxVal;
                    }
                }
            }
        @endphp

        <form action="{{ route('admin.setting-waktu.update') }}" method="POST">
            @csrf

            <div class="d-flex align-items-end gap-2">

                <!-- Durasi Minimum -->
                <div>
                    <label class="form-label">Durasi Minimum</label>
                    <div class="input-group">
                        <input type="number"
                               name="durasi_min"
                               class="form-control"
                               min="1"
                               placeholder="contoh: 180"
                               value="{{ $min }}"
                               required>
                        <span class="input-group-text">detik</span>
                    </div>
                </div>

                <!-- Tanda strip -->
                <div class="mb-2">
                    <strong>-</strong>
                </div>

                <!-- Durasi Maksimum -->
                <div>
                    <label class="form-label">Durasi Maksimum</label>
                    <div class="input-group">
                        <input type="number"
                               name="durasi_max"
                               class="form-control"
                               min="1"
                               placeholder="contoh: 300"
                               value="{{ $max }}"
                               required>
                        <span class="input-group-text">detik</span>
                    </div>
                </div>

            </div>

            <button class="btn btn-primary mt-4">
                Simpan Pengaturan
            </button>
        </form>

    </div>
</div>
@endsection
