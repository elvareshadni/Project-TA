<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Identifikasi Suara - {{ $data->nama }}</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f6fc; margin: 0; padding: 20px; color: #333; }
        .container {
            max-width: 210mm; /* A4 width */
            min-height: 297mm; /* A4 height */
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            box-shadow: 0px 4px 20px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
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

        @page { size: A4 portrait; margin: 20mm; }

        @media (max-width: 768px) {
            body { padding: 10px; }
            .container { max-width: 100%; min-height: auto; padding: 15px; box-shadow: none; }
            .header { margin-bottom: 20px; padding-bottom: 10px; }
            .summary-box { padding: 15px; margin-bottom: 15px; }
            .summary-box h2 { font-size: 20px; }
            .summary-box span { font-size: 16px !important; }
            
            /* Informasi Pengguna Table */
            .info-table th, .info-table td { display: block; width: 100%; text-align: left; padding: 5px 0; border: none; }
            .info-table tr { display: block; border-bottom: 1px solid #ddd; padding: 10px 0; }

            /* Detail Table Side-by-Side */
            .detail-table thead { display: none; }
            .detail-table > tbody > tr { display: block; }
            .detail-table > tbody > tr > td { display: block; width: 100% !important; border-right: none !important; margin-bottom: 20px; }
            .mobile-title { display: block !important; font-weight: bold; margin-bottom: 10px; color: #0053d6; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        }

        .mobile-title { display: none; }

        @media print {
            body { background-color: transparent; padding: 0; }
            .container { max-width: 100%; min-height: auto; box-shadow: none; border-radius: 0; padding: 0; }
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

    <div class="container">
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
        
        @php
            $domEmotionPercent = 0;
            if (isset($data->distribution_by_emotion[$data->hasil]['percent'])) {
                $domEmotionPercent = number_format($data->distribution_by_emotion[$data->hasil]['percent'], 2);
            }
            
            $domSukuPercent = 0;
            if (isset($data->distribution_by_suku[$data->dominant_suku]['percent'])) {
                $domSukuPercent = number_format($data->distribution_by_suku[$data->dominant_suku]['percent'], 2);
            }
        @endphp

        <div class="summary-box">
            <p>Emosi Dominan</p>
            <h2>{{ $data->hasil }} <span style="font-size: 18px; color: #64748b;">({{ $domEmotionPercent }}%)</span></h2>
        </div>

        <div class="summary-box">
            <p>Suku Dominan</p>
            <h2>{{ $data->dominant_suku }} <span style="font-size: 18px; color: #64748b;">({{ $domSukuPercent }}%)</span></h2>
        </div>

        @if($data->akurasi)
        <div class="summary-box">
            <p>Tingkat Akurasi</p>
            <h2>{{ $data->akurasi }}%</h2>
        </div>
        @endif

        <div class="result-title" style="margin-top: 30px;">Detail Persentase Emosi & Suku</div>
        <table class="detail-table" style="margin-bottom: 10px; width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="width: 50%; border-right: 1px solid #ddd; background-color: #f8f9fa; padding: 10px; text-align: left;">Kelas Emosi</th>
                    <th style="width: 50%; background-color: #f8f9fa; padding: 10px; text-align: left;">Kelas Suku</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="vertical-align: top; padding: 0; border-right: 1px solid #ddd;">
                        <div class="mobile-title">Kelas Emosi</div>
                        <table style="width: 100%; border-collapse: collapse;">
                            @if(is_array($data->distribution_by_emotion))
                                @foreach($data->distribution_by_emotion as $emotion => $val)
                                <tr>
                                    <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $emotion }}</td>
                                    <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right; font-weight: 600;">{{ isset($val['percent']) ? number_format($val['percent'], 2) : 0 }}%</td>
                                </tr>
                                @endforeach
                            @else
                                <tr><td style="padding: 10px; text-align: center;">Tidak ada data</td></tr>
                            @endif
                        </table>
                    </td>
                    <td style="vertical-align: top; padding: 0;">
                        <div class="mobile-title">Kelas Suku</div>
                        <table style="width: 100%; border-collapse: collapse;">
                            @if(is_array($data->distribution_by_suku))
                                @foreach($data->distribution_by_suku as $suku => $val)
                                <tr>
                                    <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $suku }}</td>
                                    <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right; font-weight: 600;">{{ isset($val['percent']) ? number_format($val['percent'], 2) : 0 }}%</td>
                                </tr>
                                @endforeach
                            @else
                                <tr><td style="padding: 10px; text-align: center;">Tidak ada data</td></tr>
                            @endif
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

        <div class="footer">
            Dicetak pada: {{ date('d-m-Y H:i:s') }}
        </div>
    </div>
</body>
</html>
