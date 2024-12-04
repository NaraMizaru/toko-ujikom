<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function home()
    {
        $data['produks'] = Produk::all();

        return view('home')->with($data);
    }

    public function detail($id)
    {
        $data['produk'] = Produk::find($id);

        return view('detail')->with($data);
    }

    public function postLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $auth = Auth::attempt($request->only(['email', 'password']));

        if (!$auth) {
            return redirect()->back()->with('status', 'Email atau password salah');
        } else {
            $user = Auth::user();
            return redirect()->route('index.home')->with('status', 'Selamat datang : ' . $user->name);
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('index.home');
    }

    public function postKeranjang(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'qty' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $produk = Produk::find($id);

        DetailTransaksi::create([
            'qty' => $request->qty,
            'user_id' => Auth::id(),
            'produk_id' => $produk->id,
            'status' => 'keranjang',
            'total_harga' => $produk->harga * $request->qty
        ]);

        return redirect()->route('index.home')->with('status', 'Produk berhasil dimasukkan ke keranjang');
    }

    public function keranjang()
    {
        $data['keranjang'] = DetailTransaksi::where('user_id', Auth::id())->where('status', 'keranjang')->with('produk')->get();

        return view('keranjang')->with($data);
    }

    public function bayar(Request $request, $id)
    {
        $data['detailTransaksi'] = DetailTransaksi::find($id);
        $data['produk'] = $data['detailTransaksi']->produk;

        return view('bayar')->with($data);
    }

    public function prosesBayar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bukti_transaksi' => 'required|file'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $detailTransaksi = DetailTransaksi::find($id);

        $transaksi = Transaksi::create([
            'user_id' => Auth::id(),
            'total_harga' => $detailTransaksi->total_harga,
            'kode' => 'INV' . Str::random(8)
        ]);

        $detailTransaksi->update([
            'transaksi_id' => $transaksi->id,
            'status' => 'checkout',
            'bukti_transaksi' => $request->bukti_transaksi->store('images')
        ]);

        return redirect()->route('index.summary')->with('status', 'Produk berhasil dibayar');
    }

    public function summary(Request $request)
    {
        $data['detailTransaksi'] = DetailTransaksi::where('user_id', Auth::id())->where('status', 'checkout')->get();

        return view('summary')->with($data);
    }
}
