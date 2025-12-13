@extends('admin.index')
@section('title', 'Rekap Data')

@section('content')
<h1 class="h3 mb-2 text-gray-800">Rekap Data Identifikasi Suara</h1>
<p>Hasil analisis dari aktivitas identifikasi suara yang telah dilakukan oleh para pengguna.</p>

<div class="card shadow mb-4">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Kelamin</th>
                        <th>Usia</th>
                        <th>Tanggal dan Waktu</th>
                        <th style="width: 15%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapData as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->nama ?? '-' }}</td>
                            <td>{{ $data->gender ?? '-' }}</td>
                            <td>{{ $data->usia ? $data->usia . ' Tahun' : '-' }}</td>
                            
                            {{-- Format Tanggal dan Waktu (e.g. 10 Dec 2025, 14:30) --}}
                            <td>{{ optional($data->created_at)->format('d M Y, H:i') }}</td>
                            
                            <td>
                                {{-- Pratinjau PDF --}}
                                <a href="{{ route('admin.rekap-data.pdf', $data->id) }}" target="_blank" class="btn btn-sm btn-info" title="Pratinjau PDF">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                {{-- Download PDF --}}
                                <a href="{{ route('admin.rekap-data.pdf', $data->id) }}?mode=download" target="_blank" class="btn btn-sm btn-success" title="Download PDF">
                                    <i class="fa-solid fa-download"></i>
                                </a>

                                {{-- Delete --}}
                                <button type="button" class="btn btn-sm btn-danger" title="Hapus" onclick="confirmDelete({{ $data->id }})">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $data->id }}" action="{{ route('admin.rekap-data.destroy', $data->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Belum ada data identifikasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: "{{ session('success') }}",
        timer: 1500,
        showConfirmButton: false
    });
</script>
@endif

@endsection
