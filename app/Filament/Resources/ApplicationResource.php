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
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug URL')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('Digunakan di URL: /apps/{slug}/open')
                        ->columnSpan(2),

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
                        ->required()
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
