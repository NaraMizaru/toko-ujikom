<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $allowedStatuses = ['dibayar', 'diterima', 'ditolak'];

        $query = Transaksi::whereIn('status', $allowedStatuses)->with('user');

        if (in_array($status, $allowedStatuses, true)) {
            $query->where('status', $status);
        }

        $data['transaksis'] = $query->latest()->get();
        $data['status'] = $status;
        $data['counts'] = [
            'dibayar' => Transaksi::where('status', 'dibayar')->count(),
            'diterima' => Transaksi::where('status', 'diterima')->count(),
            'ditolak' => Transaksi::where('status', 'ditolak')->count(),
        ];

        return view('admin.transaksi.index')->with($data);
    }

    public function show(Transaksi $transaksi)
    {
        if ($transaksi->status === 'keranjang') {
            abort(404);
        }

        $transaksi->load(['user', 'items.produk']);

        return view('admin.transaksi.show', compact('transaksi'));
    }

    public function terima(Transaksi $transaksi)
    {
        if ($transaksi->status !== 'dibayar') {
            return redirect()->back()
                ->withErrors(['status' => 'Transaksi ini tidak sedang menunggu verifikasi.']);
        }

        $transaksi->update(['status' => 'diterima']);

        return redirect()->route('admin.transaksi.show', $transaksi)
            ->with('status', 'Pembayaran berhasil diterima.');
    }

    public function tolak(Transaksi $transaksi)
    {
        if ($transaksi->status !== 'dibayar') {
            return redirect()->back()
                ->withErrors(['status' => 'Transaksi ini tidak sedang menunggu verifikasi.']);
        }

        $transaksi->update(['status' => 'ditolak']);

        return redirect()->route('admin.transaksi.show', $transaksi)
            ->with('status', 'Pembayaran ditolak. Hubungi customer melalui nomor HP yang tersedia.');
    }
}
