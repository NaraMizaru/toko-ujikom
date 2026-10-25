<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $data['kategoris'] = Kategori::withCount('produk')->orderBy('name')->get();

        return view('admin.kategori.index')->with($data);
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:kategoris,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Kategori::create(['name' => $request->name]);

        return redirect()->route('admin.kategori.index')->with('status', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:kategoris,name,'.$kategori->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $kategori->update(['name' => $request->name]);

        return redirect()->route('admin.kategori.index')->with('status', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->produk()->exists()) {
            return redirect()->route('admin.kategori.index')
                ->withErrors(['kategori' => 'Kategori masih memiliki produk sehingga tidak bisa dihapus.']);
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('status', 'Kategori berhasil dihapus.');
    }
}
