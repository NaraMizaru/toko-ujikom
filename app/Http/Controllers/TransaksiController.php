<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    public function bukti(Transaksi $transaksi)
    {
        $user = Auth::user();

        if (! $user->isAdmin() && $transaksi->user_id !== $user->id) {
            abort(403);
        }

        if (! $transaksi->bukti_transaksi || ! Storage::disk('local')->exists($transaksi->bukti_transaksi)) {
            abort(404);
        }

        return Storage::disk('local')->response($transaksi->bukti_transaksi);
    }
}
