<script setup>
import { ref } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import { ShieldCheck, Mail, Lock, Eye, EyeOff, AlertCircle, ArrowRight, UserCheck } from 'lucide-vue-next';

defineProps({
    status: { type: String, default: null },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};

const fillDemoAccount = (email, password) => {
    form.email = email;
    form.password = password;
};

const demoAccounts = [
    { label: 'Super Admin',    email: 'admin@nkri.co.id',   password: 'PortalPassword123!', role: 'SA' },
    { label: 'Admin Sistem',   email: 'budi.it@nkri.co.id', password: 'UserPassword123!',   role: 'AS' },
    { label: 'Admin Konten',   email: 'siti.hr@nkri.co.id', password: 'UserPassword123!',   role: 'AK' },
    { label: 'User Keuangan',  email: 'andi.fin@nkri.co.id',password: 'UserPassword123!',   role: 'U' },
];
</script>

<template>
    <Head title="Masuk — Portal NKRI" />

    <!--
        Layout: latar abu sangat muda (#F9FAFB / bg-gray-50),
        card terpusat melayang dengan shadow lembut.
        Ini lebih elegan dari split-screen flat.
    -->
    <div class="min-h-screen bg-[#F5F5F7] flex flex-col items-center justify-center px-4 py-10">

        <!-- ══════════════════════════════════════════════════════════
             KARTU UTAMA — 2 kolom di desktop, 1 kolom di mobile
        ═══════════════════════════════════════════════════════════ -->
        <div class="w-full max-w-4xl bg-white rounded-3xl shadow-[0_8px_48px_-8px_rgba(0,0,0,0.12)] overflow-hidden flex flex-col lg:flex-row">

            <!-- ─────────────────────────────────────────────────────
                 PANEL KIRI — Branding, merah korporat, elegan
            ───────────────────────────────────────────────────── -->
            <div class="relative lg:w-[42%] bg-primary flex flex-col justify-between p-10 overflow-hidden min-h-[220px] lg:min-h-0">

                <!--
                    Dekorasi geometrik halus:
                    lingkaran besar di sudut, translucent — tidak mencolok
                -->
                <!-- Lingkaran besar kanan bawah -->
                <div class="pointer-events-none absolute -bottom-20 -right-20
                            w-72 h-72 rounded-full border border-white/[0.12]" />
                <!-- Lingkaran sedang kiri atas -->
                <div class="pointer-events-none absolute -top-16 -left-16
                            w-56 h-56 rounded-full border border-white/[0.10]" />
                <!-- Lingkaran kecil solid, sangat samar -->
                <div class="pointer-events-none absolute top-1/2 right-6
                            -translate-y-1/2 w-28 h-28 rounded-full bg-white/[0.06]" />
                <!-- Garis diagonal halus (texture) -->
                <div class="pointer-events-none absolute inset-0 opacity-[0.03]"
                     style="background-image: repeating-linear-gradient(
                                -45deg,
                                #fff 0px,
                                #fff 1px,
                                transparent 1px,
                                transparent 24px
                            );" />

                <!-- LOGO -->
                <Link href="/" class="relative z-10 inline-flex items-center gap-3 group w-fit">
                    <div class="w-10 h-10 rounded-2xl bg-white p-1.5 shadow-sm flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
                        <img src="/images/logo-emblem.png" alt="Logo NKRI" class="w-full h-full object-contain" />
                    </div>
                    <div>
                        <span class="block text-[15px] font-bold text-white tracking-tight leading-none">
                            Portal NKRI
                        </span>
                        <span class="block text-[9.5px] text-white/70 uppercase tracking-wider mt-0.5">
                            Nanggala Kencana Rekatama
                        </span>
                    </div>
                </Link>

                <!-- TEKS UTAMA -->
                <div class="relative z-10 mt-10 lg:mt-0 space-y-4">
                    <!-- Label keamanan di atas judul -->
                    <!-- <div class="flex flex-wrap gap-2">
                        <span class="inline-block text-[10px] font-semibold uppercase tracking-[0.16em]
                                     text-white/80 border border-white/20 bg-white/10 rounded-full px-3 py-0.5">
                            Sistem Intranet Terpadu
                        </span>
                        <span class="inline-block text-[10px] font-semibold uppercase tracking-[0.16em]
                                     text-amber-200 border border-amber-300/30 bg-amber-400/10 rounded-full px-2.5 py-0.5">
                            Akses Terbatas & Rahasia
                        </span>
                    </div> -->

                    <h1 class="text-3xl lg:text-[2.1rem] font-extrabold text-white leading-[1.18] tracking-tight">
                        Portal Layanan<br/>Seluruh Divisi.
                    </h1>

                    <p class="text-[13px] text-white/75 leading-relaxed max-w-[28ch]">
                        Gerbang penyimpan sistem resmi <br> PT Nanggala Kencana Rekatama Indonesia.
                    </p>

                    <!-- Tiga poin keunggulan & keamanan -->
                    <!-- <ul class="space-y-2.5 pt-1">
                        <li v-for="(item, i) in [
                            'Otorisasi ketat berbasis divisi & wewenang jabatan',
                            'Perlindungan integritas operasional manufaktur rahasia',
                            'Pengawasan sesi & audit peluncuran terenkripsi',
                        ]" :key="i" class="flex items-center gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-300 shrink-0" />
                            <span class="text-[12px] text-white/80">{{ item }}</span>
                        </li>
                    </ul> -->
                </div>

                <!-- COPYRIGHT BAWAH -->
                <p class="relative z-10 mt-10 lg:mt-0 text-[10px] text-white/35">
                    &copy; {{ new Date().getFullYear() }} Portal Sistem Digital NKRI
                </p>
            </div>

            <!-- ─────────────────────────────────────────────────────
                 PANEL KANAN — Form login
            ───────────────────────────────────────────────────── -->
            <div class="flex-1 flex flex-col justify-center px-8 py-10 sm:px-10 lg:px-12">

                <!-- Mobile logo (hanya tampil < lg) -->
                <div class="flex lg:hidden items-center gap-3 mb-8">
                    <img src="/images/logo-emblem.png" alt="Logo NKRI" class="w-9 h-9 object-contain" />
                    <div>
                        <span class="block text-base font-bold text-content leading-none">Portal NKRI</span>
                        <span class="block text-[10px] text-content-subtle uppercase tracking-wider mt-0.5">Nanggala Kencana Rekatama</span>
                    </div>
                </div>

                <!-- Judul form -->
                <div class="mb-7">
                    <h2 class="text-[2.3rem] font-bold text-content tracking-tight">Masuk</h2>
                </div>

                <!-- Notifikasi status (misal: setelah logout) -->
                <div v-if="status"
                     class="mb-5 flex items-start gap-2.5 px-4 py-3
                            rounded-2xl bg-emerald-50 border border-emerald-100
                            text-[12.5px] text-emerald-800">
                    {{ status }}
                </div>

                <!-- Alert error global -->
                <div v-if="form.errors.email && !form.email"
                     class="mb-5 flex items-start gap-2.5 px-4 py-3
                            rounded-2xl bg-primary-soft border border-primary-ring/60
                            text-[12.5px] text-primary">
                    <AlertCircle class="w-4 h-4 shrink-0 mt-0.5" />
                    <span>{{ form.errors.email }}</span>
                </div>

                <!-- ─── FORM ─── -->
                <form @submit.prevent="submit" class="space-y-4" novalidate>

                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-[11px] font-semibold
                                                   text-content-muted uppercase tracking-widest">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <Mail class="absolute left-3.5 top-1/2 -translate-y-1/2
                                        w-[15px] h-[15px] text-content-subtle pointer-events-none" />
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                autocomplete="username"
                                required
                                placeholder="nama@gmail.com"
                                :class="[
                                    'w-full pl-10 pr-4 py-3 text-[13.5px] text-content',
                                    'bg-[#F9FAFB] border rounded-2xl outline-none transition-all duration-200',
                                    'placeholder:text-content-subtle/50',
                                    'focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary/60',
                                    form.errors.email
                                        ? 'border-primary/70 bg-primary-soft'
                                        : 'border-border hover:border-content-subtle/60'
                                ]"
                            />
                        </div>
                        <p v-if="form.errors.email"
                           class="flex items-center gap-1.5 text-[11.5px] text-primary pl-1">
                            <AlertCircle class="w-3 h-3 shrink-0" />
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-[11px] font-semibold
                                                         text-content-muted uppercase tracking-widest">
                                Kata Sandi
                            </label>
                            <span class="text-[10.5px] text-content-subtle">
                                Kebijakan sandi aktif
                            </span>
                        </div>
                        <div class="relative">
                            <Lock class="absolute left-3.5 top-1/2 -translate-y-1/2
                                        w-[15px] h-[15px] text-content-subtle pointer-events-none" />
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                autocomplete="current-password"
                                required
                                placeholder=""
                                :class="[
                                    'w-full pl-10 pr-11 py-3 text-[13.5px] text-content',
                                    'bg-[#F9FAFB] border rounded-2xl outline-none transition-all duration-200',
                                    'placeholder:text-content-subtle/40',
                                    'focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary/60',
                                    form.errors.password
                                        ? 'border-primary/70 bg-primary-soft'
                                        : 'border-border hover:border-content-subtle/60'
                                ]"
                            />
                            <button
                                type="button"
                                tabindex="-1"
                                @click="showPassword = !showPassword"
                                :title="showPassword ? 'Sembunyikan' : 'Tampilkan'"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2
                                       text-content-subtle hover:text-content
                                       transition-colors duration-150 focus:outline-none"
                            >
                                <EyeOff v-if="showPassword" class="w-4 h-4" />
                                <Eye v-else class="w-4 h-4" />
                            </button>
                        </div>
                        <p v-if="form.errors.password"
                           class="flex items-center gap-1.5 text-[11.5px] text-primary pl-1">
                            <AlertCircle class="w-3 h-3 shrink-0" />
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Remember me -->
                    <div class="flex items-center gap-2.5 pt-0.5">
                        <input
                            id="remember"
                            v-model="form.remember"
                            type="checkbox"
                            class="w-4 h-4 rounded-md border-border text-primary
                                   focus:ring-primary/30 focus:ring-offset-0 cursor-pointer"
                        />
                        <label for="remember" class="text-[12.5px] text-content-muted cursor-pointer select-none">
                            Ingat sesi saya di perangkat ini
                        </label>
                    </div>

                    <!-- Tombol submit -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full mt-1 flex items-center justify-center gap-2
                               py-3.5 rounded-2xl text-[13.5px] font-semibold text-white
                               bg-primary hover:bg-primary-hover
                               shadow-[0_4px_16px_-4px_rgba(185,28,28,0.5)]
                               hover:shadow-[0_6px_20px_-4px_rgba(185,28,28,0.6)]
                               focus:outline-none focus:ring-2 focus:ring-primary/30 focus:ring-offset-2
                               transition-all duration-200
                               disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none"
                    >
                        <span v-if="form.processing">Memverifikasi...</span>
                        <template v-else>
                            <span>Masuk ke Portal</span>
                            <ArrowRight class="w-4 h-4" />
                        </template>
                    </button>
                </form>


                <!-- Footer helpdesk -->
                <p class="mt-6 text-center text-[11.5px] text-content-subtle">
                    Lupa sandi atau kendala akses?
                    <span class="font-semibold text-content-muted">Hubungi Helpdesk IT</span>
                </p>
            </div>
        </div>

        <!-- Tagline di bawah kartu -->
        <p class="mt-6 text-[11px] text-gray-400">
            Portal Sistem Digital NKRI
        </p>
    </div>
</template>
