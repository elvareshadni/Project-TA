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

            <p class="mb-2">
                Atur durasi waktu yang digunakan dalam proses identifikasi suara.
            </p>

            <form action="{{ route('admin.setting-waktu.update') }}" method="POST">
                @csrf

                <div class="form-group mb-4">
                    <label for="durasi" class="font-weight-bold">Pilih Durasi Identifikasi:</label>
                    <select id="durasi" class="form-control" name="durasi" required>
                        <option value="" disabled {{ empty(optional($setting)->durasi) ? 'selected' : '' }}>-- Pilih Durasi --</option>
                        
                        <option value="1-2" {{ optional($setting)->durasi == '1-2' ? 'selected' : '' }}>
                            1 - 2 Menit
                        </option>

                        <option value="3-5" {{ optional($setting)->durasi == '3-5' ? 'selected' : '' }}>
                            3 - 5 Menit
                        </option>
                        <option value="9-10" {{ optional($setting)->durasi == '9-10' ? 'selected' : '' }}>
                            9 - 10 Menit
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Pengaturan
                </button>
            </form>

        </div>
    </div>
@endsection
