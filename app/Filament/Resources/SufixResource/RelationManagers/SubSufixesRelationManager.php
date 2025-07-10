<?php

namespace App\Filament\Resources\SufixResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SubSufixesRelationManager extends RelationManager
{
    protected static string $relationship = 'subSufixes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('alokasi')
                    ->label('ALOKASI')
                    ->numeric()
                    ->step(1)
                    ->required(),
                Forms\Components\TextInput::make('alokasi_biaya')
                    ->label('ALOKASI BIAYA')
                    ->numeric()
                    ->step(1),
                Forms\Components\TextInput::make('realisasi')
                    ->label('REALISASI')
                    ->numeric()
                    ->step(1),
                Forms\Components\TextInput::make('realisasi_biaya')
                    ->label('REALISASI BIAYA')
                    ->numeric()
                    ->step(1),
                Forms\Components\TextInput::make('gagal_bayar_tolak')
                    ->label('GAGAL BAYAR TOLAK')
                    ->numeric()
                    ->step(1),
                Forms\Components\TextInput::make('sisa_aktif')
                    ->label('SISA AKTIF')
                    ->numeric()
                    ->step(1),
                Forms\Components\TextInput::make('sisa_biaya')
                    ->label('SISA BIAYA')
                    ->numeric()
                    ->step(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('alokasi')
            ->columns([
                Tables\Columns\TextColumn::make('alokasi')
                    ->label('ALOKASI')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state): string => $state ? number_format($state, 0, ',', '.') : '0'),
                Tables\Columns\TextColumn::make('alokasi_biaya')
                    ->label('ALOKASI BIAYA')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state): string => $state ? number_format($state, 0, ',', '.') : '0'),
                Tables\Columns\TextColumn::make('realisasi')
                    ->label('REALISASI')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state): string => $state ? number_format($state, 0, ',', '.') : '0'),
                Tables\Columns\TextColumn::make('realisasi_biaya')
                    ->label('REALISASI BIAYA')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state): string => $state ? number_format($state, 0, ',', '.') : '0'),
                Tables\Columns\TextColumn::make('gagal_bayar_tolak')
                    ->label('GAGAL BAYAR TOLAK')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state): string => $state ? number_format($state, 0, ',', '.') : '0'),
                Tables\Columns\TextColumn::make('sisa_aktif')
                    ->label('SISA AKTIF')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state): string => $state ? number_format($state, 0, ',', '.') : '0'),
                Tables\Columns\TextColumn::make('sisa_biaya')
                    ->label('SISA BIAYA')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state): string => $state ? number_format($state, 0, ',', '.') : '0'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('CREATED AT')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
