<?php

namespace Database\Seeders;

use App\Models\AppCategory;
use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = AppCategory::pluck('id', 'slug');
        $budiIt = User::where('email', 'budi.it@nkri.co.id')->first();

        $applications = [
            [
                'name' => 'HRIS & Portal Kepegawaian',
                'slug' => 'hris-portal-kepegawaian',
                'description' => 'Sistem informasi pengelolaan data karyawan, cuti, klaim kesehatan, dan slip gaji elektronik.',
                'icon' => 'Users',
                'image' => 'images/apps/hris.svg',
                'url' => 'https://hris.nkri.co.id',
                'category_slug' => 'sdm-kepegawaian',
                'status' => 'active',
                'sort_order' => 1,
                'open_in_new_tab' => true,
                'login_hint' => 'Gunakan NIK Karyawan & password portal HR Anda.',
            ],
            [
                'name' => 'ERP Supply Chain & Inventory',
                'slug' => 'erp-supply-chain-inventory',
                'description' => 'Platform terintegrasi pengelolaan stok barang, distribusi gudang, dan pemesanan logistik.',
                'icon' => 'Package',
                'image' => 'images/apps/erp.svg',
                'url' => 'https://erp.nkri.co.id',
                'category_slug' => 'operasional-rantai-pasok',
                'status' => 'active',
                'sort_order' => 2,
                'open_in_new_tab' => true,
                'login_hint' => 'Login dengan akun Odoo / SAP korporat.',
            ],
            [
                'name' => 'Sistem Akuntansi & Faktur',
                'slug' => 'sistem-akuntansi-faktur',
                'description' => 'Aplikasi pencatatan jurnal umum, rekonsiliasi bank, e-Faktur pajak, dan pelaporan keuangan.',
                'icon' => 'Receipt',
                'image' => 'images/apps/finance.svg',
                'url' => 'https://finance.nkri.co.id',
                'category_slug' => 'finansial-akuntansi',
                'status' => 'active',
                'sort_order' => 3,
                'open_in_new_tab' => true,
                'login_hint' => 'Autentikasi dengan ID Akuntan resmi perusahaan.',
            ],
            [
                'name' => 'CRM & Manajemen Pelanggan',
                'slug' => 'crm-manajemen-pelanggan',
                'description' => 'Pelacakan prospek bisnis, pipeline penjualan, dan histori komunikasi klien korporat.',
                'icon' => 'Briefcase',
                'image' => 'images/apps/crm.svg',
                'url' => 'https://crm.nkri.co.id',
                'category_slug' => 'penjualan-pelanggan',
                'status' => 'active',
                'sort_order' => 4,
                'open_in_new_tab' => true,
                'login_hint' => 'Gunakan akun tim Sales & Account Executive Anda.',
            ],
            [
                'name' => 'Helpdesk & IT Support Ticket',
                'slug' => 'helpdesk-it-support-ticket',
                'description' => 'Pusat pengaduan kendala perangkat keras, akses jaringan, dan permohonan lisensi software.',
                'icon' => 'LifeBuoy',
                'image' => 'images/apps/helpdesk.svg',
                'url' => 'https://helpdesk.nkri.co.id',
                'category_slug' => 'produktivitas-kolaborasi',
                'status' => 'active',
                'sort_order' => 5,
                'open_in_new_tab' => true,
                'login_hint' => 'Login dengan email internal @nkri.co.id Anda.',
            ],
            [
                'name' => 'E-Procurement Pengadaan Barang',
                'slug' => 'e-procurement-pengadaan-barang',
                'description' => 'Sistem tender elektronik, purchase request, purchase order, dan verifikasi vendor rekanan.',
                'icon' => 'ShoppingCart',
                'image' => 'images/apps/procurement.svg',
                'url' => 'https://procurement.nkri.co.id',
                'category_slug' => 'operasional-rantai-pasok',
                'status' => 'active',
                'sort_order' => 6,
                'open_in_new_tab' => true,
                'login_hint' => 'Gunakan akun divisi Pengadaan yang terdaftar.',
            ],
            [
                'name' => 'Business Intelligence & Reporting',
                'slug' => 'business-intelligence-reporting',
                'description' => 'Dashboard analitik eksekutif untuk visualisasi performa bisnis dan laporan real-time.',
                'icon' => 'BarChart3',
                'image' => 'images/apps/bi.svg',
                'url' => 'https://bi.nkri.co.id',
                'category_slug' => 'produktivitas-kolaborasi',
                'status' => 'active',
                'sort_order' => 7,
                'open_in_new_tab' => true,
                'login_hint' => 'Akses terbatas untuk pimpinan unit dan analis data.',
            ],
            [
                'name' => 'Portal Dokumen & Kontrak Legal',
                'slug' => 'portal-dokumen-kontrak-legal',
                'description' => 'Repositori terenkripsi draft perjanjian kerja sama, perizinan usaha, dan dokumen hukum perusahaan.',
                'icon' => 'FileText',
                'image' => 'images/apps/legal.svg',
                'url' => 'https://legal.nkri.co.id',
                'category_slug' => 'produktivitas-kolaborasi',
                'status' => 'active',
                'sort_order' => 8,
                'open_in_new_tab' => true,
                'login_hint' => 'Akses terenkripsi divisi Legal & Kepatuhan.',
            ],
            [
                'name' => 'Aplikasi Presensi GPS Mobile',
                'slug' => 'aplikasi-presensi-gps-mobile',
                'description' => 'Aplikasi pencatatan kehadiran berbasis radius lokasi kantor dan pengenalan wajah.',
                'icon' => 'Clock',
                'image' => 'images/apps/attendance.svg',
                'url' => 'https://presensi.nkri.co.id',
                'category_slug' => 'sdm-kepegawaian',
                'status' => 'maintenance',
                'sort_order' => 9,
                'open_in_new_tab' => true,
                'login_hint' => 'Server dalam proses peningkatan keamanan berkala.',
            ],
            [
                'name' => 'Sistem Payroll Legacy V1 (Arsip)',
                'slug' => 'sistem-payroll-legacy-v1-arsip',
                'description' => 'Arsip sistem penggajian lama sebelum migrasi ke sistem HRIS modern.',
                'icon' => 'Archive',
                'url' => 'https://legacy-payroll.nkri.co.id',
                'category_slug' => 'finansial-akuntansi',
                'status' => 'inactive',
                'sort_order' => 10,
                'open_in_new_tab' => true,
                'login_hint' => 'Sistem tidak lagi menerima input data baru.',
            ],
        ];

        foreach ($applications as $appData) {
            $catSlug = $appData['category_slug'];
            unset($appData['category_slug']);

            Application::updateOrCreate(
                ['slug' => $appData['slug']],
                array_merge($appData, [
                    'app_category_id' => $categories[$catSlug],
                    'pic_user_id' => $budiIt?->id,
                ])
            );
        }
    }
}

