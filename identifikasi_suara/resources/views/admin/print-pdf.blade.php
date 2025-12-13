<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Identifikasi Suara - {{ $data->nama }}</title>
    <style>
        body { font-family: sans-serif; padding: 40px; color: #333; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #0053d6; padding-bottom: 20px; }
        .header h1 { color: #0053d6; margin: 0; }
        .header p { margin: 5px 0; color: #666; }
        .content { margin-bottom: 30px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .info-table th, .info-table td { text-align: left; padding: 10px; border-bottom: 1px solid #ddd; }
        .info-table th { width: 30%; font-weight: 600; color: #555; }
        
        .result-section { margin-top: 30px; }
        .result-title { font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #0053d6; border-left: 4px solid #0053d6; padding-left: 10px; }
        
        .summary-box { background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 20px; border: 1px solid #e2e8f0; }
        .summary-box h2 { margin: 0; font-size: 24px; color: #1e293b; }
        .summary-box p { margin: 5px 0 0; color: #64748b; font-size: 14px; }

        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #eee; padding-top: 20px; }

        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="checkPrint()">
    <script>
        function checkPrint() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('mode') === 'download') {
                window.print();
            }
        }
    </script>

    <div class="header">
        <h1>SUARA KU</h1>
        <p>Laporan Hasil Identifikasi Emosi & Suku</p>
    </div>

    <div class="content">
        <div class="result-title">Informasi Pengguna</div>
        <table class="info-table">
            <tr>
                <th>Nama Lengkap</th>
                <td>{{ $data->nama }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $data->email }}</td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td>{{ $data->gender }}</td>
            </tr>
            <tr>
                <th>Usia</th>
                <td>{{ $data->usia }} Tahun</td>
            </tr>
            <tr>
                <th>Tanggal Upload/Rekam</th>
                <td>{{ $data->created_at->format('d F Y, H:i') }}</td>
            </tr>
        </table>

        <div class="result-title">Hasil Identifikasi</div>
        
        <div class="summary-box">
            <p>Emosi Dominan</p>
            <h2>{{ $data->hasil }}</h2>
        </div>

        <div class="summary-box">
            <p>Suku Dominan</p>
            <h2>{{ $data->dominant_suku }}</h2>
        </div>

        @if($data->akurasi)
        <div class="summary-box">
            <p>Tingkat Akurasi</p>
            <h2>{{ $data->akurasi }}%</h2>
        </div>
        @endif
    </div>

    <div class="footer">
        Dicetak pada: {{ date('d-m-Y H:i:s') }}
    </div>

</body>
</html>
