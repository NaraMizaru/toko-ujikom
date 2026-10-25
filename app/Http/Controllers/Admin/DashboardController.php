<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        $data['menunggu'] = Transaksi::where('status', 'dibayar')->count();
        $data['diterima'] = Transaksi::where('status', 'diterima')->count();
        $data['ditolak'] = Transaksi::where('status', 'ditolak')->count();
        $data['produk'] = Produk::count();
        $data['transaksis'] = Transaksi::where('status', 'dibayar')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard')->with($data);
    }
}
