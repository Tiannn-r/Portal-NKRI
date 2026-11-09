<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
    ShieldCheck, 
    Search, 
    ArrowRight, 
    SlidersHorizontal, 
    Building2, 
    Layers, 
    ExternalLink, 
    CheckCircle2, 
    Sparkles, 
    Users, 
    Package, 
    Receipt, 
    HelpCircle 
} from 'lucide-vue-next';

const props = defineProps({
    auth: {
        type: Object,
        default: () => ({ user: null }),
    },
    categories: {
        type: Array,
        default: () => [],
    },
    featuredApps: {
        type: Array,
        default: () => [],
    },
});

// State untuk filter pencarian
const searchQuery = ref('');
const selectedCategory = ref('');

// Fallback jika database belum ada data
const displayApps = computed(() => {
    if (props.featuredApps && props.featuredApps.length >= 3) {
        return props.featuredApps.slice(0, 3);
    }
    return [
        {
            id: 1,
            name: 'HRIS & Portal Kepegawaian',
            slug: 'hris-portal-kepegawaian',
            description: 'Kelola data karyawan, presensi mandiri, pengajuan cuti tahunan, dan slip gaji elektronik secara terpusat.',
            category: { name: 'SDM & Personalia' },
            image: 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80',
        },
        {
            id: 2,
            name: 'ERP Supply Chain & Logistik',
            slug: 'erp-supply-chain-inventory',
            description: 'Platform operasional terpadu pemantauan stok pergudangan, distribusi armada, dan purchase order terhubung.',
            category: { name: 'Operasional' },
            image: 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=800&q=80',
        },
        {
            id: 3,
            name: 'Sistem Akuntansi & Faktur',
            slug: 'sistem-akuntansi-faktur',
            description: 'Pencatatan pembukuan kas, rekonsiliasi perbankan otomatis, e-Faktur pajak, dan laporan laba rugi real-time.',
            category: { name: 'Finansial' },
            image: 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=800&q=80',
        },
    ];
});

// Gambar kartu dinamis
const getCardImage = (index) => {
    const images = [
        'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=800&q=80',
    ];
    return images[index % images.length];
};

const handleSearch = () => {
    if (props.auth.user) {
        window.location.href = `/dashboard?q=${encodeURIComponent(searchQuery.value)}&cat=${encodeURIComponent(selectedCategory.value)}`;
    } else {
        window.location.href = '/login';
    }
};
</script>

<template>
    <Head title="Portal NKRI - Gerbang Sistem Digital Korporat" />

    <div class="min-h-screen bg-white text-content font-sans antialiased flex flex-col selection:bg-primary-soft selection:text-primary">
        
        <!-- =================================================================================== -->
        <!-- 2. NAVBAR (Bar Navigasi Atas dengan Sudut Atas Membulat, Logo, Menu, & Tombol Kapsul) -->
        <!-- =================================================================================== -->
        <header class="w-full max-w-7xl mx-auto pt-3 px-4 sm:px-6 lg:px-8">
            <nav class="bg-white/95 backdrop-blur border border-border rounded-t-2xl sm:rounded-2xl shadow-sm px-6 py-3.5 flex items-center justify-between transition-all">
                <!-- Brand / Logo di Kiri -->
                <Link href="/" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold shadow-sm group-hover:bg-primary-hover transition-colors">
                        <ShieldCheck class="w-5 h-5 text-white" />
                    </div>
                    <div class="text-left">
                        <span class="block text-lg font-bold tracking-tight text-content leading-none">Portal NKRI</span>
                        <span class="text-[10px] font-semibold text-content-subtle tracking-wider uppercase">Sistem Korporat</span>
                    </div>
                </Link>

                <!-- 4 Menu Navigasi di Tengah -->
                <div class="hidden md:flex items-center space-x-8 text-sm font-medium text-content-muted">
                    <a href="#beranda" class="text-primary font-semibold hover:text-primary-hover transition-colors">Beranda</a>
                    <a href="#layanan" class="hover:text-primary transition-colors">Layanan</a>
                    <a href="#tentang" class="hover:text-primary transition-colors">Tentang Portal</a>
                    <a href="#katalog" class="hover:text-primary transition-colors">Katalog Sistem</a>
                </div>

                <!-- Ikon Pencarian & Tombol CTA Kapsul (Pill Button) Berwarna Primary di Kanan -->
                <div class="flex items-center space-x-3">
                    <button 
                        type="button" 
                        @click="handleSearch"
                        class="p-2 text-content-subtle hover:text-primary rounded-full hover:bg-primary-soft transition-colors"
                        title="Pencarian Cepat"
                    >
                        <Search class="w-5 h-5" />
                    </button>

                    <Link 
                        v-if="auth.user" 
                        href="/dashboard" 
                        class="inline-flex items-center space-x-1.5 px-5 py-2 rounded-full text-xs sm:text-sm font-semibold text-white bg-primary hover:bg-primary-hover shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-ring"
                    >
                        <span>Dashboard Saya</span>
                        <ArrowRight class="w-4 h-4" />
                    </Link>
                    <Link 
                        v-else 
                        href="/login" 
                        class="inline-flex items-center space-x-1.5 px-6 py-2 rounded-full text-xs sm:text-sm font-semibold text-white bg-primary hover:bg-primary-hover shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-ring"
                    >
                        <span>Masuk Portal</span>
                        <ArrowRight class="w-4 h-4" />
                    </Link>
                </div>
            </nav>
        </header>

        <!-- =================================================================================== -->
        <!-- 3. HERO SECTION (Full-Width, Warm Lighting, Badge Kapsul, Judul Tebal, CTA Kapsul) -->
        <!-- =================================================================================== -->
        <section id="beranda" class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-3">
            <div class="relative w-full h-[540px] sm:h-[580px] rounded-3xl overflow-hidden shadow-md flex items-center">
                <!-- Background Image dengan Pencahayaan Hangat (Warm Sunrise / Modern Landscape Architecture) -->
                <img 
                    src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1920&q=80" 
                    alt="Warm Sunrise Landscape Architecture"
                    class="absolute inset-0 w-full h-full object-cover object-center filter brightness-[0.88]"
                />
                
                <!-- Gradient Overlay Hangat yang Halus untuk Keterbacaan Teks -->
                <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/45 to-transparent"></div>

                <!-- Konten Hero Sisi Kiri -->
                <div class="relative z-10 max-w-2xl px-6 sm:px-12 py-12 text-left text-white">
                    <!-- Badge Kecil Berbentuk Kapsul -->
                    <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-white text-xs font-semibold uppercase tracking-wider mb-5">
                        <Sparkles class="w-3.5 h-3.5 text-amber-300" />
                        <span>Gerbang Layanan Digital Korporat</span>
                    </div>

                    <!-- Judul Utama Berukuran Besar Berwarna Putih Tebal -->
                    <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-[1.15] text-white drop-shadow-sm mb-4">
                        Akses Seluruh Sistem Digital dalam Satu Gerbang
                    </h1>

                    <!-- Subjudul Pendek -->
                    <p class="text-sm sm:text-base text-white/90 leading-relaxed font-normal max-w-xl mb-8">
                        Sentralisasi akses operasional harian, mulai dari HRIS, ERP rantai pasok, finansial, hingga dukungan teknis dengan efisiensi tinggi dan keamanan terjamin.
                    </p>

                    <!-- Satu Tombol CTA Berbentuk Kapsul -->
                    <div>
                        <Link 
                            :href="auth.user ? '/dashboard' : '/login'" 
                            class="inline-flex items-center space-x-2 px-8 py-3.5 rounded-full text-sm font-bold text-white bg-primary hover:bg-primary-hover shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-primary-ring/50"
                        >
                            <span>{{ auth.user ? 'Buka Dashboard Saya' : 'Mulai Akses Sekarang' }}</span>
                            <ArrowRight class="w-4 h-4" />
                        </Link>
                    </div>
                </div>
            </div>

            <!-- =================================================================================== -->
            <!-- 4. FLOATING SEARCH/FILTER BAR (Container Kapsul Besar Melayang / Overlapping)         -->
            <!-- =================================================================================== -->
            <div class="relative -mt-10 sm:-mt-12 z-20 max-w-4xl mx-auto px-4">
                <!-- Container Kapsul Besar Berwarna Secondary (#FEE2E2 / primary-soft tone) -->
                <div class="bg-primary-soft/90 backdrop-blur-md p-3 sm:p-4 rounded-3xl sm:rounded-full border border-primary-ring/40 shadow-xl">
                    <form @submit.prevent="handleSearch" class="bg-white rounded-2xl sm:rounded-full p-2 flex flex-col sm:flex-row items-center gap-2 border border-border shadow-inner">
                        <!-- Kolom Filter 1: Input Pencarian Nama Sistem -->
                        <div class="relative w-full sm:w-1/2 flex items-center px-4 py-1.5 sm:border-r border-border">
                            <Search class="w-4 h-4 text-content-subtle shrink-0 mr-3" />
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Cari nama sistem, aplikasi, atau modul..."
                                class="w-full text-xs sm:text-sm text-content placeholder:text-content-subtle/70 bg-transparent border-none focus:outline-none focus:ring-0"
                            />
                        </div>

                        <!-- Kolom Filter 2: Dropdown Kategori -->
                        <div class="relative w-full sm:w-1/2 flex items-center px-4 py-1.5">
                            <Layers class="w-4 h-4 text-content-subtle shrink-0 mr-3" />
                            <select 
                                v-model="selectedCategory"
                                class="w-full text-xs sm:text-sm text-content bg-transparent border-none focus:outline-none focus:ring-0 cursor-pointer"
                            >
                                <option value="">Semua Kategori Sistem</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.slug">
                                    {{ cat.name }}
                                </option>
                                <option v-if="categories.length === 0" value="sdm">SDM & Kepegawaian</option>
                                <option v-if="categories.length === 0" value="finansial">Finansial & Akuntansi</option>
                                <option v-if="categories.length === 0" value="operasional">Operasional & Supply Chain</option>
                            </select>
                        </div>

                        <!-- Ikon Aksi Kecil & Tombol Pencarian Kapsul Berwarna Primary di Kanan -->
                        <div class="w-full sm:w-auto shrink-0 flex items-center space-x-2">
                            <button 
                                type="submit" 
                                class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 px-7 py-3 rounded-full text-xs sm:text-sm font-semibold text-white bg-primary hover:bg-primary-hover shadow-md transition-all focus:outline-none focus:ring-2 focus:ring-primary-ring"
                            >
                                <span>Cari Sistem</span>
                                <Search class="w-3.5 h-3.5" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- =================================================================================== -->
        <!-- 5. ABOUT/INTRO SECTION (2 Kolom: Kiri Gambar Berbingkai + Badge, Kanan Teks + CTA)  -->
        <!-- =================================================================================== -->
        <section id="tentang" class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                
                <!-- Kolom Kiri: Gambar Persegi Panjang dengan Bingkai Putih + Overlapping Badges -->
                <div class="lg:col-span-5 relative flex justify-center">
                    <div class="relative w-full max-w-md">
                        <!-- Bingkai Putih Tebal & Sudut Membulat -->
                        <div class="bg-white p-2.5 sm:p-3 rounded-2xl shadow-xl border border-border/80">
                            <img 
                                src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80" 
                                alt="Modern Workspace Architecture"
                                class="w-full h-72 sm:h-80 object-cover rounded-xl"
                            />
                        </div>

                        <!-- Badge Bulat Besar Berwarna Primary (Overlap di Sudut Kiri Atas Gambar) -->
                        <div class="absolute -top-5 -left-4 sm:-top-6 sm:-left-6 w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-primary text-white flex flex-col items-center justify-center shadow-lg border-4 border-white transition-transform hover:scale-105">
                            <ShieldCheck class="w-6 h-6 sm:w-7 sm:h-7" />
                            <span class="text-[9px] font-bold tracking-tight uppercase mt-0.5">TERPERCAYA</span>
                        </div>

                        <!-- Badge Ikon Bulat Kecil (Overlap di Sudut Kanan Bawah Gambar) -->
                        <div class="absolute -bottom-4 -right-3 sm:-bottom-5 sm:-right-4 w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-white text-primary border-2 border-primary-ring shadow-lg flex items-center justify-center">
                            <Sparkles class="w-6 h-6 text-primary" />
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Teks & Informasi Singkat -->
                <div class="lg:col-span-7 text-left space-y-5">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-primary-soft text-primary text-xs font-semibold uppercase tracking-wider">
                        <Building2 class="w-3.5 h-3.5" />
                        <span>Mengenal Portal NKRI</span>
                    </div>

                    <h2 class="text-2xl sm:text-4xl font-bold text-content tracking-tight leading-tight">
                        Pusat Kendali Seluruh Aplikasi Korporat Secara Efisien & Aman
                    </h2>

                    <p class="text-sm sm:text-base text-content-muted leading-relaxed">
                        Portal NKRI dirancang khusus sebagai peluncur tunggal (*digital launcher*) untuk mempermudah seluruh karyawan mengakses instrumen kerja harian tanpa perlu mencatat puluhan alamat website yang terpisah.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-2">
                        <div class="flex items-start space-x-3">
                            <CheckCircle2 class="w-5 h-5 text-primary shrink-0 mt-0.5" />
                            <div>
                                <h4 class="text-xs font-bold text-content uppercase tracking-wider">Tanpa Hambatan</h4>
                                <p class="text-xs text-content-subtle mt-0.5">Satu klik untuk meluncurkan sistem target langsung di tab baru.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <CheckCircle2 class="w-5 h-5 text-primary shrink-0 mt-0.5" />
                            <div>
                                <h4 class="text-xs font-bold text-content uppercase tracking-wider">Akses Terkelola</h4>
                                <p class="text-xs text-content-subtle mt-0.5">Matriks hak akses disesuaikan dengan divisi dan wewenang kerja Anda.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol CTA Utama Berbentuk Kapsul & Tautan Teks Sekunder Berdampingan -->
                    <div class="pt-2 flex flex-wrap items-center gap-4">
                        <Link 
                            :href="auth.user ? '/dashboard' : '/login'" 
                            class="inline-flex items-center space-x-2 px-7 py-3 rounded-full text-sm font-semibold text-white bg-primary hover:bg-primary-hover shadow-md hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-primary-ring"
                        >
                            <span>Eksplorasi Sekarang</span>
                            <ArrowRight class="w-4 h-4" />
                        </Link>

                        <a 
                            href="#katalog" 
                            class="inline-flex items-center space-x-1.5 text-sm font-semibold text-content-muted hover:text-primary transition-colors py-2 px-3"
                        >
                            <span>Lihat Katalog Sistem</span>
                            <ArrowRight class="w-4 h-4" />
                        </a>
                    </div>
                </div>

            </div>
        </section>

        <!-- =================================================================================== -->
        <!-- 6. FEATURED CARD GRID SECTION (Latar Background Sekunder, Grid 3 Kartu, Pagination) -->
        <!-- =================================================================================== -->
        <section id="katalog" class="w-full bg-surface py-20 border-t border-border">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                
                <!-- Judul di Tengah Atas -->
                <div class="max-w-2xl mx-auto mb-14">
                    <span class="text-xs font-semibold text-primary uppercase tracking-widest bg-primary-soft px-3 py-1 rounded-full">
                        Sistem Terintegrasi
                    </span>
                    <h2 class="mt-3 text-2xl sm:text-3xl font-extrabold text-content tracking-tight">
                        Aplikasi Unggulan Perusahaan
                    </h2>
                    <p class="mt-2 text-xs sm:text-sm text-content-muted">
                        Daftar sistem inti yang paling sering digunakan untuk menunjang kegiatan operasional harian.
                    </p>
                </div>

                <!-- Grid 3 Kolom Kartu Berwarna Putih dengan Sudut Membulat (rounded-2xl) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div 
                        v-for="(app, idx) in displayApps" 
                        :key="app.id"
                        class="bg-white border border-border rounded-2xl overflow-hidden shadow-card hover:shadow-card-hover hover:border-primary-ring/70 transition-all flex flex-col text-left group"
                    >
                        <!-- Gambar di Bagian Atas Kartu -->
                        <div class="relative h-48 w-full overflow-hidden bg-slate-100">
                            <img 
                                :src="getCardImage(idx)" 
                                :alt="app.name"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            <!-- Badge Kategori di Atas Gambar -->
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-white/90 backdrop-blur text-content shadow-sm">
                                    {{ app.category?.name || 'Sistem Utama' }}
                                </span>
                            </div>
                        </div>

                        <!-- Isi Kartu: Judul Tebal & Teks Deskripsi 2 Baris -->
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-base font-bold text-content group-hover:text-primary transition-colors line-clamp-1 mb-2">
                                    {{ app.name }}
                                </h3>
                                <p class="text-xs text-content-muted leading-relaxed line-clamp-2 mb-6">
                                    {{ app.description }}
                                </p>
                            </div>

                            <!-- Tombol Kapsul Berwarna Primary di Bagian Bawah -->
                            <div class="pt-4 border-t border-border/60 flex items-center justify-between">
                                <span class="text-[11px] font-medium text-content-subtle">
                                    Status: <span class="text-emerald-600 font-semibold">Aktif</span>
                                </span>

                                <a 
                                    :href="auth.user ? `/apps/${app.slug}/open` : '/login'"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center space-x-1.5 px-5 py-2 rounded-full text-xs font-semibold text-white bg-primary hover:bg-primary-hover shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-primary-ring"
                                >
                                    <span>Buka Sistem</span>
                                    <ExternalLink class="w-3.5 h-3.5" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Indikator Titik (Pagination Dots) di Bawah Grid -->
                <div class="mt-12 flex items-center justify-center space-x-2">
                    <span class="w-6 h-2 rounded-full bg-primary transition-all"></span>
                    <span class="w-2 h-2 rounded-full bg-border hover:bg-content-subtle cursor-pointer transition-all"></span>
                    <span class="w-2 h-2 rounded-full bg-border hover:bg-content-subtle cursor-pointer transition-all"></span>
                    <span class="w-2 h-2 rounded-full bg-border hover:bg-content-subtle cursor-pointer transition-all"></span>
                </div>

            </div>
        </section>

        <!-- =================================================================================== -->
        <!-- FOOTER SEDERHANA                                                                    -->
        <!-- =================================================================================== -->
        <footer class="bg-white border-t border-border py-8 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-content-subtle">
                <div class="flex items-center space-x-2">
                    <ShieldCheck class="w-4 h-4 text-primary" />
                    <span class="font-semibold text-content">Portal Sistem Digital NKRI</span>
                    <span>&bull;</span>
                    <span>Akses Launcher Korporat Mandiri</span>
                </div>
                <div>
                    &copy; {{ new Date().getFullYear() }} Seluruh Hak Cipta Dilindungi.
                </div>
            </div>
        </footer>

    </div>
</template>
