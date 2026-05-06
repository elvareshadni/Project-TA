<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Identifikasi Suara</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f6fc; margin: 0; padding: 20px; color: #333; }
        .container {
            max-width: 210mm; /* A4 width */
            margin: 0 auto;
            background: white;
            box-shadow: 0px 4px 20px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .header { background: #0053d6; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .result-box { margin-bottom: 20px; border: 1px solid #ddd; padding: 15px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }

        @media (max-width: 768px) {
            body { padding: 10px; }
            .container { max-width: 100%; border-radius: 8px; }
            .header { padding: 15px; }
            .header h2 { font-size: 20px; margin: 0; }
            .content { padding: 15px; }
            .result-box { padding: 15px; }
            th, td { padding: 6px; font-size: 14px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Hasil Identifikasi Digital SUARAKU</h2>
        </div>
        <div class="content">
        <p>Halo, <strong>{{ $data['nama'] }}</strong>!</p>
        <p>Terima kasih telah menggunakan layanan SUARAKU. Berikut adalah hasil analisis suara Anda:</p>

        <div class="result-box">
            <h3>Ringkasan Dominan</h3>
            <p><strong>Emosi Dominan:</strong> {{ $data['hasil'] }}</p>
            
            @php
                $domSuku = '-';
                $maxSuku = 0;
                if(isset($data['distribution_by_suku'])) {
                    foreach($data['distribution_by_suku'] as $suku => $val) {
                        if($val['percent'] > $maxSuku) {
                            $maxSuku = $val['percent'];
                            $domSuku = $suku;
                        }
                    }
                }
            @endphp
            <p><strong>Suku Dominan:</strong> {{ $domSuku }}</p>
        </div>

        <div class="result-box">
            <h3>Detail Emosi</h3>
            <table>
                <thead>
                    <tr>
                        <th>Emosi</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data['distribution_by_emotion']))
                        @foreach($data['distribution_by_emotion'] as $emotion => $val)
                            <tr>
                                <td>{{ $emotion }}</td>
                                <td>{{ number_format($val['percent'], 2) }}%</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="result-box">
            <h3>Detail Suku</h3>
            <table>
                <thead>
                    <tr>
                        <th>Suku</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data['distribution_by_suku']))
                        @foreach($data['distribution_by_suku'] as $suku => $val)
                            <tr>
                                <td>{{ $suku }}</td>
                                <td>{{ number_format($val['percent'], 2) }}%</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <p>Terima kasih,</p>
        <p>Tim SUARAKU</p>
    </div>
    </div>
</body>
</html>
