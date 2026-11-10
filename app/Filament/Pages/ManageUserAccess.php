<?php

namespace App\Filament\Pages;

use App\Models\Application;
use App\Models\User;
use App\Services\AccessResolver;
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
    protected static ?string $navigationLabel = 'Atur Akses Pengguna';
    protected static ?string $title = 'Atur Hak Akses Pengguna';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.manage-user-access';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'user_id' => null,
            'allowed_apps' => [],
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pilih Pengguna')
                    ->description('Pilih karyawan yang ingin Anda atur izin akses sistemnya.')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Nama Karyawan / Pengguna')
                            ->options(
                                User::orderBy('name')
                                    ->get()
                                    ->mapWithKeys(fn (User $user) => [
                                        $user->id => "{$user->name} ({$user->email})",
                                    ])
                            )
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if (!$state) {
                                    $set('allowed_apps', []);
                                    return;
                                }

                                $user = User::with('applications')->find($state);
                                if (!$user) {
                                    return;
                                }

                                $allowed = $user->applications
                                    ->pluck('id')
                                    ->map(fn ($id) => (string) $id)
                                    ->toArray();

                                $set('allowed_apps', $allowed);
                            })
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Hak Akses Sistem Digital')
                    ->description('Centang sistem-sistem apa saja yang diizinkan untuk dibuka oleh pengguna yang dipilih.')
                    ->visible(fn (Forms\Get $get) => filled($get('user_id')))
                    ->schema([
                        Forms\Components\Placeholder::make('super_admin_notice')
                            ->label('Status Akses Khusus')
                            ->content('Pengguna ini adalah Super Administrator. Super Admin secara otomatis memiliki izin akses penuh ke seluruh sistem digital internal.')
                            ->visible(function (Forms\Get $get) {
                                $userId = $get('user_id');
                                if (!$userId) {
                                    return false;
                                }
                                $user = User::find($userId);
                                return $user && $user->hasRole('Super Admin');
                            }),

                        Forms\Components\CheckboxList::make('allowed_apps')
                            ->label('Daftar Sistem yang Diberi Akses')
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
                            ->helperText('Sistem yang dicentang akan dapat dilihat dan diluncurkan oleh karyawan ini di portal.')
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

        // Simpan sistem yang diizinkan
        $user->applications()->sync($allowed);

        // Invalidate cache
        app(AccessResolver::class)->invalidateUserCache($user->id);

        Notification::make()
            ->title('Pengaturan Berhasil Disimpan')
            ->body("Hak akses sistem untuk {$user->name} berhasil diperbarui.")
            ->success()
            ->send();
    }
}
