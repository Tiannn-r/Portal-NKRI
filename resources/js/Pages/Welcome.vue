<script setup>
import { ShieldCheck } from 'lucide-vue-next';

defineProps({
    appName: {
        type: String,
        default: 'Portal NKRI',
    },
});
</script>

<template>
    <div class="min-h-screen bg-surface flex flex-col justify-center items-center px-4">
        <div class="max-w-md w-full bg-white border border-border rounded-lg p-8 shadow-card text-center">
            <div class="w-12 h-12 rounded-lg bg-primary-soft text-primary flex items-center justify-center mx-auto mb-4">
                <ShieldCheck class="w-6 h-6" />
            </div>
            <h1 class="text-xl font-semibold text-content mb-2">{{ appName }}</h1>
            <p class="text-sm text-content-muted mb-6">
                Gerbang Masuk Tunggal Sistem Digital Perusahaan
            </p>
            <div class="text-xs text-content-subtle border-t border-border pt-4">
                Inisialisasi Dasar Berhasil • Merah Putih Korporat
            </div>
        </div>
    </div>
</template>

