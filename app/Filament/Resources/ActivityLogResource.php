<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Audit Log';
    protected static ?string $navigationGroup = 'Sistem & Keamanan';
    protected static ?int $navigationSort = 10;
    protected static ?string $modelLabel = 'Audit Log';
    protected static ?string $pluralModelLabel = 'Audit Logs (Riwayat Aktivitas)';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Rincian Log Aktivitas')
                ->schema([
                    Forms\Components\TextInput::make('log_name')
                        ->label('Kategori Log'),

                    Forms\Components\TextInput::make('event')
                        ->label('Jenis Event (Aksi)'),

                    Forms\Components\TextInput::make('description')
                        ->label('Deskripsi'),

                    Forms\Components\DateTimePicker::make('created_at')
                        ->label('Waktu Kejadian'),

                    Forms\Components\TextInput::make('causer_type')
                        ->label('Tipe Pelaku (Causer)'),

                    Forms\Components\TextInput::make('causer.name')
                        ->label('Nama Pelaku'),

                    Forms\Components\TextInput::make('subject_type')
                        ->label('Entitas Objek'),

                    Forms\Components\TextInput::make('subject_id')
                        ->label('ID Objek'),

                    Forms\Components\Textarea::make('properties')
                        ->label('Data Perubahan (JSON)')
                        ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))
                        ->columnSpanFull()
                        ->rows(8),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable(),

                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Dilakukan Oleh')
                    ->default('Sistem / Tamu')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('event')
                    ->label('Event')
                    ->colors([
                        'success' => 'created',
                        'warning' => 'updated',
                        'danger'  => 'deleted',
                        'info'    => fn ($state) => !in_array($state, ['created', 'updated', 'deleted']),
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Objek Data')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->badge()
                    ->color('gray')
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->label('Event')
                    ->options([
                        'created' => 'Created (Dibuat)',
                        'updated' => 'Updated (Diubah)',
                        'deleted' => 'Deleted (Dihapus)',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'view'  => Pages\ViewActivityLog::route('/{record}'),
        ];
    }
}
