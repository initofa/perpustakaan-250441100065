<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pencarian harus tidak peduli huruf besar/kecil, baik di MySQL/SQLite (lokal)
 * maupun PostgreSQL (production Supabase, yang LIKE-nya case-sensitive).
 */
class PencarianBukuTest extends TestCase
{
    use RefreshDatabase;

    public function test_pencarian_judul_tidak_peduli_huruf_besar_kecil(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('siswa');

        $kategori = Kategori::create(['nama' => 'Pemrograman']);
        Buku::create(['kategori_id' => $kategori->id, 'judul' => 'Belajar Laravel', 'penulis' => 'Budi Santoso', 'tahun_terbit' => 2024]);

        foreach (['laravel', 'LARAVEL', 'Laravel', 'budi santoso'] as $kata) {
            $this->actingAs($user)->get('/buku?q='.urlencode($kata))->assertOk()->assertSee('Belajar Laravel');
        }
    }
}
