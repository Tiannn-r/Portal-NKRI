<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Department;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label('Email Korporat')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Akun Aktif')
                        ->default(true)
                        ->helperText('Non-aktifkan untuk memblokir akses login.'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Kata Sandi')
                ->schema([
                    Forms\Components\TextInput::make('password')
                        ->label('Kata Sandi Baru')
                        ->password()
                        ->revealable()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation) => $operation === 'create')
                        ->minLength(8)
                        ->helperText('Kosongkan jika tidak ingin mengganti kata sandi.'),
                ]),

            Forms\Components\Section::make('Hak Akses Administrator')
                ->description('Tentukan apakah pengguna ini memiliki hak untuk masuk dan mengelola CMS Admin.')
                ->schema([
                    Forms\Components\Toggle::make('is_admin')
                        ->label('Beri Akses ke Panel Admin (Super Admin)')
                        ->helperText('Jika diaktifkan, pengguna ini dapat login ke /admin dan mengelola sistem, pengguna, serta pengaturan portal.')
                        ->default(false)
                        ->disabled(fn (?User $record) => $record && $record->id === auth()->id())
                        ->afterStateHydrated(function (Forms\Components\Toggle $component, ?User $record) {
                            $component->state($record ? $record->hasRole('Super Admin') : false);
                        }),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('is_admin')
                    ->label('Akses Admin')
                    ->state(fn (User $record) => $record->hasRole('Super Admin') ? 'Super Admin' : 'Pengguna Biasa')
                    ->badge()
                    ->color(fn (string $state) => $state === 'Super Admin' ? 'danger' : 'gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_admin')
                    ->label('Tipe Akses')
                    ->placeholder('Semua Pengguna')
                    ->trueLabel('Hanya Super Admin')
                    ->falseLabel('Hanya Pengguna Biasa')
                    ->queries(
                        true: fn ($query) => $query->whereHas('roles', fn ($q) => $q->where('name', 'Super Admin')),
                        false: fn ($query) => $query->whereDoesntHave('roles', fn ($q) => $q->where('name', 'Super Admin')),
                    ),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Akun')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
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
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
