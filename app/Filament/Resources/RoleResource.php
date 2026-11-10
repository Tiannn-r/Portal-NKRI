<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Role Pengguna';
    protected static ?string $modelLabel = 'Role Pengguna';
    protected static ?string $pluralModelLabel = 'Role Pengguna';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?int $navigationSort = 3;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Role')
                ->description('Definisikan nama role atau jabatan untuk penentuan hak akses aplikasi.')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Role')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true)
                        ->placeholder('Contoh: Finance Specialist, Operator Pabrik, Quality Control')
                        ->disabled(fn (?Role $record) => $record?->name === 'Super Admin')
                        ->helperText(fn (?Role $record) => $record?->name === 'Super Admin'
                            ? 'Role Super Admin dilindungi oleh sistem dan tidak dapat diubah namanya.'
                            : 'Gunakan nama jabatan atau wewenang yang spesifik.'),

                    Forms\Components\Hidden::make('guard_name')
                        ->default('web'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Role')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Super Admin' => 'danger',
                        'Admin Sistem' => 'warning',
                        'Admin Konten' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('users_count')
                    ->label('Jumlah Pengguna')
                    ->counts('users')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('guard_name')
                    ->label('Guard')
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn (Role $record) => $record->name === 'Super Admin'),

                Tables\Actions\DeleteAction::make()
                    ->hidden(fn (Role $record) => $record->name === 'Super Admin')
                    ->modalHeading('Hapus Role Pengguna')
                    ->modalDescription('Apakah Anda yakin ingin menghapus role ini? Seluruh relasi izin aplikasi dan penugasan ke pengguna terkait akan dicabut otomatis.')
                    ->before(function (Role $record, Tables\Actions\DeleteAction $action) {
                        if ($record->name === 'Super Admin') {
                            Notification::make()
                                ->danger()
                                ->title('Aksi Ditolak')
                                ->body('Role Super Admin adalah role utama sistem dan tidak boleh dihapus.')
                                ->send();
                            $action->halt();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Collection $records, Tables\Actions\DeleteBulkAction $action) {
                            if ($records->contains('name', 'Super Admin')) {
                                Notification::make()
                                    ->danger()
                                    ->title('Aksi Ditolak')
                                    ->body('Daftar yang dipilih memuat role Super Admin yang tidak boleh dihapus.')
                                    ->send();
                                $action->halt();
                            }
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
