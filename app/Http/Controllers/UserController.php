<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->isAdmin() ? 'admin.dashboard' : 'index.home');
        }

        return view('login');
    }

    public function home()
    {
        $data['produks'] = Produk::with('kategori')->get();

        return view('home')->with($data);
    }

    public function detail($id)
    {
        $data['produk'] = Produk::with('kategori')->findOrFail($id);

        return view('detail')->with($data);
    }

    public function postLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $auth = Auth::attempt($request->only(['email', 'password']));

        if (! $auth) {
            return redirect()->back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->withInput($request->only('email'));
        }

        $user = Auth::user();

        $redirect = $user->isAdmin() ? 'admin.dashboard' : 'index.home';

        return redirect()->route($redirect)->with('status', 'Selamat datang, '.$user->name);
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('index.home')->with('status', 'Berhasil logout.');
    }

    public function postKeranjang(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'qty' => 'required|integer|min:1|max:99',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $produk = Produk::findOrFail($id);

        $transaksi = Transaksi::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'keranjang'],
            ['total_harga' => 0]
        );

        $item = TransaksiItem::firstOrNew([
            'transaksi_id' => $transaksi->id,
            'produk_id' => $produk->id,
        ]);

        $item->qty = ($item->qty ?? 0) + $request->qty;
        $item->harga_satuan = $produk->harga;
        $item->save();

        $transaksi->syncTotal();

        return redirect()->route('index.home')->with('status', 'Produk berhasil dimasukkan ke keranjang');
    }

    public function keranjang()
    {
        $data['transaksi'] = Transaksi::where('user_id', Auth::id())
            ->where('status', 'keranjang')
            ->with('items.produk')
            ->first();

        return view('keranjang')->with($data);
    }

    public function updateKeranjang(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'qty' => 'required|integer|min:1|max:99',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $item = TransaksiItem::whereHas('transaksi', function ($query) {
            $query->where('user_id', Auth::id())->where('status', 'keranjang');
        })->findOrFail($id);

        $item->update(['qty' => $request->qty]);
        $item->transaksi->syncTotal();

        return redirect()->route('index.keranjang')->with('status', 'Qty berhasil diubah');
    }

    public function hapusKeranjang($id)
    {
        $item = TransaksiItem::whereHas('transaksi', function ($query) {
            $query->where('user_id', Auth::id())->where('status', 'keranjang');
        })->findOrFail($id);

        $transaksi = $item->transaksi;
        $item->delete();

        if ($transaksi->items()->count() === 0) {
            $transaksi->delete();
        } else {
            $transaksi->syncTotal();
        }

        return redirect()->route('index.keranjang')->with('status', 'Item berhasil dihapus');
    }

    public function bayar(Request $request)
    {
        if (blank(Auth::user()->no_hp)) {
            return redirect()->route('profile.edit')
                ->withErrors(['no_hp' => 'Lengkapi nomor HP terlebih dahulu sebelum checkout.']);
        }

        $itemIds = collect($request->input('items', []))->filter()->unique()->values();
        $items = $this->itemsKeranjang($itemIds->all());

        if ($itemIds->isEmpty()) {
            return redirect()->route('index.keranjang')
                ->withErrors(['items' => 'Pilih minimal satu produk untuk dibayar.']);
        }

        if ($items->count() !== $itemIds->count()) {
            return redirect()->route('index.keranjang')
                ->withErrors(['items' => 'Item yang dipilih tidak valid.']);
        }

        $data['items'] = $items;
        $data['itemIds'] = $items->pluck('id')->all();
        $data['totalHarga'] = $items->sum('subtotal');

        return view('bayar')->with($data);
    }

    public function prosesBayar(Request $request)
    {
        if (blank(Auth::user()->no_hp)) {
            return redirect()->route('profile.edit')
                ->withErrors(['no_hp' => 'Lengkapi nomor HP terlebih dahulu sebelum checkout.']);
        }

        $validator = Validator::make($request->all(), [
            'bukti_transaksi' => 'required|file|image|mimes:jpg,jpeg,png|max:2048',
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'integer|distinct',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $itemIds = collect($request->item_ids)->unique()->values();
        $items = $this->itemsKeranjang($itemIds->all());

        if ($items->count() !== $itemIds->count()) {
            return redirect()->route('index.keranjang')
                ->withErrors(['items' => 'Item yang dipilih tidak valid.']);
        }

        $bukti = $request->bukti_transaksi->store('images');

        DB::transaction(function () use ($items, $itemIds, $bukti) {
            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
                'total_harga' => $items->sum('subtotal'),
                'status' => 'dibayar',
                'kode' => 'INV'.Str::random(8),
                'bukti_transaksi' => $bukti,
            ]);

            TransaksiItem::whereIn('id', $itemIds->all())->update([
                'transaksi_id' => $transaksi->id,
            ]);

            $keranjang = Transaksi::where('user_id', Auth::id())
                ->where('status', 'keranjang')
                ->first();

            if ($keranjang) {
                if ($keranjang->items()->count() === 0) {
                    $keranjang->delete();
                } else {
                    $keranjang->syncTotal();
                }
            }
        });

        return redirect()->route('index.summary')->with('status', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }

    public function summary()
    {
        $data['transaksis'] = Transaksi::where('user_id', Auth::id())
            ->whereIn('status', ['dibayar', 'diterima', 'ditolak'])
            ->with('items.produk')
            ->latest()
            ->get();

        return view('summary')->with($data);
    }

    private function itemsKeranjang(array $itemIds)
    {
        if (empty($itemIds)) {
            return collect();
        }

        return TransaksiItem::whereIn('id', $itemIds)
            ->whereHas('transaksi', function ($query) {
                $query->where('user_id', Auth::id())->where('status', 'keranjang');
            })
            ->with('produk')
            ->get();
    }
}
