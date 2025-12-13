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
            // Pastikan val numerik
            $numericVal = floatval($val);
            if ($numericVal > $maxVal) {
                $maxVal = $numericVal;
                $maxKey = $key;
            }
        }

        // Opsional: Jika maxVal 0, mungkin return '-' atau tetap nama sukunya
        return $maxKey;
    }
}
