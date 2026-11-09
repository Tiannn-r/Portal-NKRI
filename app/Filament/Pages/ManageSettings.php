<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan Portal';
    protected static ?string $title = 'Pengaturan Portal';
    protected static ?string $navigationGroup = 'Manajemen Konten';
    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['Super Admin', 'Admin Konten', 'Admin Sistem']) ?? false;
    }

    public function mount(): void
    {
        $this->form->fill([
            'hero_title' => Setting::get('hero_title', 'Selamat datang di Portal NKRI'),
            'hero_description' => Setting::get('hero_description', 'Penyimpan seluruh sistem yang dibuat untuk PT NKRI'),
            'footer_text' => Setting::get('footer_text', 'PT Nanggala Kencana Rekatama Indonesia. Seluruh hak cipta dilindungi.'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Konten Beranda (Hero Section)')
                    ->description('Sesuaikan teks headline dan deskripsi pengantar pada bagian atas landing page.')
                    ->schema([
                        TextInput::make('hero_title')
                            ->label('Judul Hero')
                            ->placeholder('Selamat datang di Portal NKRI')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('hero_description')
                            ->label('Deskripsi Hero')
                            ->placeholder('Penyimpan seluruh sistem yang dibuat untuk PT NKRI')
                            ->required()
                            ->rows(3)
                            ->maxLength(500),
                    ]),

                Section::make('Konten Footer')
                    ->description('Teks nama PT NKRI dan klausul hak cipta di bagian bawah seluruh halaman.')
                    ->schema([
                        TextInput::make('footer_text')
                            ->label('Teks Footer & Hak Cipta')
                            ->placeholder('PT Nanggala Kencana Rekatama Indonesia. Seluruh hak cipta dilindungi.')
                            ->required()
                            ->maxLength(255),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        Setting::set('hero_title', $state['hero_title']);
        Setting::set('hero_description', $state['hero_description']);
        Setting::set('footer_text', $state['footer_text']);

        // Catat ke Spatie Activity Log untuk kepatuhan Audit Log
        if (function_exists('activity')) {
            activity('portal_settings')
                ->causedBy(auth()->user())
                ->withProperties($state)
                ->log('Memperbarui Pengaturan Portal');
        }

        Notification::make()
            ->title('Pengaturan Portal Berhasil Disimpan')
            ->body('Teks Hero dan Footer pada Landing Page telah diperbarui secara langsung.')
            ->success()
            ->send();
    }
}
