<?php

namespace App\Filament\Resources\SubSufixResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TotalsRelationManager extends RelationManager
{
    protected static string $relationship = 'totals';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('jumlah_alokasi_bnba')
                    ->numeric()
                    ->step(1),
                Forms\Components\TextInput::make('jumlah_alokasi_biaya')
                    ->numeric()
                    ->step(1),
                Forms\Components\TextInput::make('jumlah_realisasi')
                    ->numeric()
                    ->step(1),
                Forms\Components\TextInput::make('jumlah_realisasi_biaya')
                    ->numeric()
                    ->step(1),
                Forms\Components\TextInput::make('persentase')
                    ->numeric()
                    ->step(0.01),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('jumlah_alokasi_bnba')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('jumlah_alokasi_biaya')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('jumlah_realisasi')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('jumlah_realisasi_biaya')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('persentase')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format($state, 2, ',', '.')),
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
