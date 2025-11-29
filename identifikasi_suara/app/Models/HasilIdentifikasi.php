<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilIdentifikasi extends Model
{
    protected $table = 'hasil_identifikasi';

    protected $fillable = [
        'user_id',
        'file_suara',
        'hasil',
        'akurasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
