# Catatan Deployment (Vercel + Supabase)

Berkas `.env` **tidak** ikut di-commit maupun di-deploy. Semua konfigurasi didaftarkan di
**Vercel > Settings > Environment Variables** (environment: Production). Setelah mengubah variabel,
lakukan **Redeploy** karena perubahan hanya terbaca oleh deployment baru.

| Variabel | Nilai | Rahasia? | Fungsi |
|---|---|---|---|
| `APP_NAME` | `Perpustakaan Digital` | tidak | Nama aplikasi |
| `APP_ENV` | `production` | tidak | Mode lingkungan |
| `APP_KEY` | (hasil `php artisan key:generate --show`) | **ya** | Kunci enkripsi session/cookie |
| `APP_DEBUG` | `false` | tidak | Sembunyikan detail error dari pengguna |
| `APP_URL` | `https://<nama-project>.vercel.app` | tidak | URL aplikasi |
| `DB_CONNECTION` | `pgsql` | tidak | Driver PostgreSQL |
| `DB_HOST` | `aws-0-<region>.pooler.supabase.com` | tidak | Alamat pooler Supabase |
| `DB_PORT` | `6543` | tidak | Transaction pooler (khusus Vercel) |
| `DB_DATABASE` | `postgres` | tidak | Nama database |
| `DB_USERNAME` | `postgres.<project-ref>` | tidak | Pengguna database (format pooler) |
| `DB_PASSWORD` | (password database) | **ya** | Password database |
| `DB_SSLMODE` | `require` | tidak | Koneksi terenkripsi |
| `SESSION_DRIVER` | `database` | tidak | Session disimpan di tabel `sessions` (filesystem Vercel read-only) |

Nilai bertanda **ya** tidak boleh ditulis di repositori, laporan, maupun tangkapan layar.
