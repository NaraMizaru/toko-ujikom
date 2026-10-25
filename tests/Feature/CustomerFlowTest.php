<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomerFlowTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_customer_can_checkout_and_status_menjadi_dibayar(): void
    {
        Storage::fake('local');

        $user = User::factory()->create(['no_hp' => '081234567890']);
        $produk = $this->produk();

        $this->actingAs($user)
            ->post(route('post.keranjang', $produk->id), ['qty' => 2])
            ->assertRedirect(route('index.home'));

        $item = Transaksi::where('user_id', $user->id)
            ->where('status', 'keranjang')
            ->firstOrFail()
            ->items()
            ->firstOrFail();

        $this->actingAs($user)
            ->post(route('proses.bayar'), [
                'bukti_transaksi' => UploadedFile::fake()->image('bukti.jpg'),
                'item_ids' => [$item->id],
            ])
            ->assertRedirect(route('index.summary'));

        $transaksi = Transaksi::where('user_id', $user->id)
            ->where('status', 'dibayar')
            ->firstOrFail();

        $this->assertSame(200000, (int) $transaksi->total_harga);
        $this->assertNotNull($transaksi->bukti_transaksi);

        $this->actingAs($user)
            ->get(route('index.summary'))
            ->assertOk()
            ->assertSee('Menunggu Verifikasi');
    }

    public function test_checkout_diblokir_jika_no_hp_kosong(): void
    {
        $user = User::factory()->create(['no_hp' => '']);
        $produk = $this->produk();

        $this->actingAs($user)
            ->post(route('post.keranjang', $produk->id), ['qty' => 1]);

        $item = Transaksi::where('user_id', $user->id)
            ->where('status', 'keranjang')
            ->firstOrFail()
            ->items()
            ->firstOrFail();

        $this->actingAs($user)
            ->get(route('index.bayar', ['items' => [$item->id]]))
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors('no_hp');
    }

    public function test_admin_tidak_bisa_masuk_keranjang_atau_checkout(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $produk = $this->produk();

        $this->actingAs($admin)
            ->get(route('index.keranjang'))
            ->assertRedirect(route('admin.dashboard'));

        $this->actingAs($admin)
            ->post(route('post.keranjang', $produk->id), ['qty' => 1])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseCount('transaksis', 0);
    }

    public function test_customer_tidak_bisa_masuk_admin_panel(): void
    {
        $customer = User::factory()->create(['no_hp' => '081234567890']);

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('index.home'));
    }

    public function test_customer_hanya_bisa_melihat_bukti_miliknya_sendiri(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('images/bukti.jpg', 'bukti');

        $owner = User::factory()->create(['no_hp' => '081234567890']);
        $other = User::factory()->create(['no_hp' => '081234567891']);
        $admin = User::factory()->create(['role' => 'admin']);

        $transaksi = Transaksi::create([
            'user_id' => $owner->id,
            'total_harga' => 100000,
            'status' => 'dibayar',
            'kode' => 'INVTEST01',
            'bukti_transaksi' => 'images/bukti.jpg',
        ]);

        $this->actingAs($owner)->get(route('transaksi.bukti', $transaksi))->assertOk();
        $this->actingAs($other)->get(route('transaksi.bukti', $transaksi))->assertForbidden();
        $this->actingAs($admin)->get(route('transaksi.bukti', $transaksi))->assertOk();
    }

    public function test_customer_bisa_update_profil_dan_no_hp_wajib_valid(): void
    {
        $user = User::factory()->create(['no_hp' => '']);

        $this->actingAs($user)
            ->put(route('profile.update'), ['name' => 'Customer Baru', 'no_hp' => 'abc'])
            ->assertSessionHasErrors('no_hp');

        $this->actingAs($user)
            ->put(route('profile.update'), ['name' => 'Customer Baru', 'no_hp' => '0812-3456-7890'])
            ->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Customer Baru',
            'no_hp' => '0812-3456-7890',
        ]);
    }
}
