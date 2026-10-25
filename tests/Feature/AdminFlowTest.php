<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminFlowTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'no_hp' => '081234567890',
        ]);
    }

    private function transaksiDibayar(User $customer, Produk $produk): Transaksi
    {
        $transaksi = Transaksi::create([
            'user_id' => $customer->id,
            'total_harga' => $produk->harga,
            'status' => 'dibayar',
            'kode' => 'INV'.uniqid(),
            'bukti_transaksi' => 'images/bukti.jpg',
        ]);

        TransaksiItem::create([
            'transaksi_id' => $transaksi->id,
            'produk_id' => $produk->id,
            'qty' => 1,
            'harga_satuan' => $produk->harga,
        ]);

        return $transaksi;
    }

    private function produk(): Produk
    {
        $kategori = Kategori::create(['name' => 'VGA']);

        return Produk::create([
            'kategori_id' => $kategori->id,
            'name' => 'RTX Test',
            'harga' => 100000,
            'foto' => 'img/test.jpg',
        ]);
    }

    public function test_admin_bisa_menerima_pembayaran(): void
    {
        $customer = User::factory()->create(['no_hp' => '081234567891']);
        $produk = $this->produk();
        $transaksi = $this->transaksiDibayar($customer, $produk);

        $this->actingAs($this->admin())
            ->post(route('admin.transaksi.terima', $transaksi))
            ->assertRedirect(route('admin.transaksi.show', $transaksi));

        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksi->id,
            'status' => 'diterima',
        ]);
    }

    public function test_admin_bisa_menolak_pembayaran_dan_status_final(): void
    {
        $customer = User::factory()->create(['no_hp' => '081234567891']);
        $produk = $this->produk();
        $transaksi = $this->transaksiDibayar($customer, $produk);

        $this->actingAs($this->admin())
            ->post(route('admin.transaksi.tolak', $transaksi))
            ->assertRedirect(route('admin.transaksi.show', $transaksi));

        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksi->id,
            'status' => 'ditolak',
        ]);

        $this->actingAs($this->admin())
            ->post(route('admin.transaksi.terima', $transaksi))
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksi->id,
            'status' => 'ditolak',
        ]);
    }

    public function test_halaman_admin_dapat_dirender(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('images/bukti.jpg', 'bukti');

        $customer = User::factory()->create(['no_hp' => '081234567891']);
        $produk = $this->produk();
        $transaksi = $this->transaksiDibayar($customer, $produk);
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('admin.transaksi.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.transaksi.show', $transaksi))->assertOk();
        $this->actingAs($admin)->get(route('admin.produk.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.produk.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.produk.edit', $produk))->assertOk();
        $this->actingAs($admin)->get(route('admin.kategori.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.kategori.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.kategori.edit', $produk->kategori))->assertOk();
    }

    public function test_admin_bisa_menambah_mengubah_dan_menghapus_produk(): void
    {
        Storage::fake('public');

        $admin = $this->admin();
        $kategori = Kategori::create(['name' => 'VGA']);

        $this->actingAs($admin)
            ->post(route('admin.produk.store'), [
                'name' => 'Produk Baru',
                'kategori_id' => $kategori->id,
                'harga' => 150000,
                'foto' => UploadedFile::fake()->image('produk.jpg'),
            ])
            ->assertRedirect(route('admin.produk.index'));

        $produk = Produk::where('name', 'Produk Baru')->firstOrFail();
        Storage::disk('public')->assertExists($produk->foto);

        $fotoLama = $produk->foto;

        $this->actingAs($admin)
            ->put(route('admin.produk.update', $produk), [
                'name' => 'Produk Diubah',
                'kategori_id' => $kategori->id,
                'harga' => 175000,
                'foto' => UploadedFile::fake()->image('produk-baru.jpg'),
            ])
            ->assertRedirect(route('admin.produk.index'));

        $produk->refresh();
        $this->assertSame('Produk Diubah', $produk->name);
        Storage::disk('public')->assertMissing($fotoLama);
        Storage::disk('public')->assertExists($produk->foto);

        $this->actingAs($admin)
            ->delete(route('admin.produk.destroy', $produk))
            ->assertRedirect(route('admin.produk.index'));

        $this->assertDatabaseMissing('produks', ['id' => $produk->id]);
    }

    public function test_produk_dan_kategori_yang_dipakai_tidak_bisa_dihapus(): void
    {
        $customer = User::factory()->create(['no_hp' => '081234567891']);
        $produk = $this->produk();
        $this->transaksiDibayar($customer, $produk);

        $this->actingAs($this->admin())
            ->delete(route('admin.produk.destroy', $produk))
            ->assertSessionHasErrors('produk');

        $this->assertDatabaseHas('produks', ['id' => $produk->id]);

        $this->actingAs($this->admin())
            ->delete(route('admin.kategori.destroy', $produk->kategori))
            ->assertSessionHasErrors('kategori');

        $this->assertDatabaseHas('kategoris', ['id' => $produk->kategori_id]);
    }

    public function test_admin_bisa_menambah_mengubah_dan_menghapus_kategori(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.kategori.store'), ['name' => 'RAM'])
            ->assertRedirect(route('admin.kategori.index'));

        $kategori = Kategori::where('name', 'RAM')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.kategori.update', $kategori), ['name' => 'Memory'])
            ->assertRedirect(route('admin.kategori.index'));

        $this->assertDatabaseHas('kategoris', ['id' => $kategori->id, 'name' => 'Memory']);

        $this->actingAs($admin)
            ->delete(route('admin.kategori.destroy', $kategori))
            ->assertRedirect(route('admin.kategori.index'));

        $this->assertDatabaseMissing('kategoris', ['id' => $kategori->id]);
    }
}
