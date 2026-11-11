<script setup>
import { ref, computed, onMounted, onUnmounted, watch, nextTick } from 'vue';
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

// State Modal Detail Pengumuman
const selectedAnnouncement = ref(null);
const isAnnouncementModalOpen = ref(false);

const openAnnouncementModal = (item) => {
    selectedAnnouncement.value = item;
    isAnnouncementModalOpen.value = true;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = 'hidden';
    }
};

const closeAnnouncementModal = () => {
    isAnnouncementModalOpen.value = false;
    selectedAnnouncement.value = null;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = '';
    }
};

const handleKeyDown = (e) => {
    if (e.key === 'Escape' && isAnnouncementModalOpen.value) {
        closeAnnouncementModal();
    }
};

// Sliding Indicator State untuk Animasi Geser Halus (Sliding Pill)
const navRef = ref(null);
const heroTabRef = ref(null);
const announcementsTabRef = ref(null);
const systemsTabRef = ref(null);
const isIndicatorInitialized = ref(false);

const indicatorStyle = ref({
    transform: 'translate3d(0, 0, 0)',
    width: '0px',
    height: '0px',
    top: '0px',
    opacity: 0,
});

const getActiveTabElement = () => {
    if (activeSection.value === 'hero') return heroTabRef.value;
    if (activeSection.value === 'announcements') return announcementsTabRef.value;
    if (activeSection.value === 'systems') return systemsTabRef.value;
    return null;
};

const updateIndicator = () => {
    const activeEl = getActiveTabElement();
    if (activeEl) {
        indicatorStyle.value = {
            transform: `translate3d(${activeEl.offsetLeft}px, 0, 0)`,
            width: `${activeEl.offsetWidth}px`,
            height: `${activeEl.offsetHeight}px`,
            top: `${activeEl.offsetTop}px`,
            opacity: 1,
        };
    }
};

let isProgrammaticScroll = false;
let scrollEndTimer = null;

const handleNavClick = (id) => {
    activeSection.value = id;
    isProgrammaticScroll = true;
    updateIndicator();
    scrollToSection(id);

    if (scrollEndTimer) clearTimeout(scrollEndTimer);
    scrollEndTimer = setTimeout(() => {
        isProgrammaticScroll = false;
    }, 850);
};

watch(activeSection, () => {
    nextTick(() => {
        updateIndicator();
    });
});

// Directive Scroll-Reveal: Memicu animasi meluncur saat elemen masuk ke viewport saat digulir
const vReveal = {
    mounted(el, binding) {
        if (typeof window === 'undefined') return;

        // Jika preferensi OS meminta minim gerak, langsung tampilkan
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            el.classList.add('is-revealed');
            return;
        }

        // Fallback untuk browser tanpa IntersectionObserver
        if (typeof IntersectionObserver === 'undefined') {
            el.classList.add('is-revealed');
            return;
        }

        const delay = typeof binding.value === 'number' ? binding.value : 0;
        el.style.transitionDelay = `${delay}ms`;

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        el.classList.add('is-revealed');
                        observer.unobserve(el);
                    }
                });
            },
            {
                threshold: 0.08,
                rootMargin: '0px 0px -20px 0px',
            }
        );

        observer.observe(el);
        el._revealObserver = observer;
    },
    unmounted(el) {
        if (el._revealObserver) {
            el._revealObserver.disconnect();
            delete el._revealObserver;
        }
    }
};

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
    // Jangan ubah activeSection saat user sedang menjalankan scroll hasil klik
    if (isProgrammaticScroll) return;

    const scrollY = window.scrollY;

    // Jika scroll sudah di dekat ujung bawah halaman, pasti aktifkan 'systems'
    if (window.innerHeight + scrollY >= document.documentElement.scrollHeight - 60) {
        if (activeSection.value !== 'systems') {
            activeSection.value = 'systems';
        }
        return;
    }

    const sections = ['hero'];
    if (props.announcements && props.announcements.length > 0) {
        sections.push('announcements');
    }
    sections.push('systems');

    const triggerOffset = 150;

    for (const id of sections) {
        const el = document.getElementById(id);
        if (el) {
            const top = el.offsetTop - triggerOffset;
            const bottom = top + el.offsetHeight;
            if (scrollY >= top && scrollY < bottom) {
                if (activeSection.value !== id) {
                    activeSection.value = id;
                }
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
    window.addEventListener('resize', updateIndicator, { passive: true });
    window.addEventListener('keydown', handleKeyDown);
    handleScroll();
    nextTick(() => {
        updateIndicator();
        setTimeout(() => {
            isIndicatorInitialized.value = true;
        }, 60);
    });
    if (document.fonts) {
        document.fonts.ready.then(() => {
            updateIndicator();
        });
    }
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('resize', updateIndicator);
    window.removeEventListener('keydown', handleKeyDown);
    if (typeof document !== 'undefined') {
        document.body.style.overflow = '';
    }
});
</script>

<template>
    <Head title="Portal NKRI - Gerbang Sistem Digital PT Nanggala Kencana Rekatama Indonesia" />

    <div class="min-h-screen bg-[#FBFBFB] text-content font-sans antialiased flex flex-col selection:bg-primary-soft selection:text-primary">
        
        <!-- =================================================================================== -->
        <!-- HEADER STICKY (Logo, Anchor Menu, Akun / Login, Padding Lega & Elegan)              -->
        <!-- =================================================================================== -->
        <header class="sticky top-0 z-50 w-full bg-white/95 backdrop-blur border-b border-border shadow-xs transition-all duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-3 sm:py-4 md:py-5 flex items-center justify-between gap-2">
                
                <!-- Logo & Brand di Sisi Kiri -->
                <button 
                    @click="handleNavClick('hero')" 
                    type="button" 
                    class="flex items-center space-x-2 sm:space-x-3.5 text-left group focus:outline-none focus:ring-2 focus:ring-primary-ring rounded-2xl p-1 sm:p-1.5 transition-all shrink-0"
                >
                    <img 
                        src="/images/logo-emblem.png" 
                        alt="Logo NKRI" 
                        class="w-9 h-9 sm:w-11 sm:h-11 object-contain group-hover:scale-105 transition-transform duration-200" 
                    />
                    <div class="hidden sm:block">
                        <span class="block text-base sm:text-lg font-black tracking-tight text-content leading-tight group-hover:text-primary transition-colors">
                            PORTAL NKRI
                        </span>
                        <span class="block text-[10px] sm:text-[11px] font-semibold text-content-subtle tracking-wider uppercase mt-0.5">
                            PT Nanggala Kencana Rekatama Indonesia
                        </span>
                    </div>
                </button>

                <!-- Menu Anchor Navigasi di Tengah (Animasi Geser Halus Bebas Glitch) -->
                <nav ref="navRef" class="relative flex items-center gap-1 sm:gap-2 md:gap-3">
                    <!-- Kapsul Latar Belakang yang Meluncur Mulus (Sliding Pill Hardware Accelerated) -->
                    <div
                        class="absolute left-0 rounded-full bg-primary-soft border border-primary-ring/40 shadow-xs pointer-events-none will-change-transform"
                        :class="isIndicatorInitialized ? 'transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]' : 'transition-none'"
                        :style="indicatorStyle"
                    ></div>

                    <!-- Tombol Beranda -->
                    <button
                        ref="heroTabRef"
                        type="button"
                        @click="handleNavClick('hero')"
                        class="relative z-10 px-3 sm:px-5 md:px-6 py-1.5 sm:py-2 md:py-2.5 rounded-full text-xs sm:text-sm font-semibold transition-colors duration-150 select-none"
                        :class="activeSection === 'hero' 
                            ? 'text-primary' 
                            : 'text-content-muted hover:text-content'"
                    >
                        Beranda
                    </button>

                    <!-- Menu Pengumuman: Otomatis Disembunyikan Jika Kosong -->
                    <button
                        v-if="announcements && announcements.length > 0"
                        ref="announcementsTabRef"
                        type="button"
                        @click="handleNavClick('announcements')"
                        class="relative z-10 px-3 sm:px-5 md:px-6 py-1.5 sm:py-2 md:py-2.5 rounded-full text-xs sm:text-sm font-semibold transition-colors duration-150 select-none flex items-center gap-1 sm:gap-2"
                        :class="activeSection === 'announcements' 
                            ? 'text-primary' 
                            : 'text-content-muted hover:text-content'"
                    >
                        <span>Pengumuman</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                    </button>

                    <!-- Tombol Sistem -->
                    <button
                        ref="systemsTabRef"
                        type="button"
                        @click="handleNavClick('systems')"
                        class="relative z-10 px-3 sm:px-5 md:px-6 py-1.5 sm:py-2 md:py-2.5 rounded-full text-xs sm:text-sm font-semibold transition-colors duration-150 select-none"
                        :class="activeSection === 'systems' 
                            ? 'text-primary' 
                            : 'text-content-muted hover:text-content'"
                    >
                        Sistem
                    </button>
                </nav>

                <!-- Menu Akun / Tombol Masuk di Kanan -->
                <div class="relative flex items-center shrink-0">
                    <!-- Jika Pengguna Sudah Login -->
                    <div v-if="auth.user" class="relative">
                        <button
                            type="button"
                            @click="isAccountDropdownOpen = !isAccountDropdownOpen"
                            class="flex items-center gap-2 sm:gap-3 px-2.5 sm:px-4 py-1.5 sm:py-2 rounded-full border border-border bg-surface hover:bg-gray-100 hover:border-primary-ring transition-all text-left focus:outline-none focus:ring-2 focus:ring-primary-ring shadow-xs"
                        >
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-primary-soft text-primary flex items-center justify-center font-bold text-xs sm:text-sm shrink-0">
                                {{ auth.user.name.charAt(0) }}
                            </div>
                            <div class="hidden md:block max-w-[160px] truncate">
                                <span class="block text-xs sm:text-sm font-bold text-content leading-tight truncate">
                                    {{ auth.user.name }}
                                </span>
                                <span class="block text-[10.5px] text-content-subtle truncate mt-0.5">
                                    {{ auth.user.email }}
                                </span>
                            </div>
                        </button>

                        <!-- Dropdown Menu Akun -->
                        <div
                            v-if="isAccountDropdownOpen"
                            @click.outside="isAccountDropdownOpen = false"
                            class="absolute right-0 mt-2.5 w-60 rounded-2xl bg-white border border-border shadow-xl py-2 z-50 animate-fade-in"
                        >
                            <div class="px-4 py-3 border-b border-border/70">
                                <p class="text-xs sm:text-sm font-bold text-content truncate">{{ auth.user.name }}</p>
                                <p class="text-[11px] text-content-subtle truncate mt-0.5">{{ auth.user.email }}</p>
                                <div class="mt-2 flex flex-wrap gap-1">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-primary-soft text-primary border border-primary-ring/40">
                                        Karyawan Terdaftar
                                    </span>
                                </div>
                            </div>

                            <!-- Tombol Keluar (Logout) -->
                            <button
                                type="button"
                                @click="handleLogout"
                                class="w-full flex items-center gap-2.5 px-4 py-2.5 text-xs sm:text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors text-left"
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
                        class="inline-flex items-center gap-1.5 sm:gap-2 px-3.5 sm:px-6 py-1.5 sm:py-2.5 rounded-full text-xs sm:text-sm font-bold text-white bg-primary hover:bg-primary-hover shadow-sm hover:shadow-md transition-all focus:outline-none focus:ring-2 focus:ring-primary-ring"
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
            class="relative w-full min-h-[46vh] sm:min-h-[54vh] flex items-center justify-center py-10 sm:py-16 md:py-20 px-4 sm:px-6 lg:px-8 border-b border-border bg-gradient-to-b from-white via-surface to-white overflow-hidden"
        >
            <!-- Ornamen Geometris Merah Halus di Latar Belakang -->
            <div class="pointer-events-none absolute -top-24 -left-24 w-96 h-96 rounded-full bg-primary/[0.03] blur-3xl" />
            <div class="pointer-events-none absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-primary/[0.04] blur-3xl" />

            <div class="relative max-w-4xl mx-auto text-center space-y-4 sm:space-y-6">
                
                <!-- Logo Resmi NKRI di Tengah di Atas Text (Besar & Bersih Tanpa Border) -->
                <div class="flex justify-center items-center pb-1 sm:pb-2">
                    <img 
                        src="/images/logo-emblem.png" 
                        alt="Logo PT Nanggala Kencana Rekatama Indonesia" 
                        class="w-20 h-20 sm:w-28 sm:h-28 md:w-36 md:h-36 object-contain drop-shadow-sm hover:scale-105 transition-transform duration-300" 
                    />
                </div>

                <!-- Judul Utama Hero dari Pengaturan Portal -->
                <h1 class="animate-text-enter text-2xl sm:text-4xl md:text-5xl font-extrabold text-content tracking-tight leading-tight px-2">
                    {{ settings.hero_title || 'Selamat datang di Portal NKRI' }}
                </h1>

                <!-- Deskripsi Hero dari Pengaturan Portal -->
                <p class="animate-text-enter max-w-2xl mx-auto text-xs sm:text-sm md:text-base text-content-muted leading-relaxed px-2" style="animation-delay: 160ms;">
                    {{ settings.hero_description || 'Penyimpan seluruh sistem yang dibuat untuk PT NKRI' }}
                </p>

                <!-- Tombol Aksi "Lihat Sistem" (Scroll Halus ke Section Sistem) -->
                <div class="pt-2 flex items-center justify-center gap-4">
                    <button
                        type="button"
                        @click="handleNavClick('systems')"
                        class="inline-flex items-center gap-2 sm:gap-2.5 px-6 sm:px-7 py-2.5 sm:py-3 rounded-full text-xs sm:text-sm font-bold text-white bg-primary hover:bg-primary-hover shadow-md hover:shadow-lg transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary-ring"
                    >
                        <span>Lihat Sistem</span>
                        <ArrowDown class="w-3.5 h-3.5 sm:w-4 sm:h-4 animate-bounce" />
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
            class="w-full py-10 sm:py-14 px-4 sm:px-6 lg:px-8 border-b border-border bg-[#FFFDFD]"
        >
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center gap-2.5 mb-5 sm:mb-6">
                    <div class="w-8 h-8 rounded-xl bg-primary-soft text-primary flex items-center justify-center shrink-0">
                        <Bell class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-extrabold text-content tracking-tight">
                            Pengumuman Perusahaan
                        </h2>
                        <p class="text-xs text-content-subtle">
                            Informasi dan pemberitahuan resmi terkini dari manajemen
                        </p>
                    </div>
                </div>

                <!-- Grid Pengumuman: 1 kolom di HP, 2 kolom di Tablet & Desktop -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div
                        v-for="(item, idx) in announcements"
                        :key="item.id"
                        v-reveal="idx * 130"
                        @click="openAnnouncementModal(item)"
                        role="button"
                        tabindex="0"
                        @keydown.enter="openAnnouncementModal(item)"
                        @keydown.space.prevent="openAnnouncementModal(item)"
                        class="reveal-card group cursor-pointer p-4 sm:p-5 rounded-2xl bg-white border border-border hover:border-primary-ring hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 flex flex-col justify-between focus:outline-none focus:ring-2 focus:ring-primary-ring"
                    >
                        <div>
                            <!-- Header Card: Tanggal Penerbitan (Bersih & Rapi) -->
                            <div class="flex items-center gap-1.5 text-[11px] sm:text-xs font-semibold text-content-subtle mb-2.5">
                                <Calendar class="w-3.5 h-3.5 text-primary shrink-0" />
                                <span>{{ formatDate(item.created_at) }}</span>
                            </div>

                            <h3 class="text-sm sm:text-base font-bold text-content group-hover:text-primary transition-colors mb-2 leading-snug line-clamp-2">
                                {{ item.title }}
                            </h3>

                            <div 
                                class="text-xs text-content-muted leading-relaxed line-clamp-3 [&_p]:mb-1 [&_p:last-child]:mb-0 [&_ul]:list-disc [&_ul]:pl-4 [&_ol]:list-decimal [&_ol]:pl-4 [&_strong]:text-content [&_strong]:font-bold [&_a]:text-primary [&_a]:underline"
                                v-html="item.body"
                            ></div>
                        </div>

                        <!-- Footer Card Info: HANYA SATU AKSI (Lihat Detail), tanpa 'Buka' terpisah -->
                        <div class="mt-4 pt-3 border-t border-border/60 flex items-center justify-between text-xs font-semibold text-primary">
                            <span class="text-content-subtle text-[11px]">
                                Pengumuman Resmi
                            </span>
                            <span class="inline-flex items-center gap-1 font-bold group-hover:translate-x-1 transition-transform">
                                Lihat Detail
                                <ChevronRight class="w-3.5 h-3.5" />
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =================================================================================== -->
        <!-- 3. DAFTAR SISTEM (Pencarian, Filter Kategori, Tombol Bintang Favorit, Kartu Sistem)  -->
        <!-- =================================================================================== -->
        <section id="systems" class="w-full py-12 sm:py-16 px-4 sm:px-6 lg:px-8 flex-1 bg-surface">
            <div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">
                
                <!-- Header Section Sistem -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <span class="text-[11px] sm:text-xs font-bold text-primary uppercase tracking-widest bg-primary-soft px-3 py-1 rounded-full">
                            Katalog Terintegrasi
                        </span>
                        <h2 class="mt-2.5 text-xl sm:text-2xl md:text-3xl font-extrabold text-content tracking-tight">
                            Daftar Seluruh Sistem Digital
                        </h2>
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
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-content-subtle hover:text-content p-1"
                        >
                            <X class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <!-- Kategori & Tab Filter Favorit (Scrollable horizontal di Mobile, Wrap di Tablet/Desktop) -->
                <div class="flex items-center gap-2 pb-2 border-b border-border/80 overflow-x-auto no-scrollbar -mx-4 px-4 sm:mx-0 sm:px-0 sm:flex-wrap">
                    <button
                        type="button"
                        @click="selectedCategory = 'all'; showOnlyFavorites = false"
                        class="px-3.5 sm:px-4 py-2 rounded-full text-xs font-semibold transition-all duration-150 shrink-0"
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
                        class="px-3.5 sm:px-4 py-2 rounded-full text-xs font-semibold transition-all duration-150 inline-flex items-center gap-1.5 shrink-0"
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
                        class="px-3.5 sm:px-4 py-2 rounded-full text-xs font-semibold transition-all duration-150 shrink-0"
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
                        v-for="(system, index) in filteredSystems"
                        :key="system.id + '-' + selectedCategory"
                        v-reveal="(index % 3) * 130"
                        class="reveal-card group relative rounded-2xl p-5 flex flex-col justify-between border bg-white border-border shadow-xs hover:border-primary-ring/70 hover:shadow-md transition-all duration-200"
                    >
                        <div>
                            <!-- 1. Bagian Atas: Logo Menyatu Dengan Judul & Tombol Favorit -->
                            <div class="flex items-start justify-between gap-3 mb-3.5">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <!-- Logo Sistem (Gambar Upload atau Ikon Lucide) -->
                                    <div 
                                        class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 border transition-transform duration-200 group-hover:scale-105"
                                        :class="system.logo 
                                            ? 'bg-white border-border shadow-2xs p-1' 
                                            : 'bg-primary-soft border-primary-ring/40 text-primary'"
                                    >
                                        <img 
                                            v-if="system.logo" 
                                            :src="system.logo" 
                                            :alt="system.name" 
                                            class="w-full h-full object-contain rounded-lg" 
                                        />
                                        <component v-else :is="getSystemIcon(system.icon)" class="w-5 h-5" />
                                    </div>
                                    
                                    <!-- Judul Sistem & Kategori -->
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-sm font-bold text-content leading-snug truncate group-hover:text-primary transition-colors" :title="system.name">
                                            {{ system.name }}
                                        </h3>
                                        <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-semibold bg-gray-100 text-content-muted mt-0.5">
                                            {{ system.category?.name || 'Sistem' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Tombol Bintang Favorit -->
                                <button
                                    type="button"
                                    @click="toggleFavorite(system)"
                                    :title="system.is_favorite ? 'Hapus dari favorit' : 'Tambah ke favorit'"
                                    class="p-1.5 rounded-full hover:bg-gray-100 transition-colors focus:outline-none shrink-0"
                                >
                                    <Star 
                                        class="w-4 h-4 transition-transform active:scale-125"
                                        :class="system.is_favorite 
                                            ? 'text-amber-500 fill-amber-500' 
                                            : 'text-gray-300 hover:text-amber-400'"
                                    />
                                </button>
                            </div>

                            <!-- 2. Bagian Tengah: Gambar Pratinjau Website (Screenshot / Mockup) -->
                            <div class="mb-3.5">
                                <div class="relative w-full aspect-[16/9] rounded-xl overflow-hidden bg-slate-100 border border-slate-200/80 group-hover:border-primary-ring/60 transition-all duration-300">
                                    <img 
                                        v-if="system.image" 
                                        :src="system.image" 
                                        :alt="'Pratinjau ' + system.name" 
                                        class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-[1.03]"
                                        loading="lazy"
                                    />
                                    <!-- Fallback Mockup Browser jika belum ada gambar -->
                                    <div v-else class="w-full h-full bg-gradient-to-br from-slate-50 to-slate-100 flex flex-col justify-between p-3">
                                        <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                                            <div class="flex items-center space-x-1.5">
                                                <div class="w-2 h-2 rounded-full bg-rose-400"></div>
                                                <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                                                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                            </div>
                                            <span class="text-[9.5px] text-slate-400 font-mono truncate max-w-[150px]">
                                                {{ system.url ? system.url.replace(/^https?:\/\//, '') : 'nkri.co.id' }}
                                            </span>
                                        </div>
                                        <div class="flex-1 flex flex-col items-center justify-center text-center">
                                            <div class="w-8 h-8 rounded-lg bg-white shadow-2xs border border-slate-200/80 flex items-center justify-center text-primary mb-1">
                                                <component :is="getSystemIcon(system.icon)" class="w-4 h-4" />
                                            </div>
                                            <span class="text-[10px] font-semibold text-slate-600 truncate max-w-[180px]">{{ system.name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Deskripsi Sistem -->
                            <p class="text-xs text-content-muted leading-relaxed line-clamp-2 mb-3">
                                {{ system.description || 'Sistem operasional digital PT Nanggala Kencana Rekatama Indonesia.' }}
                            </p>

                            <!-- Login Hint bila ada -->
                            <div 
                                v-if="system.login_hint" 
                                class="mb-3.5 px-3 py-1.5 rounded-lg bg-amber-50/70 border border-amber-200/60 flex items-start gap-1.5 text-[11px] text-amber-900"
                            >
                                <KeyRound class="w-3.5 h-3.5 shrink-0 mt-0.5 text-amber-600" />
                                <span class="leading-tight">{{ system.login_hint }}</span>
                            </div>
                        </div>

                        <!-- 4. Bagian Bawah: Status & Tombol Peluncur -->
                        <div class="pt-3.5 border-t border-border/70 flex items-center justify-between">
                            <!-- JIKA SEDANG MAINTENANCE -->
                            <template v-if="system.status === 'maintenance'">
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
                                    class="px-5 py-2 rounded-full text-xs font-bold text-white bg-primary hover:bg-primary-hover shadow-xs hover:shadow-md transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary-ring inline-flex items-center gap-1.5"
                                >
                                    <span>Buka Sistem</span>
                                    <ExternalLink class="w-3.5 h-3.5" />
                                </a>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Empty State Jika Tidak Ada Sistem atau Filter Pencarian Tidak Ditemukan -->
                <div v-else class="text-center py-16 bg-white rounded-3xl border border-dashed border-border p-8">
                    <template v-if="systems.length === 0">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3">
                            <Lock class="w-6 h-6" />
                        </div>
                        <h3 class="text-base font-bold text-content">Belum Ada Sistem yang Diberikan Akses</h3>
                        <p class="text-xs text-content-subtle mt-1 max-w-sm mx-auto">
                            Akun Anda belum memiliki izin akses ke sistem digital internal. Silakan hubungi Administrator melalui CMS Admin untuk menambahkan akses sistem.
                        </p>
                    </template>
                    <template v-else>
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
                    </template>
                </div>

            </div>
        </section>

        <!-- =================================================================================== -->
        <!-- 4. FOOTER (Nama PT NKRI & Hak Cipta dari Pengaturan Portal)                         -->
        <!-- =================================================================================== -->
        <footer class="w-full bg-white border-t border-border py-6 sm:py-8 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4 text-xs text-content-subtle text-center sm:text-left">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                    <img src="/images/logo-emblem.png" alt="Logo NKRI" class="w-4 h-4 object-contain shrink-0" />
                    <span class="font-bold text-content">PT Nanggala Kencana Rekatama Indonesia</span>
                    <span class="hidden sm:inline">&bull;</span>
                    <span class="block sm:inline w-full sm:w-auto text-[11px] sm:text-xs">Portal Peluncur Sistem Digital</span>
                </div>
                <div class="text-[11px] sm:text-xs">
                    {{ settings.footer_text || 'PT Nanggala Kencana Rekatama Indonesia. Seluruh hak cipta dilindungi.' }}
                </div>
            </div>
        </footer>

        <!-- =================================================================================== -->
        <!-- MODAL DETAIL PENGUMUMAN (Pop-up Card Informasi Lengkap)                             -->
        <!-- =================================================================================== -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div 
                    v-if="isAnnouncementModalOpen && selectedAnnouncement" 
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-black/60 backdrop-blur-xs overflow-y-auto"
                    @click.self="closeAnnouncementModal"
                >
                    <div 
                        class="relative w-full max-w-2xl bg-white rounded-2xl sm:rounded-3xl border border-border shadow-2xl overflow-hidden my-auto animate-in fade-in zoom-in-95 duration-200"
                        role="dialog"
                        aria-modal="true"
                    >
                        <!-- Header Card Modal -->
                        <div class="p-5 sm:p-7 border-b border-border bg-gradient-to-b from-primary-soft/50 to-white">
                            <div class="flex items-center justify-between gap-3 mb-2.5 sm:mb-3">
                                <div class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-1 rounded-full bg-primary-soft text-primary border border-primary-ring/50 text-[11px] sm:text-xs font-bold">
                                    <Bell class="w-3.5 h-3.5" />
                                    <span>Pengumuman Perusahaan</span>
                                </div>
                                <button
                                    type="button"
                                    @click="closeAnnouncementModal"
                                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white hover:bg-neutral-100 border border-border flex items-center justify-center text-content-subtle hover:text-content transition-colors shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-ring"
                                    aria-label="Tutup modal"
                                >
                                    <X class="w-4 h-4" />
                                </button>
                            </div>

                            <h2 class="text-lg sm:text-2xl font-black text-content tracking-tight leading-snug">
                                {{ selectedAnnouncement.title }}
                            </h2>

                            <div class="flex items-center gap-1.5 text-xs font-semibold text-content-subtle mt-2 sm:mt-3">
                                <Calendar class="w-3.5 h-3.5 text-primary" />
                                <span>Diterbitkan: {{ formatDate(selectedAnnouncement.created_at) }}</span>
                            </div>
                        </div>

                        <!-- Isi Konten Pengumuman (Scrollable jika teks panjang) -->
                        <div class="p-5 sm:p-7 max-h-[65vh] sm:max-h-[60vh] overflow-y-auto">
                            <div 
                                class="text-xs sm:text-base text-content leading-relaxed space-y-3 [&_p]:mb-3 [&_p:last-child]:mb-0 [&_h1]:text-lg sm:[&_h1]:text-xl [&_h1]:font-bold [&_h2]:text-base sm:[&_h2]:text-lg [&_h2]:font-bold [&_h3]:text-sm sm:[&_h3]:text-base [&_h3]:font-bold [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:mb-3 [&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:mb-3 [&_li]:mb-1 [&_strong]:text-content [&_strong]:font-bold [&_a]:text-primary [&_a]:underline [&_blockquote]:border-l-4 [&_blockquote]:border-primary [&_blockquote]:pl-4 [&_blockquote]:italic [&_blockquote]:text-content-muted"
                                v-html="selectedAnnouncement.body"
                            ></div>
                        </div>

                        <!-- Footer Card Modal -->
                        <div class="px-5 py-3.5 sm:px-7 sm:py-4 border-t border-border bg-surface flex items-center justify-between gap-3">
                            <span class="hidden sm:inline text-xs text-content-subtle">
                                Tekan <kbd class="px-1.5 py-0.5 bg-white border border-border rounded text-[11px] font-mono shadow-2xs">Esc</kbd> atau klik di luar untuk menutup
                            </span>
                            <span class="sm:hidden text-[11px] text-content-subtle">
                                Ketuk di luar untuk menutup
                            </span>
                            <button
                                type="button"
                                @click="closeAnnouncementModal"
                                class="px-5 py-2 sm:py-2.5 rounded-xl bg-primary hover:bg-primary-hover text-white text-xs font-bold transition-colors shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-ring shrink-0"
                            >
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>

    </div>
</template>
