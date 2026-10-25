<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_harga',
        'status',
        'kode',
        'bukti_transaksi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransaksiItem::class);
    }

    public function syncTotal(): void
    {
        $this->update([
            'total_harga' => $this->items()->get()->sum('subtotal'),
        ]);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'keranjang' => 'Keranjang',
            'dibayar' => 'Menunggu Verifikasi',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'keranjang' => 'secondary',
            'dibayar' => 'warning',
            'diterima' => 'success',
            'ditolak' => 'danger',
            default => 'secondary',
        };
    }
}
