<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasis';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'lokasi',
        'kuota',
        'jarak',
    ];

    protected static function boot()
    {
        parent::boot();

        // Event listener untuk membuat UUID sebelum menyimpan
        static::creating(function ($model) {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }
}
