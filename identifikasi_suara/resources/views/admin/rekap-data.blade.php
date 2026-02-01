@extends('admin.index')
@section('title', 'Rekap Data')

@section('content')
<h1 class="h3 mb-2 text-gray-800">Rekap Data Identifikasi Suara</h1>
<p>Hasil analisis dari aktivitas identifikasi suara yang telah dilakukan oleh para pengguna.</p>

<div class="card shadow mb-4">
    <div class="card-body">

        <!-- Controls: Records Per Page -->
        <form method="GET" action="{{ route('admin.rekap-data.index') }}" 
        class="mb-3 d-flex justify-content-between align-items-center flex-wrap">

        <!-- KIRI: Show entries -->
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <label class="mr-2 mb-0">Show</label>
            <select name="per_page"
                    class="form-control form-control-sm mx-2"
                    style="width: auto;"
                    onchange="this.form.submit()">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
            <label class="mb-0">entries</label>
        </div>

        <!-- KANAN: CSV Action -->
        <div class="d-flex gap-2">
            <!-- Download CSV -->
            <a href="{{ route('admin.rekap-data.csv', ['mode' => 'download']) }}"
            class="btn btn-sm btn-outline-success">
                <i class="fa-solid fa-file-csv"></i> Download CSV
            </a>
        </div>
    </form>

        <div class="table-responsive horizontal-scroll-view">
            <!-- Added table-sm for better density given many columns -->
            <table class="table table-bordered table-hover table-sm text-nowrap" style="font-size: 0.9rem;">
                <thead class="table-primary text-center" style="vertical-align: middle;">
                    <tr>
                        <th rowspan="2" style="width: 3%;">No</th>
                        <th rowspan="2">Nama</th>
                        <th rowspan="2">JK</th>
                        <th rowspan="2">Usia</th>
                        <th rowspan="2">Durasi</th>
                        
                        <th colspan="5">Emosi (%)</th>
                        <th colspan="5">Suku (%)</th>
                        
                        <th rowspan="2">Waktu</th>
                        <th rowspan="2" style="width: 10%;">Action</th>
                    </tr>
                    <tr>
                        <!-- Emosi Headers -->
                        <th>Happy</th>
                        <th>Sad</th>
                        <th>Angry</th>
                        <th>Surprised</th>
                        <th>Neutral</th>
                        
                        <!-- Suku Headers -->
                        <th>Jawa</th>
                        <th>Sunda</th>
                        <th>Batak</th>
                        <th>Minang</th>
                        <th>Betawi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapData as $data)
                        @php
                            $em = $data->distribution_by_emotion ?? [];
                            $sk = $data->distribution_by_suku ?? [];
                        @endphp
                        <tr>
                            <td class="text-center">{{ $rekapData->firstItem() + $loop->index }}</td>
                            <td>{{ $data->nama ?? '-' }}</td>
                            <td class="text-center">{{ $data->gender ?? '-' }}</td>
                            <td class="text-center">{{ $data->usia ? $data->usia : '-' }}</td>
                            <td class="text-center">{{ $data->durasi ?? '-' }}</td>

                            <!-- Emosi Values -->
                            <td class="text-center">{{ number_format($em['Happy']['percent'] ?? 0, 1) }}%</td>
                            <td class="text-center">{{ number_format($em['Sad']['percent'] ?? 0, 1) }}%</td>
                            <td class="text-center">{{ number_format($em['Angry']['percent'] ?? 0, 1) }}%</td>
                            <td class="text-center">{{ number_format($em['Surprised']['percent'] ?? 0, 1) }}%</td>
                            <td class="text-center">{{ number_format($em['Neutral']['percent'] ?? 0, 1) }}%</td>

                            <!-- Suku Values -->
                            <td class="text-center">{{ number_format($sk['Jawa']['percent'] ?? 0, 1) }}%</td>
                            <td class="text-center">{{ number_format($sk['Sunda']['percent'] ?? 0, 1) }}%</td>
                            <td class="text-center">{{ number_format($sk['Batak']['percent'] ?? 0, 1) }}%</td>
                            <td class="text-center">{{ number_format($sk['Minang']['percent'] ?? 0, 1) }}%</td>
                            <td class="text-center">{{ number_format($sk['Betawi']['percent'] ?? 0, 1) }}%</td>
                            
                            <!-- Date -->
                            <td class="text-center" style="font-size:0.8rem;">
                                {{ optional($data->created_at)->format('d M Y, H:i') }}
                            </td>
                            
                            <td class="text-center">
                                <a href="{{ route('admin.rekap-data.pdf', $data->id) }}" target="_blank" class="btn btn-sm btn-info mb-1" title="Pratinjau PDF">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.rekap-data.pdf', $data->id) }}?mode=download" target="_blank" class="btn btn-sm btn-success mb-1" title="Download PDF">
                                    <i class="fa-solid fa-download"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger mb-1" title="Hapus" onclick="confirmDelete({{ $data->id }})">
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
                            <td colspan="17" class="text-center text-muted">
                                Belum ada data identifikasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="row mt-3 align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                Showing {{ $rekapData->firstItem() ?? 0 }} to {{ $rekapData->lastItem() ?? 0 }} of {{ $rekapData->total() }} entries
            </div>
            <div class="col-md-6 d-flex justify-content-md-end justify-content-center">
                {{ $rekapData->links('pagination::bootstrap-5') }}
            </div>
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
