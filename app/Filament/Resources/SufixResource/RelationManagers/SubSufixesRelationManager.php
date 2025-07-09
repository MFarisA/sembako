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
                    ->numeric()
                    ->step(1),
                Forms\Components\TextInput::make('alokasi_biaya')
                    ->numeric()
                    ->step(1),
                Forms\Components\TextInput::make('realisasi')
                    ->numeric()
                    ->step(1),
                Forms\Components\TextInput::make('realisasi_biaya')
                    ->numeric()
                    ->step(1),
                Forms\Components\TextInput::make('gagal_bayar_tolak')
                    ->numeric()
                    ->step(1),
                Forms\Components\TextInput::make('sisa_aktif')
                    ->numeric()
                    ->step(1),
                Forms\Components\TextInput::make('sisa_biaya')
                    ->numeric()
                    ->step(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('alokasi')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('alokasi_biaya')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('realisasi')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('realisasi_biaya')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('gagal_bayar_tolak')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('sisa_aktif')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('sisa_biaya')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0, ',', '.')),
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
