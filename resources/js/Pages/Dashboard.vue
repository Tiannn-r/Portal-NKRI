<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ShieldCheck, LogOut, ExternalLink, Building2, User, KeyRound, Sparkles } from 'lucide-vue-next';

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
});

const handleLogout = () => {
    router.post('/logout');
};

const openAdminPanel = () => {
    window.location.href = '/admin';
};
</script>

<template>
    <Head title="Dashboard - Portal NKRI" />

    <div class="min-h-screen bg-surface flex flex-col font-sans">
        <!-- Top Navigation Bar -->
        <header class="bg-white border-b border-border sticky top-0 z-30">
            <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-lg bg-primary text-white flex items-center justify-center font-bold shadow-sm">
                        <ShieldCheck class="w-5 h-5 text-white" />
                    </div>
                    <div>
                        <span class="block text-base font-bold text-content leading-none">PORTAL NKRI</span>
                        <span class="text-[10px] font-semibold text-content-subtle tracking-wider uppercase">Launcher Digital</span>
                    </div>
                </div>

                <!-- User Profile & Actions -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <!-- Tombol Masuk Panel Admin (Jika Role Admin) -->
                    <button
                        v-if="auth.user.can_access_admin"
                        type="button"
                        @click="openAdminPanel"
                        class="inline-flex items-center space-x-1.5 px-3 py-1.5 text-xs font-semibold rounded-md bg-primary-soft text-primary border border-primary-ring/50 hover:bg-primary hover:text-white transition-colors"
                        title="Buka CMS Panel Admin Filament"
                    >
                        <KeyRound class="w-3.5 h-3.5" />
                        <span class="hidden sm:inline">Panel Admin CMS</span>
                        <ExternalLink class="w-3 h-3" />
                    </button>

                    <!-- User Info & Avatar -->
                    <div class="flex items-center space-x-2 pl-2 border-l border-border">
                        <div class="w-8 h-8 rounded-full bg-primary-soft text-primary font-bold text-xs flex items-center justify-center border border-primary-ring/40">
                            {{ auth.user.name.charAt(0) }}
                        </div>
                        <div class="hidden md:block text-left">
                            <div class="text-xs font-semibold text-content leading-tight">{{ auth.user.name }}</div>
                            <div class="text-[10px] text-content-subtle leading-tight">{{ auth.user.department }}</div>
                        </div>
                    </div>

                    <!-- Tombol Keluar -->
                    <button
                        type="button"
                        @click="handleLogout"
                        class="inline-flex items-center space-x-1 p-2 sm:px-3 sm:py-1.5 text-xs font-medium text-content-muted hover:text-primary hover:bg-primary-soft rounded-md border border-transparent hover:border-primary-ring/30 transition-colors"
                        title="Keluar dari sesi portal"
                    >
                        <LogOut class="w-4 h-4" />
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 max-w-[1200px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Greeting & Profile Banner -->
            <div class="bg-white border border-border rounded-lg p-6 mb-8 shadow-card flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-content flex items-center space-x-2">
                        <span>Selamat datang, {{ auth.user.name }}</span>
                    </h1>
                    <p class="text-xs text-content-muted mt-1">
                        Anda telah berhasil masuk ke sesi aman Portal Sistem Digital Korporat.
                    </p>
                </div>

                <!-- Badges -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-surface border border-border text-content-muted">
                        <Building2 class="w-3.5 h-3.5 text-content-subtle" />
                        <span>Divisi: {{ auth.user.department }}</span>
                    </span>
                    <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-primary-soft border border-primary-ring/60 text-primary">
                        <User class="w-3.5 h-3.5 text-primary" />
                        <span>Role: {{ auth.user.roles.join(', ') }}</span>
                    </span>
                </div>
            </div>

            <!-- Card Info Autentikasi Langkah 2 Sukses -->
            <div class="bg-white border border-border rounded-lg p-8 text-center shadow-card">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 border border-emerald-200">
                    <Sparkles class="w-6 h-6" />
                </div>
                <h2 class="text-lg font-bold text-content mb-2">
                    Langkah 2: Sistem Autentikasi & Role Sukses Diterapkan
                </h2>
                <p class="text-xs text-content-muted max-w-lg mx-auto mb-6 leading-relaxed">
                    Sesi pengguna telah terautentikasi dengan aman melalui FormRequest (rate limit 5x, password policy, verifikasi akun aktif), session regeneration, dan integrasi Spatie Role.
                </p>

                <div class="inline-flex flex-col sm:flex-row items-center justify-center gap-3 text-xs">
                    <div class="px-3 py-2 rounded bg-surface border border-border text-content-muted">
                        Email Login: <strong class="text-content">{{ auth.user.email }}</strong>
                    </div>
                    <div class="px-3 py-2 rounded bg-surface border border-border text-content-muted">
                        Hak Akses Admin CMS: <strong class="text-content">{{ auth.user.can_access_admin ? 'Ya (Aktif)' : 'Tidak' }}</strong>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-border text-xs text-content-subtle">
                    Siap melanjutkan ke <strong>Langkah 3 (AccessResolver & Automated Testing)</strong> dan <strong>Langkah 4 (Panel Admin Filament)</strong>.
                </div>
            </div>
        </main>
    </div>
</template>
