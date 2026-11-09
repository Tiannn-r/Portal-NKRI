<script setup>
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import { ShieldCheck, Mail, Lock, Eye, EyeOff, AlertCircle, ArrowRight, UserCheck } from 'lucide-vue-next';

defineProps({
    status: {
        type: String,
        default: null,
    },
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

// Fungsi bantuan untuk pengujian cepat akun seeded
const fillDemoAccount = (email, password) => {
    form.email = email;
    form.password = password;
};
</script>

<template>
    <Head title="Masuk ke Portal NKRI" />

    <div class="min-h-screen bg-surface flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Brand & Emblem -->
            <div class="flex items-center justify-center space-x-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-primary text-white flex items-center justify-center font-bold text-lg shadow-sm">
                    <ShieldCheck class="w-6 h-6 text-white" />
                </div>
                <div class="text-left">
                    <span class="block text-xl font-bold tracking-tight text-content leading-none">PORTAL NKRI</span>
                    <span class="text-[11px] font-medium text-content-subtle tracking-wider uppercase">Sistem Digital Terpadu</span>
                </div>
            </div>

            <h2 class="mt-6 text-center text-xl font-bold text-content">
                Masuk ke Akun Anda
            </h2>
            <p class="mt-1 text-center text-sm text-content-muted">
                Gerbang peluncur resmi seluruh sistem digital internal korporat
            </p>
        </div>

        <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-6 border border-border rounded-lg shadow-card sm:px-8">
                <!-- Status Notifikasi -->
                <div v-if="status" class="mb-5 p-3 rounded-md bg-emerald-50 border border-emerald-200 text-xs text-emerald-800">
                    {{ status }}
                </div>

                <!-- Alert Error Global (jika ada error umum) -->
                <div v-if="form.errors.email && !form.email" class="mb-5 p-3 rounded-md bg-primary-soft border border-primary-ring text-xs text-primary flex items-start space-x-2">
                    <AlertCircle class="w-4 h-4 shrink-0 mt-0.5" />
                    <span>{{ form.errors.email }}</span>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-content uppercase tracking-wider mb-1.5">
                            Alamat Email Resmi
                        </label>
                        <div class="relative rounded-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-content-subtle">
                                <Mail class="h-4 w-4" />
                            </div>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                autocomplete="username"
                                required
                                placeholder="nama@nkri.co.id"
                                :class="[
                                    'block w-full pl-9 pr-3 py-2 text-sm text-content bg-white border rounded-md transition-colors placeholder:text-content-subtle/60 focus:outline-none focus:ring-2 focus:ring-primary-ring focus:border-primary',
                                    form.errors.email ? 'border-primary text-primary' : 'border-border hover:border-content-subtle'
                                ]"
                            />
                        </div>
                        <p v-if="form.errors.email" class="mt-1.5 text-xs text-primary flex items-center space-x-1">
                            <AlertCircle class="w-3.5 h-3.5 inline shrink-0" />
                            <span>{{ form.errors.email }}</span>
                        </p>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-content uppercase tracking-wider">
                                Kata Sandi
                            </label>
                            <span class="text-[11px] text-content-subtle">
                                Kebijakan Sandi Aktif
                            </span>
                        </div>
                        <div class="relative rounded-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-content-subtle">
                                <Lock class="h-4 w-4" />
                            </div>
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                autocomplete="current-password"
                                required
                                placeholder="Masukkan kata sandi Anda"
                                :class="[
                                    'block w-full pl-9 pr-10 py-2 text-sm text-content bg-white border rounded-md transition-colors placeholder:text-content-subtle/60 focus:outline-none focus:ring-2 focus:ring-primary-ring focus:border-primary',
                                    form.errors.password ? 'border-primary text-primary' : 'border-border hover:border-content-subtle'
                                ]"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-content-subtle hover:text-content focus:outline-none"
                                tabindex="-1"
                            >
                                <EyeOff v-if="showPassword" class="h-4 w-4" />
                                <Eye v-else class="h-4 w-4" />
                            </button>
                        </div>
                        <p v-if="form.errors.password" class="mt-1.5 text-xs text-primary flex items-center space-x-1">
                            <AlertCircle class="w-3.5 h-3.5 inline shrink-0" />
                            <span>{{ form.errors.password }}</span>
                        </p>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="h-4 w-4 rounded border-border text-primary focus:ring-primary-ring focus:ring-offset-0 cursor-pointer"
                            />
                            <span class="text-xs text-content-muted">Ingat sesi saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-md text-sm font-semibold text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-ring transition-colors shadow-sm disabled:opacity-60 disabled:cursor-not-allowed"
                        >
                            <span v-if="form.processing">Memverifikasi...</span>
                            <span v-else class="flex items-center space-x-2">
                                <span>Masuk ke Portal</span>
                                <ArrowRight class="w-4 h-4" />
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Informasi Dukungan & Akun Coba Cepat (Dev Helper) -->
                <div class="mt-8 pt-6 border-t border-border">
                    <p class="text-xs font-semibold text-content-subtle uppercase tracking-wider mb-2.5">
                        Pilih Kredensial Demo Cepat:
                    </p>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <button
                            type="button"
                            @click="fillDemoAccount('admin@nkri.co.id', 'PortalPassword123!')"
                            class="text-left p-2 rounded border border-border bg-surface hover:bg-primary-soft hover:border-primary-ring transition-colors"
                        >
                            <div class="font-semibold text-content flex items-center space-x-1">
                                <UserCheck class="w-3.5 h-3.5 text-primary" />
                                <span>Super Admin</span>
                            </div>
                            <div class="text-[11px] text-content-muted truncate">admin@nkri.co.id</div>
                        </button>

                        <button
                            type="button"
                            @click="fillDemoAccount('budi.it@nkri.co.id', 'UserPassword123!')"
                            class="text-left p-2 rounded border border-border bg-surface hover:bg-primary-soft hover:border-primary-ring transition-colors"
                        >
                            <div class="font-semibold text-content flex items-center space-x-1">
                                <UserCheck class="w-3.5 h-3.5 text-primary" />
                                <span>Admin Sistem</span>
                            </div>
                            <div class="text-[11px] text-content-muted truncate">budi.it@nkri.co.id</div>
                        </button>

                        <button
                            type="button"
                            @click="fillDemoAccount('siti.hr@nkri.co.id', 'UserPassword123!')"
                            class="text-left p-2 rounded border border-border bg-surface hover:bg-primary-soft hover:border-primary-ring transition-colors"
                        >
                            <div class="font-semibold text-content flex items-center space-x-1">
                                <UserCheck class="w-3.5 h-3.5 text-primary" />
                                <span>Admin Konten (HR)</span>
                            </div>
                            <div class="text-[11px] text-content-muted truncate">siti.hr@nkri.co.id</div>
                        </button>

                        <button
                            type="button"
                            @click="fillDemoAccount('andi.fin@nkri.co.id', 'UserPassword123!')"
                            class="text-left p-2 rounded border border-border bg-surface hover:bg-primary-soft hover:border-primary-ring transition-colors"
                        >
                            <div class="font-semibold text-content flex items-center space-x-1">
                                <UserCheck class="w-3.5 h-3.5 text-primary" />
                                <span>User (Keuangan)</span>
                            </div>
                            <div class="text-[11px] text-content-muted truncate">andi.fin@nkri.co.id</div>
                        </button>
                    </div>

                    <div class="mt-4 text-center text-xs text-content-subtle">
                        Kendala akses atau lupa sandi? Hubungi tim Helpdesk IT di ext. 101.
                    </div>
                </div>
            </div>

            <!-- Footer Hak Cipta -->
            <p class="mt-6 text-center text-xs text-content-subtle">
                &copy; {{ new Date().getFullYear() }} Portal Sistem Digital NKRI. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</template>
