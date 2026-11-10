<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    /**
     * Teks yang muncul di menu samping (Sidebar Navigation).
     * Ubah teks ini sesuai yang Anda inginkan (misal: 'Beranda', 'Pusat Kontrol', 'Ikhtisar', dll).
     */
    protected static ?string $navigationLabel = 'Beranda Admin';

    /**
     * Judul yang muncul di bagian paling atas halaman.
     */
    protected static ?string $title = 'Beranda Admin';

    /**
     * Ikon navigasi di sidebar (opsional).
     */
    protected static ?string $navigationIcon = 'heroicon-o-home';
}
