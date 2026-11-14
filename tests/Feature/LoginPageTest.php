<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginPageTest extends TestCase
{
    public function test_judul_halaman_login_memuat_nim(): void
    {
        $this->get('/login')->assertOk()->assertSee('Login Perpustakaan Digital - 250441100065');
    }
}
