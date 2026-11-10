<?php

namespace App\Filament\Resources\ApplicationResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'allowedUsers';

    protected static ?string $title = 'Pengguna yang Memiliki Akses';
    protected static ?string $modelLabel = 'Pengguna Berizin';
    protected static ?string $pluralModelLabel = 'Pengguna yang Memiliki Akses';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Pengguna')
                ->required()
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
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Akun Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('pivot.created_at')
                    ->label('Diberikan Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Tambah Akses Pengguna')
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['name', 'email'])
                    ->modalHeading('Berikan Izin Akses ke Pengguna')
                    ->modalSubmitActionLabel('Berikan Akses'),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Cabut Akses')
                    ->modalHeading('Cabut Akses Pengguna')
                    ->modalDescription('Apakah Anda yakin ingin mencabut izin akses pengguna ini ke sistem terkait?'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->label('Cabut Akses Terpilih'),
                ]),
            ]);
    }
}

