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
            'is_super_admin' => false,
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
                                        $user->id => "{$user->name} ({$user->email})" . ($user->hasRole('Super Admin') ? ' [Super Admin]' : ''),
                                    ])
                            )
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if (!$state) {
                                    $set('is_super_admin', false);
                                    $set('allowed_apps', []);
                                    return;
                                }

                                $user = User::with('applications')->find($state);
                                if (!$user) {
                                    return;
                                }

                                $set('is_super_admin', $user->hasRole('Super Admin'));

                                $allowed = $user->applications
                                    ->pluck('id')
                                    ->map(fn ($id) => (string) $id)
                                    ->toArray();

                                $set('allowed_apps', $allowed);
                            })
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Hak Akses Administrator')
                    ->description('Tentukan apakah pengguna ini diberikan kewenangan untuk masuk dan mengelola CMS Admin.')
                    ->visible(fn (Forms\Get $get) => filled($get('user_id')))
                    ->schema([
                        Forms\Components\Toggle::make('is_super_admin')
                            ->label('Beri Hak Akses Administrator (Super Admin)')
                            ->helperText('Jika diaktifkan, pengguna ini dapat login ke /admin untuk mengelola sistem dan pengguna, serta otomatis dapat mengakses seluruh sistem di portal.')
                            ->live()
                            ->disabled(fn (Forms\Get $get) => (int) $get('user_id') === auth()->id()),
                    ]),

                Forms\Components\Section::make('Hak Akses Sistem Digital')
                    ->description('Centang sistem-sistem apa saja yang diizinkan untuk dibuka oleh pengguna yang dipilih.')
                    ->visible(fn (Forms\Get $get) => filled($get('user_id')))
                    ->schema([
                        Forms\Components\Placeholder::make('super_admin_notice')
                            ->label('Status Hak Akses')
                            ->content('Pengguna ini berstatus Super Administrator. Seluruh sistem digital di bawah ini otomatis dapat dibuka oleh Super Admin di portal.')
                            ->visible(fn (Forms\Get $get) => (bool) $get('is_super_admin')),

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

        // Perbarui Hak Akses Admin (Super Admin)
        $isSuperAdmin = !empty($state['is_super_admin']);
        if ($isSuperAdmin) {
            $user->assignRole('Super Admin');
        } else {
            // Hindari mencabut Super Admin akun sendiri agar tidak terkunci
            if ($user->id !== auth()->id()) {
                $user->removeRole('Super Admin');
            }
        }

        $allowed = $state['allowed_apps'] ?? [];

        // Simpan sistem yang diizinkan
        $user->applications()->sync($allowed);

        // Invalidate cache
        app(AccessResolver::class)->invalidateUserCache($user->id);

        Notification::make()
            ->title('Pengaturan Berhasil Disimpan')
            ->body("Hak akses sistem dan peran untuk {$user->name} berhasil diperbarui.")
            ->success()
            ->send();
    }
}
