<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilIdentifikasi extends Model
{
    // nama tabel di database
    protected $table = 'hasil_identifikasi';

    protected $fillable = [
        'nama',
        'email',
        'gender',
        'usia',
        'sumber',
        'file_suara',
        'durasi',
        'hasil',
        'akurasi',
        'distribution_by_emotion',
        'distribution_by_suku',
    ];

    protected $casts = [
        'distribution_by_emotion' => 'array',
        'distribution_by_suku' => 'array',
    ];

    public function getDominantSukuAttribute()
    {
        $distribution = $this->distribution_by_suku;

        if (empty($distribution) || !is_array($distribution)) {
            return '-';
        }

        // Cari value tertinggi
        $maxVal = -1;
        $maxKey = '-';

        foreach ($distribution as $key => $val) {
            // Ambil value numerik (handle jika formatnya array ['percent' => ...])
            $numericVal = 0;
            if (is_array($val) && isset($val['percent'])) {
                $numericVal = floatval($val['percent']);
            } elseif (is_numeric($val)) {
                $numericVal = floatval($val);
            }

            if ($numericVal > $maxVal) {
                $maxVal = $numericVal;
                $maxKey = $key;
            }
        }

        // Jika semua 0 (maxVal 0), mungkin kita tetap return maxKey (yg ketemu terakhir/pertama > -1) 
        // atau return '-' jika maxVal == 0
        // Tapi logic awal: maxVal = -1, jadi 0 > -1 -> akan ambil yg 0 jika tidak ada yg lebih besar.
        // Jika logic bisnis ingin "Dominan" harus > 0, bisa disesuaikan. 
        // Untuk sekarang kita ikuti logic "tertinggi".
        
        return $maxKey;
    }
}
