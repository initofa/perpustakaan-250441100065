<?php

namespace App\Providers;

use App\Models\Peminjaman;
use App\Models\User;
use App\Policies\PeminjamanPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // [Modul 7] Di Vercel, HTTPS dihentikan di lapisan depan sehingga Laravel mengira diakses lewat HTTP.
        // Paksa semua URL (url(), route(), asset()) memakai https agar CSS/JS tidak diblokir (mixed content).
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Peminjaman::class, PeminjamanPolicy::class);

        // [SOAL 3] Aturan pengelolaan buku dipusatkan di SATU tempat.
        // Hanya role admin dan petugas yang boleh menambah, mengubah, dan menghapus buku.
        Gate::define('kelola-buku', function (User $user) {
            return $user->hasAnyRole(['admin', 'petugas']);
        });
    }
}
