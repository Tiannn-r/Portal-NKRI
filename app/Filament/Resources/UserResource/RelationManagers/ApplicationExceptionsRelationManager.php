<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ApplicationExceptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'applicationExceptions';

    protected static ?string $title = 'Pengaturan Penampilan Sistem Pengguna';
    protected static ?string $modelLabel = 'Sistem Terhubung';
    protected static ?string $pluralModelLabel = 'Daftar Sistem & Status Tampil';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('type')
                ->label('Status Penampilan')
                ->options([
                    'allow' => 'TAMPILKAN (Beri Akses Khusus ke Pengguna Ini)',
                    'deny'  => 'SEMBUNYIKAN (Blokir Mutlak - Pengguna Tidak Boleh Melihat/Membuka)',
                ])
                ->default('allow')
                ->required(),

            Forms\Components\TextInput::make('notes')
                ->label('Catatan Otorisasi')
                ->placeholder('Contoh: Ditugaskan ke modul proyek rahasia')
                ->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Sistem')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Status Tampil')
                    ->colors([
                        'success' => 'allow',
                        'danger'  => 'deny',
                    ])
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'allow' => 'DITAMPILKAN (Allow)',
                        'deny'  => 'DISEMBUNYIKAN (Deny)',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(40)
                    ->placeholder('-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Filter Status')
                    ->options([
                        'allow' => 'Ditampilkan',
                        'deny'  => 'Disembunyikan',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Atur Tampilan Sistem Baru')
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Select::make('type')
                            ->label('Status Tampil untuk User Ini')
                            ->options([
                                'allow' => 'TAMPILKAN (Beri Izin Akses Khusus)',
                                'deny'  => 'SEMBUNYIKAN (Blokir Mutlak / Deny Wins)',
                            ])
                            ->default('allow')
                            ->required(),
                        Forms\Components\TextInput::make('notes')
                            ->label('Catatan / Alasan')
                            ->placeholder('Contoh: Penugasan khusus atau pembatasan rahasia')
                            ->maxLength(255),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Ubah Status'),
                Tables\Actions\DetachAction::make()
                    ->label('Hapus Pengaturan'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->label('Hapus Pengaturan Terpilih'),
                ]),
            ]);
    }
}
