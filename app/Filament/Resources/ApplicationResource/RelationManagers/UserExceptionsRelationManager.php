<?php

namespace App\Filament\Resources\ApplicationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class UserExceptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'userExceptions';

    protected static ?string $title = 'Pengecualian Akses Per User (Allow / Deny)';
    protected static ?string $modelLabel = 'Pengecualian User';
    protected static ?string $pluralModelLabel = 'Daftar Pengecualian User';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('type')
                ->label('Jenis Pengecualian')
                ->options([
                    'allow' => 'ALLOW (Izin Khusus - Berikan akses meski role/departemen tidak sesuai)',
                    'deny'  => 'DENY (Blokir Khusus - Selalu batalkan akses untuk user ini)',
                ])
                ->required(),

            Forms\Components\TextInput::make('notes')
                ->label('Catatan / Alasan')
                ->placeholder('Contoh: Ditugaskan sementara untuk audit internal')
                ->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pengguna')
                    ->weight('semibold')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('department.name')
                    ->label('Departemen')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Jenis Pengecualian')
                    ->colors([
                        'success' => 'allow',
                        'danger'  => 'deny',
                    ])
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'allow' => 'ALLOW (Diizinkan Khusus)',
                        'deny'  => 'DENY (Diblokir Khusus)',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(40)
                    ->placeholder('-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Filter Jenis')
                    ->options([
                        'allow' => 'Allow (Izin)',
                        'deny'  => 'Deny (Blokir)',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Tambah Pengecualian User')
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Select::make('type')
                            ->label('Jenis Pengecualian')
                            ->options([
                                'allow' => 'ALLOW (Izin Khusus - Override Izin)',
                                'deny'  => 'DENY (Blokir Khusus - Deny Wins)',
                            ])
                            ->default('allow')
                            ->required(),
                        Forms\Components\TextInput::make('notes')
                            ->label('Catatan / Alasan')
                            ->placeholder('Contoh: Penugasan khusus atau pembatasan sementara')
                            ->maxLength(255),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Ubah'),
                Tables\Actions\DetachAction::make()
                    ->label('Hapus Pengecualian'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->label('Hapus Pengecualian Terpilih'),
                ]),
            ]);
    }
}
