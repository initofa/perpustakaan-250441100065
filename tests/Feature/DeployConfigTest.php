<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Memastikan berkas konfigurasi deployment Vercel ada dan benar.
 * Jalankan: php artisan test --filter=DeployConfigTest
 */
class DeployConfigTest extends TestCase
{
    public function test_api_index_meneruskan_ke_front_controller_laravel(): void
    {
        $this->assertFileExists(base_path('api/index.php'));
        $this->assertStringContainsString("/../public/index.php", file_get_contents(base_path('api/index.php')));
    }

    public function test_vercel_json_valid_dan_memakai_runtime_php(): void
    {
        $json = json_decode(file_get_contents(base_path('vercel.json')), true);

        $this->assertSame(JSON_ERROR_NONE, json_last_error());
        $this->assertSame(2, $json['version']);
        $this->assertStringStartsWith('vercel-php@', $json['functions']['api/index.php']['runtime']);
        $this->assertSame('/api/index.php', collect($json['routes'])->last()['dest']);
        $this->assertSame('/tmp/views', $json['env']['VIEW_COMPILED_PATH']);
    }

    public function test_vercelignore_memuat_env_dan_vendor(): void
    {
        $isi = file_get_contents(base_path('.vercelignore'));

        $this->assertStringContainsString('.env', $isi);
        $this->assertStringContainsString('/vendor', $isi);
        $this->assertStringContainsString('node_modules', $isi);
    }

    public function test_env_dan_env_production_masuk_gitignore(): void
    {
        $isi = file_get_contents(base_path('.gitignore'));

        $this->assertMatchesRegularExpression('/^\.env$/m', $isi);
        $this->assertMatchesRegularExpression('/^\.env\.production$/m', $isi);
    }

    public function test_env_example_tidak_memuat_rahasia(): void
    {
        $isi = file_get_contents(base_path('.env.example'));

        $this->assertMatchesRegularExpression('/^APP_KEY=$/m', $isi);
        $this->assertMatchesRegularExpression('/^DB_PASSWORD=$/m', $isi);
        $this->assertMatchesRegularExpression('/^ADMIN_PASSWORD=$/m', $isi);
    }

    public function test_koneksi_pgsql_mengaktifkan_emulasi_prepared_statement(): void
    {
        $this->assertTrue(config('database.connections.pgsql.options')[\PDO::ATTR_EMULATE_PREPARES]);
    }

    public function test_url_dipaksa_https_di_production(): void
    {
        $this->app['env'] = 'production';
        $this->app->make(\App\Providers\AppServiceProvider::class, ['app' => $this->app])->boot();

        $this->assertStringStartsWith('https://', url('/login'));
    }
}
