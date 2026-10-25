<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id',
        'name',
        'harga',
        'foto',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
