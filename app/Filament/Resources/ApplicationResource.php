<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Filament\Resources\ApplicationResource\RelationManagers;
use App\Models\AppCategory;
use App\Models\Application;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Sistem & Aplikasi';
    protected static ?string $navigationGroup = 'Manajemen Sistem';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Dasar')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Sistem')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi Singkat')
                        ->maxLength(500)
                        ->rows(2)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('url')
                        ->label('URL Sistem (http/https)')
                        ->required()
                        ->url()
                        ->maxLength(2048)
                        ->prefix('https://')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('login_hint')
                        ->label('Login Hint')
                        ->maxLength(255)
                        ->placeholder('Contoh: Login dengan akun Odoo Anda')
                        ->helperText('Teks panduan singkat yang tampil di kartu sistem.')
                        ->columnSpanFull(),
                ])
                ->columns(4),

            Forms\Components\Section::make('Kategori & PIC')
                ->schema([
                    Forms\Components\Select::make('app_category_id')
                        ->label('Kategori')
                        ->relationship('category', 'name')
                        ->nullable()
                        ->placeholder('Pilih Kategori (Opsional)')
                        ->searchable()
                        ->preload(),

                    Forms\Components\Select::make('pic_user_id')
                        ->label('PIC (Penanggung Jawab)')
                        ->relationship('pic', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Visual & Media Kartu Sistem')
                ->description('Atur logo yang tampil di samping judul dan screenshot website yang tampil di tengah kartu.')
                ->schema([
                    Forms\Components\FileUpload::make('logo')
                        ->label('Logo Sistem (Tampil di Judul)')
                        ->image()
                        ->disk('public')
                        ->directory('applications/logos')
                        ->visibility('public')
                        ->fetchFileInformation(false)
                        ->maxSize(2048)
                        ->helperText('Format PNG/SVG/JPG, rasio kotak disarankan. Jika kosong, sistem menggunakan ikon cadangan.'),

                    Forms\Components\Select::make('icon')
                        ->label('Ikon Cadangan (Lucide)')
                        ->options([
                            'Users' => 'Users (SDM / Kepegawaian)',
                            'Package' => 'Package (Logistik / Gudang)',
                            'Receipt' => 'Receipt (Keuangan / Faktur)',
                            'Briefcase' => 'Briefcase (CRM / Bisnis)',
                            'LifeBuoy' => 'LifeBuoy (Bantuan / IT)',
                            'ShoppingCart' => 'ShoppingCart (Pengadaan)',
                            'BarChart3' => 'BarChart3 (BI / Analitik)',
                            'FileText' => 'FileText (Dokumen / Legal)',
                            'Clock' => 'Clock (Presensi / Waktu)',
                            'Layers' => 'Layers (Umum)',
                            'Cpu' => 'Cpu (Teknologi)',
                            'Building' => 'Building (Manajemen)',
                            'Truck' => 'Truck (Distribusi)',
                        ])
                        ->default('Layers')
                        ->searchable(),

                    Forms\Components\FileUpload::make('image')
                        ->label('Gambar / Screenshot Website (Tampil di Tengah Kartu)')
                        ->image()
                        ->disk('public')
                        ->directory('applications/previews')
                        ->visibility('public')
                        ->fetchFileInformation(false)
                        ->maxSize(5120)
                        ->helperText('Upload tangkapan layar antarmuka sistem (rasio 16:9 disarankan). Tampil di bagian tengah kartu portal.')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Status & Tampilan')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'active'      => 'Aktif',
                            'maintenance' => 'Sedang Maintenance',
                            'inactive'    => 'Nonaktif (Tersembunyi)',
                        ])
                        ->required()
                        ->default('active'),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Urutan Tampil')
                        ->numeric()
                        ->default(0)
                        ->helperText('Angka lebih kecil tampil lebih awal.'),

                    Forms\Components\Toggle::make('open_in_new_tab')
                        ->label('Buka di Tab Baru')
                        ->default(true),
                ])
                ->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Preview Web')
                    ->disk('public')
                    ->circular(false)
                    ->square()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Sistem')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->limit(40)
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'maintenance',
                        'danger'  => 'inactive',
                    ])
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'active'      => 'Aktif',
                        'maintenance' => 'Maintenance',
                        'inactive'    => 'Nonaktif',
                        default       => $state,
                    }),

                Tables\Columns\IconColumn::make('open_in_new_tab')
                    ->label('Tab Baru')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active'      => 'Aktif',
                        'maintenance' => 'Maintenance',
                        'inactive'    => 'Nonaktif',
                    ]),

                Tables\Filters\SelectFilter::make('app_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'edit'   => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
}
