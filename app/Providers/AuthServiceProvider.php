<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate; // FIX ACTIVATION: Buka gembok import Gate agar bisa dipakai
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Panggil pendaftaran kebijakan bawaan Laravel
        $this->registerPolicies();

        // 1. DEFINISI PINTU OTORISASI ADMIN
        // Mengunci rute agar hanya akun dengan string 'admin' di kolom role yang boleh lewat
        Gate::define('is-admin', function ($user) {
            return $user->role === 'admin';
        });

        // 2. DEFINISI PINTU OTORISASI USER REGULER
        // Mengunci rute khusus terminal member (selain role admin)
        Gate::define('is-user', function ($user) {
            return $user->role !== 'admin';
        });
    }
}