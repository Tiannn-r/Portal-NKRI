<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import { 
    ShieldCheck, 
    Search, 
    ArrowDown,
    ExternalLink, 
    CheckCircle2, 
    Users, 
    Package, 
    Receipt, 
    Lock,
    Star,
    Bell,
    Calendar,
    KeyRound,
    User,
    LogOut,
    LogIn,
    X,
    Briefcase,
    FileText,
    BarChart3,
    Truck,
    Cpu,
    Boxes,
    FolderKanban,
    AlertTriangle,
    Layers,
    ChevronRight,
    Building
} from 'lucide-vue-next';

const props = defineProps({
    auth: {
        type: Object,
        default: () => ({ user: null }),
    },
    settings: {
        type: Object,
        default: () => ({
            hero_title: 'Sistem Informasi Terpadu Seluruh Divisi',
            hero_description: 'Gerbang peluncur tunggal instrumen kerja internal PT Nanggala Kencana Rekatama Indonesia. Akses terproteksi dan terenkripsi khusus karyawan terdaftar.',
            footer_text: 'PT Nanggala Kencana Rekatama Indonesia. Sistem Internal — Klasifikasi: Terbatas & Rahasia.',
        }),
    },
    announcements: {
        type: Array,
        default: () => [],
    },
    categories: {
        type: Array,
        default: () => [],
    },
    systems: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({
            accessible_count: 0,
            total_count: 0,
        }),
    },
});

// Mapping Icon Lucide Dinamis
const iconComponents = {
    Users,
    Package,
    Receipt,
    Briefcase,
    FileText,
    BarChart3,
    Truck,
    Cpu,
    Boxes,
    FolderKanban,
    Building,
    Layers,
};

const getSystemIcon = (iconName) => {
    if (iconName && iconComponents[iconName]) {
        return iconComponents[iconName];
    }
    return Layers;
};

// State Filter & Navigasi
const searchQuery = ref('');
const selectedCategory = ref('all');
const showOnlyFavorites = ref(false);
const activeSection = ref('hero');
const isAccountDropdownOpen = ref(false);

// Format Tanggal Bahasa Indonesia
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    }).format(date);
};

// Filter Sistem Realtime
const filteredSystems = computed(() => {
    let result = [...props.systems];

    // Filter Favorit
    if (showOnlyFavorites.value) {
        result = result.filter(item => item.is_favorite);
    }

    // Filter Kategori
    if (selectedCategory.value !== 'all') {
        result = result.filter(item => item.category?.slug === selectedCategory.value);
    }

    // Filter Pencarian Teks
    if (searchQuery.value.trim() !== '') {
        const query = searchQuery.value.toLowerCase().trim();
        result = result.filter(item => 
            item.name.toLowerCase().includes(query) ||
            (item.description && item.description.toLowerCase().includes(query)) ||
            (item.category && item.category.name.toLowerCase().includes(query)) ||
            (item.login_hint && item.login_hint.toLowerCase().includes(query))
        );
    }

    return result;
});

// Scroll Halus dengan Offset Header Sticky
const scrollToSection = (id) => {
    const element = document.getElementById(id);
    if (!element) return;

    const headerOffset = 76;
    const elementPosition = element.getBoundingClientRect().top;
    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

    window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
    });
};

// Scroll Spy untuk Menyorot Link Menu Aktif Sesuai Posisi Scroll
const handleScroll = () => {
    const sections = ['hero'];
    if (props.announcements && props.announcements.length > 0) {
        sections.push('announcements');
    }
    sections.push('systems');

    const scrollY = window.scrollY;
    const triggerOffset = 140;

    for (const id of sections) {
        const el = document.getElementById(id);
        if (el) {
            const top = el.offsetTop - triggerOffset;
            const bottom = top + el.offsetHeight;
            if (scrollY >= top && scrollY < bottom) {
                activeSection.value = id;
            }
        }
    }
};

// Toggle Bintang Favorit (Optimistic UI Update)
const toggleFavorite = async (system) => {
    if (!props.auth.user) {
        window.location.href = '/login';
        return;
    }

    const previousState = system.is_favorite;
    system.is_favorite = !previousState;

    try {
        await axios.post(`/apps/${system.id}/favorite`);
    } catch (error) {
        system.is_favorite = previousState;
        console.error('Gagal memperbarui status favorit:', error);
    }
};

// Logout Handler
const handleLogout = () => {
    router.post('/logout');
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <Head title="Portal NKRI - Gerbang Sistem Digital PT Nanggala Kencana Rekatama Indonesia" />

    <div class="min-h-screen bg-[#FBFBFB] text-content font-sans antialiased flex flex-col selection:bg-primary-soft selection:text-primary">
        
        <!-- =================================================================================== -->
        <!-- HEADER STICKY (Logo, Anchor Menu, Akun / Login, Offset Halus)                       -->
        <!-- =================================================================================== -->
        <header class="sticky top-0 z-50 w-full bg-white/95 backdrop-blur border-b border-border shadow-xs transition-all duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
                
                <!-- Logo & Brand di Sisi Kiri -->
                <button 
                    @click="scrollToSection('hero')" 
                    type="button" 
                    class="flex items-center space-x-3 text-left group focus:outline-none focus:ring-2 focus:ring-primary-ring rounded-xl p-1"
                >
                    <img 
                        src="/images/logo-emblem.png" 
                        alt="Logo NKRI" 
                        class="w-10 h-10 object-contain group-hover:scale-105 transition-transform" 
                    />
                    <div class="hidden sm:block">
                        <span class="block text-base font-extrabold tracking-tight text-content leading-tight group-hover:text-primary transition-colors">
                            PORTAL NKRI
                        </span>
                        <span class="block text-[10.5px] font-semibold text-content-subtle tracking-wider uppercase">
                            PT Nanggala Kencana Rekatama Indonesia
                        </span>
                    </div>
                </button>

                <!-- Menu Anchor Navigasi di Tengah -->
                <nav class="flex items-center space-x-1 sm:space-x-2">
                    <button
                        type="button"
                        @click="scrollToSection('hero')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all duration-150"
                        :class="activeSection === 'hero' 
                            ? 'bg-primary-soft text-primary font-bold shadow-xs' 
                            : 'text-content-muted hover:text-content hover:bg-gray-100'"
                    >
                        Beranda
                    </button>

                    <!-- Menu Pengumuman: Otomatis Disembunyikan Jika Kosong -->
                    <button
                        v-if="announcements && announcements.length > 0"
                        type="button"
                        @click="scrollToSection('announcements')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all duration-150 flex items-center gap-1.5"
                        :class="activeSection === 'announcements' 
                            ? 'bg-primary-soft text-primary font-bold shadow-xs' 
                            : 'text-content-muted hover:text-content hover:bg-gray-100'"
                    >
                        <span>Pengumuman</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                    </button>

                    <button
                        type="button"
                        @click="scrollToSection('systems')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all duration-150"
                        :class="activeSection === 'systems' 
                            ? 'bg-primary-soft text-primary font-bold shadow-xs' 
                            : 'text-content-muted hover:text-content hover:bg-gray-100'"
                    >
                        Sistem
                    </button>
                </nav>

                <!-- Menu Akun / Tombol Masuk di Kanan -->
                <div class="relative flex items-center">
                    <!-- Jika Pengguna Sudah Login -->
                    <div v-if="auth.user" class="relative">
                        <button
                            type="button"
                            @click="isAccountDropdownOpen = !isAccountDropdownOpen"
                            class="flex items-center gap-2.5 px-3 py-1.5 rounded-full border border-border bg-surface hover:bg-gray-100 hover:border-primary-ring transition-all text-left focus:outline-none focus:ring-2 focus:ring-primary-ring"
                        >
                            <div class="w-7 h-7 rounded-full bg-primary-soft text-primary flex items-center justify-center font-bold text-xs">
                                {{ auth.user.name.charAt(0) }}
                            </div>
                            <div class="hidden md:block max-w-[140px] truncate">
                                <span class="block text-xs font-bold text-content leading-tight truncate">
                                    {{ auth.user.name }}
                                </span>
                                <span class="block text-[10px] text-content-subtle truncate">
                                    {{ auth.user.department || 'PT NKRI' }}
                                </span>
                            </div>
                        </button>

                        <!-- Dropdown Menu Akun -->
                        <div
                            v-if="isAccountDropdownOpen"
                            @click.outside="isAccountDropdownOpen = false"
                            class="absolute right-0 mt-2 w-56 rounded-2xl bg-white border border-border shadow-lg py-2 z-50 animate-fade-in"
                        >
                            <div class="px-4 py-2 border-b border-border/70">
                                <p class="text-xs font-bold text-content truncate">{{ auth.user.name }}</p>
                                <p class="text-[11px] text-content-subtle truncate">{{ auth.user.email }}</p>
                                <div class="mt-1.5 flex flex-wrap gap-1">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-primary-soft text-primary">
                                        {{ auth.user.department }}
                                    </span>
                                </div>
                            </div>

                            <!-- Link ke Filament Admin jika memiliki izin -->
                            <a
                                v-if="auth.user.can_access_admin"
                                href="/admin"
                                class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-content hover:bg-primary-soft hover:text-primary transition-colors"
                            >
                                <ShieldCheck class="w-4 h-4 text-primary" />
                                <span>Panel Admin CMS</span>
                            </a>

                            <!-- Tombol Keluar (Logout) -->
                            <button
                                type="button"
                                @click="handleLogout"
                                class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 transition-colors text-left"
                            >
                                <LogOut class="w-4 h-4 text-red-500" />
                                <span>Keluar dari Portal</span>
                            </button>
                        </div>
                    </div>

                    <!-- Jika Belum Login (Guest) -->
                    <Link
                        v-else
                        href="/login"
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-full text-xs font-bold text-white bg-primary hover:bg-primary-hover shadow-xs hover:shadow-md transition-all focus:outline-none focus:ring-2 focus:ring-primary-ring"
                    >
                        <LogIn class="w-3.5 h-3.5" />
                        <span>Masuk Portal</span>
                    </Link>
                </div>

            </div>
        </header>

        <!-- =================================================================================== -->
        <!-- 1. HERO SECTION (Tinggi 60-70% Layar, Judul, Deskripsi, Tombol Scroll, Info Nyata)  -->
        <!-- =================================================================================== -->
        <section 
            id="hero" 
            class="relative w-full min-h-[58vh] max-h-[70vh] flex items-center justify-center py-14 sm:py-18 px-4 sm:px-6 lg:px-8 border-b border-border bg-gradient-to-b from-white via-surface to-white overflow-hidden"
        >
            <!-- Ornamen Geometris Merah Halus di Latar Belakang -->
            <div class="pointer-events-none absolute -top-24 -left-24 w-96 h-96 rounded-full bg-primary/[0.03] blur-3xl" />
            <div class="pointer-events-none absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-primary/[0.04] blur-3xl" />

            <div class="relative max-w-4xl mx-auto text-center space-y-6">
                
                <!-- Badge Info Nyata Sistem (Real Data Counter) -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary-soft border border-primary-ring/60 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    <span v-if="auth.user" class="text-xs font-bold text-primary tracking-wide">
                        {{ stats.accessible_count }} dari {{ stats.total_count }} sistem digital dapat Anda akses
                    </span>
                    <span v-else class="text-xs font-bold text-primary tracking-wide">
                        {{ stats.total_count }} sistem digital operasional aktif terpusat
                    </span>
                </div>

                <!-- Judul Utama Hero dari Pengaturan Portal -->
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-content tracking-tight leading-tight">
                    {{ settings.hero_title || 'Selamat datang di Portal NKRI' }}
                </h1>

                <!-- Deskripsi Hero dari Pengaturan Portal -->
                <p class="max-w-2xl mx-auto text-sm sm:text-base text-content-muted leading-relaxed">
                    {{ settings.hero_description || 'Penyimpan seluruh sistem yang dibuat untuk PT NKRI' }}
                </p>

                <!-- Tombol Aksi "Lihat Sistem" (Scroll Halus ke Section Sistem) -->
                <div class="pt-2 flex items-center justify-center gap-4">
                    <button
                        type="button"
                        @click="scrollToSection('systems')"
                        class="inline-flex items-center gap-2.5 px-7 py-3 rounded-full text-sm font-bold text-white bg-primary hover:bg-primary-hover shadow-md hover:shadow-lg transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary-ring"
                    >
                        <span>Lihat Sistem</span>
                        <ArrowDown class="w-4 h-4 animate-bounce" />
                    </button>
                </div>

            </div>
        </section>

        <!-- =================================================================================== -->
        <!-- 2. PENGUMUMAN SECTION (Daftar Aktif. JIKA KOSONG: TIDAK DIRENDER)                   -->
        <!-- =================================================================================== -->
        <section 
            v-if="announcements && announcements.length > 0" 
            id="announcements" 
            class="w-full py-12 px-4 sm:px-6 lg:px-8 border-b border-border bg-[#FFFDFD]"
        >
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center gap-2.5 mb-6">
                    <div class="w-8 h-8 rounded-xl bg-primary-soft text-primary flex items-center justify-center">
                        <Bell class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-content tracking-tight">
                            Pengumuman Perusahaan
                        </h2>
                        <p class="text-xs text-content-subtle">
                            Informasi dan pemberitahuan resmi terkini dari manajemen
                        </p>
                    </div>
                </div>

                <!-- Grid Pengumuman -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div
                        v-for="item in announcements"
                        :key="item.id"
                        class="p-5 rounded-2xl bg-white border border-border hover:border-primary-ring shadow-xs hover:shadow-sm transition-all"
                    >
                        <div class="flex items-center gap-2 text-[11px] font-semibold text-content-subtle mb-2">
                            <Calendar class="w-3.5 h-3.5 text-primary" />
                            <span>{{ formatDate(item.created_at) }}</span>
                        </div>
                        <h3 class="text-sm font-bold text-content mb-1.5 leading-snug">
                            {{ item.title }}
                        </h3>
                        <p class="text-xs text-content-muted leading-relaxed whitespace-pre-line">
                            {{ item.body }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- =================================================================================== -->
        <!-- 3. DAFTAR SISTEM (Pencarian, Filter Kategori, Tombol Bintang Favorit, Kartu Sistem)  -->
        <!-- =================================================================================== -->
        <section id="systems" class="w-full py-16 px-4 sm:px-6 lg:px-8 flex-1 bg-surface">
            <div class="max-w-7xl mx-auto space-y-8">
                
                <!-- Header Section Sistem -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold text-primary uppercase tracking-widest bg-primary-soft px-3 py-1 rounded-full">
                            Katalog Terintegrasi
                        </span>
                        <h2 class="mt-2.5 text-2xl sm:text-3xl font-extrabold text-content tracking-tight">
                            Daftar Seluruh Sistem Digital
                        </h2>
                        <p class="text-xs sm:text-sm text-content-muted mt-1">
                            Pilih sistem yang ingin Anda luncurkan. Sistem yang belum berizin berstatus terkunci.
                        </p>
                    </div>

                    <!-- Search Input Realtime -->
                    <div class="w-full md:w-80 relative">
                        <Search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-content-subtle pointer-events-none" />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Cari nama atau fungsi sistem..."
                            class="w-full pl-10 pr-9 py-2.5 rounded-2xl border border-border bg-white text-xs text-content placeholder:text-content-subtle focus:border-primary focus:ring-2 focus:ring-primary-ring shadow-xs outline-none transition-all"
                        />
                        <button
                            v-if="searchQuery"
                            @click="searchQuery = ''"
                            type="button"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-content-subtle hover:text-content"
                        >
                            <X class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <!-- Kategori & Tab Filter Favorit -->
                <div class="flex flex-wrap items-center gap-2 pb-2 border-b border-border/80">
                    <button
                        type="button"
                        @click="selectedCategory = 'all'; showOnlyFavorites = false"
                        class="px-4 py-2 rounded-full text-xs font-semibold transition-all duration-150"
                        :class="selectedCategory === 'all' && !showOnlyFavorites
                            ? 'bg-primary text-white shadow-xs font-bold'
                            : 'bg-white border border-border text-content-muted hover:text-content hover:bg-gray-50'"
                    >
                        Semua Sistem ({{ systems.length }})
                    </button>

                    <!-- Filter Khusus Favorit (Jika Login) -->
                    <button
                        type="button"
                        @click="showOnlyFavorites = !showOnlyFavorites"
                        class="px-4 py-2 rounded-full text-xs font-semibold transition-all duration-150 inline-flex items-center gap-1.5"
                        :class="showOnlyFavorites
                            ? 'bg-amber-500 text-white shadow-xs font-bold'
                            : 'bg-white border border-border text-content-muted hover:text-amber-600 hover:bg-gray-50'"
                    >
                        <Star class="w-3.5 h-3.5 fill-current" />
                        <span>Favorit Saya</span>
                    </button>

                    <!-- Pills Kategori Dinamis -->
                    <button
                        v-for="cat in categories"
                        :key="cat.id"
                        type="button"
                        @click="selectedCategory = cat.slug; showOnlyFavorites = false"
                        class="px-4 py-2 rounded-full text-xs font-semibold transition-all duration-150"
                        :class="selectedCategory === cat.slug && !showOnlyFavorites
                            ? 'bg-primary text-white shadow-xs font-bold'
                            : 'bg-white border border-border text-content-muted hover:text-content hover:bg-gray-50'"
                    >
                        {{ cat.name }}
                    </button>
                </div>

                <!-- Grid Kartu Sistem (3 Kolom di Layar Lebar) -->
                <div v-if="filteredSystems.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="system in filteredSystems"
                        :key="system.id"
                        class="relative rounded-2xl p-6 flex flex-col justify-between transition-all duration-200 border"
                        :class="system.is_locked 
                            ? 'bg-[#F9FAFB]/90 border-dashed border-gray-300 opacity-70' 
                            : 'bg-white border-border shadow-xs hover:shadow-md hover:border-primary-ring'"
                    >
                        <!-- Bagian Atas Kartu: Ikon & Tombol Bintang Favorit -->
                        <div>
                            <div class="flex items-start justify-between gap-3 mb-4">
                                <div class="flex items-center gap-3">
                                    <div 
                                        class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 border"
                                        :class="system.is_locked
                                            ? 'bg-gray-100 border-gray-200 text-gray-400'
                                            : 'bg-primary-soft border-primary-ring/40 text-primary'"
                                    >
                                        <component :is="getSystemIcon(system.icon)" class="w-6 h-6" />
                                    </div>
                                    <div>
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10.5px] font-semibold bg-gray-100 text-content-muted">
                                            {{ system.category?.name || 'Sistem' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Tombol Favorit (Bintang) -->
                                <button
                                    type="button"
                                    @click="toggleFavorite(system)"
                                    :title="system.is_favorite ? 'Hapus dari favorit' : 'Tambah ke favorit'"
                                    class="p-2 rounded-full hover:bg-gray-100 transition-colors focus:outline-none"
                                >
                                    <Star 
                                        class="w-5 h-5 transition-transform active:scale-125"
                                        :class="system.is_favorite 
                                            ? 'text-amber-500 fill-amber-500' 
                                            : 'text-gray-300 hover:text-amber-400'"
                                    />
                                </button>
                            </div>

                            <!-- Judul & Deskripsi Sistem -->
                            <h3 
                                class="text-base font-bold text-content leading-snug mb-1.5"
                                :class="{ 'text-gray-500': system.is_locked }"
                            >
                                {{ system.name }}
                            </h3>
                            <p class="text-xs text-content-muted leading-relaxed line-clamp-2 mb-4">
                                {{ system.description || 'Sistem operasional digital PT Nanggala Kencana Rekatama Indonesia.' }}
                            </p>

                            <!-- login_hint bila ada -->
                            <div 
                                v-if="system.login_hint" 
                                class="mb-4 px-3 py-2 rounded-xl bg-amber-50/70 border border-amber-200/60 flex items-start gap-2 text-[11px] text-amber-900"
                            >
                                <KeyRound class="w-3.5 h-3.5 shrink-0 mt-0.5 text-amber-600" />
                                <span class="leading-tight">{{ system.login_hint }}</span>
                            </div>
                        </div>

                        <!-- Bagian Bawah Kartu: Status Hak Akses & Tombol Aksi -->
                        <div class="pt-4 border-t border-border/70 flex items-center justify-between">
                            
                            <!-- JIKA SISTEM TERKUNCI (BELUM ADA AKSES) -->
                            <template v-if="system.is_locked">
                                <div class="flex items-center gap-1.5 text-[11.5px] font-semibold text-gray-500">
                                    <Lock class="w-3.5 h-3.5 text-gray-400 shrink-0" />
                                    <span>Belum ada akses, hubungi admin</span>
                                </div>

                                <button
                                    type="button"
                                    disabled
                                    class="px-4 py-2 rounded-full text-xs font-semibold bg-gray-200 text-gray-400 cursor-not-allowed inline-flex items-center gap-1"
                                >
                                    <span>Terkunci</span>
                                </button>
                            </template>

                            <!-- JIKA SEDANG MAINTENANCE -->
                            <template v-else-if="system.status === 'maintenance'">
                                <div class="flex items-center gap-1.5 text-[11.5px] font-semibold text-amber-700">
                                    <AlertTriangle class="w-3.5 h-3.5 text-amber-500 shrink-0" />
                                    <span>Sedang Maintenance</span>
                                </div>

                                <button
                                    type="button"
                                    disabled
                                    class="px-4 py-2 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 cursor-not-allowed"
                                >
                                    <span>Pemeliharaan</span>
                                </button>
                            </template>

                            <!-- JIKA AKTIF DAN BERIZIN -->
                            <template v-else>
                                <span class="text-[11.5px] font-semibold text-emerald-600 flex items-center gap-1">
                                    <CheckCircle2 class="w-3.5 h-3.5" />
                                    <span>Tersedia</span>
                                </span>

                                <a
                                    :href="'/apps/' + system.slug + '/open'"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="px-5 py-2.5 rounded-full text-xs font-bold text-white bg-primary hover:bg-primary-hover shadow-xs hover:shadow-md transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary-ring inline-flex items-center gap-1.5"
                                >
                                    <span>Buka Sistem</span>
                                    <ExternalLink class="w-3.5 h-3.5" />
                                </a>
                            </template>

                        </div>
                    </div>
                </div>

                <!-- Empty State Jika Filter Pencarian Tidak Ditemukan -->
                <div v-else class="text-center py-16 bg-white rounded-3xl border border-dashed border-border p-8">
                    <div class="w-12 h-12 rounded-2xl bg-gray-100 text-content-subtle flex items-center justify-center mx-auto mb-3">
                        <Search class="w-6 h-6" />
                    </div>
                    <h3 class="text-base font-bold text-content">Tidak ada sistem yang cocok</h3>
                    <p class="text-xs text-content-subtle mt-1 max-w-sm mx-auto">
                        Coba periksa kata kunci pencarian Anda atau kembalikan filter ke kategori "Semua Sistem".
                    </p>
                    <button
                        type="button"
                        @click="searchQuery = ''; selectedCategory = 'all'; showOnlyFavorites = false"
                        class="mt-4 px-4 py-2 rounded-full text-xs font-semibold text-primary bg-primary-soft hover:bg-primary hover:text-white transition-colors"
                    >
                        Reset Filter
                    </button>
                </div>

            </div>
        </section>

        <!-- =================================================================================== -->
        <!-- 4. FOOTER (Nama PT NKRI & Hak Cipta dari Pengaturan Portal)                         -->
        <!-- =================================================================================== -->
        <footer class="w-full bg-white border-t border-border py-8 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-content-subtle">
                <div class="flex items-center space-x-2">
                    <img src="/images/logo-emblem.png" alt="Logo NKRI" class="w-4 h-4 object-contain" />
                    <span class="font-bold text-content">PT Nanggala Kencana Rekatama Indonesia</span>
                    <span>&bull;</span>
                    <span>Portal Peluncur Sistem Digital</span>
                </div>
                <div>
                    {{ settings.footer_text || 'PT Nanggala Kencana Rekatama Indonesia. Seluruh hak cipta dilindungi.' }}
                </div>
            </div>
        </footer>

    </div>
</template>
