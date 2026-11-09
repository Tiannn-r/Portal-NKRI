<?php

namespace App\Filament\Pages;

use App\Models\Application;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageUserAccess extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Atur Tampilan Sistem';
    protected static ?string $title = 'Pengaturan Penampilan Sistem Per Pengguna';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.manage-user-access';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'user_id' => null,
            'allowed_apps' => [],
            'denied_apps' => [],
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pilih Pengguna')
                    ->description('Pilih karyawan yang ingin Anda atur daftar sistem digitalnya.')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Nama Karyawan / Pengguna')
                            ->options(
                                User::with('department')
                                    ->orderBy('name')
                                    ->get()
                                    ->mapWithKeys(fn (User $user) => [
                                        $user->id => "{$user->name} ({$user->email}) - " . ($user->department?->name ?? 'Lintas Departemen'),
                                    ])
                            )
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if (!$state) {
                                    $set('allowed_apps', []);
                                    $set('denied_apps', []);
                                    return;
                                }

                                $user = User::with('applicationExceptions')->find($state);
                                if (!$user) {
                                    return;
                                }

                                $allowed = $user->applicationExceptions
                                    ->where('pivot.type', 'allow')
                                    ->pluck('id')
                                    ->map(fn ($id) => (string) $id)
                                    ->toArray();

                                $denied = $user->applicationExceptions
                                    ->where('pivot.type', 'deny')
                                    ->pluck('id')
                                    ->map(fn ($id) => (string) $id)
                                    ->toArray();

                                $set('allowed_apps', $allowed);
                                $set('denied_apps', $denied);
                            })
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Pengaturan Tampilan Sistem Digital')
                    ->description('Tentukan sistem mana saja yang ditampilkan atau disembunyikan secara khusus untuk pengguna yang dipilih.')
                    ->visible(fn (Forms\Get $get) => filled($get('user_id')))
                    ->schema([
                        Forms\Components\CheckboxList::make('allowed_apps')
                            ->label('Sistem yang DITAMPILKAN (Diberi Izin Khusus)')
                            ->options(
                                Application::with('category')
                                    ->where('status', '!=', 'inactive')
                                    ->orderBy('sort_order')
                                    ->get()
                                    ->mapWithKeys(fn (Application $app) => [
                                        $app->id => "{$app->name} [" . ($app->category?->name ?? 'Umum') . "]",
                                    ])
                            )
                            ->columns(2)
                            ->helperText('Sistem yang dicentang di sini akan SELALU muncul di portal karyawan ini.')
                            ->bulkToggleable(),

                        Forms\Components\CheckboxList::make('denied_apps')
                            ->label('Sistem yang DISEMBUNYIKAN / DIBLOKIR (Deny Wins)')
                            ->options(
                                Application::with('category')
                                    ->where('status', '!=', 'inactive')
                                    ->orderBy('sort_order')
                                    ->get()
                                    ->mapWithKeys(fn (Application $app) => [
                                        $app->id => "{$app->name} [" . ($app->category?->name ?? 'Umum') . "]",
                                    ])
                            )
                            ->columns(2)
                            ->helperText('Sistem yang dicentang di sini akan SELALU disembunyikan dari karyawan ini meskipun divisinya memiliki akses.')
                            ->bulkToggleable(),
                    ]),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $state = $this->form->getState();
        $userId = $state['user_id'] ?? null;

        if (!$userId) {
            Notification::make()
                ->title('Pilih pengguna terlebih dahulu')
                ->danger()
                ->send();
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            return;
        }

        $allowed = $state['allowed_apps'] ?? [];
        $denied = $state['denied_apps'] ?? [];

        // Hapus konfigurasi lama
        $user->applicationExceptions()->detach();

        // Simpan izin khusus (allow)
        foreach ($allowed as $appId) {
            // Jika ada di denied, prioritas deny
            if (!in_array($appId, $denied)) {
                $user->applicationExceptions()->attach($appId, [
                    'type' => 'allow',
                    'notes' => 'Diatur via menu Atur Tampilan Sistem CMS',
                ]);
            }
        }

        // Simpan blokir khusus (deny)
        foreach ($denied as $appId) {
            $user->applicationExceptions()->attach($appId, [
                'type' => 'deny',
                'notes' => 'Diblokir via menu Atur Tampilan Sistem CMS',
            ]);
        }

        Notification::make()
            ->title('Pengaturan Berhasil Disimpan')
            ->body("Daftar penampilan sistem untuk {$user->name} berhasil diperbarui.")
            ->success()
            ->send();
    }
}
