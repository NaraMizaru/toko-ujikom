<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\TransaksiItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function index()
    {
        $data['produks'] = Produk::with('kategori')->latest()->get();

        return view('admin.produk.index')->with($data);
    }

    public function create()
    {
        $data['kategoris'] = Kategori::orderBy('name')->get();

        return view('admin.produk.create')->with($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga' => 'required|integer|min:0',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Produk::create([
            'name' => $request->name,
            'kategori_id' => $request->kategori_id,
            'harga' => (int) $request->harga,
            'foto' => $request->foto->store('img', 'public'),
        ]);

        return redirect()->route('admin.produk.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk)
    {
        $data['produk'] = $produk;
        $data['kategoris'] = Kategori::orderBy('name')->get();

        return view('admin.produk.edit')->with($data);
    }

    public function update(Request $request, Produk $produk)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->name,
            'kategori_id' => $request->kategori_id,
            'harga' => (int) $request->harga,
        ];

        if ($request->hasFile('foto')) {
            $newFoto = $request->foto->store('img', 'public');

            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }

            $data['foto'] = $newFoto;
        }

        $produk->update($data);

        return redirect()->route('admin.produk.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        if (TransaksiItem::where('produk_id', $produk->id)->exists()) {
            return redirect()->route('admin.produk.index')
                ->withErrors(['produk' => 'Produk sudah dipakai dalam transaksi sehingga tidak bisa dihapus.']);
        }

        if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')->with('status', 'Produk berhasil dihapus.');
    }
}
