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
                        <th>No</th>
                        <th>Nama User</th>
                        <th>Email</th>
                        <th>File Suara</th>
                        <th>Hasil</th>
                        <th>Akurasi</th>
                        <th>Tanggal Upload</th>
                    </tr>
                </thead>
                <tbody>

                    @php $no = 1; @endphp

                    @foreach($rekapData as $user)
                        @foreach($user->hasil as $data)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>

                                <td>
                                    @if($data->file_suara)
                                        <audio controls>
                                            <source src="{{ asset('storage/suara/' . $data->file_suara) }}">
                                        </audio>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>{{ $data->hasil ?? '-' }}</td>

                                <td>
                                    {{ $data->akurasi ? $data->akurasi . '%' : '-' }}
                                </td>

                                <td>{{ $data->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
