<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class SubKriteria extends Model
{
    use HasFactory;

    protected $table = 'sub_kriterias';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_kriteria',
        'nama',
        'bobot',
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
