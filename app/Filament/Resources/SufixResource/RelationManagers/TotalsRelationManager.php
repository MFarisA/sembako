<?php

namespace App\Filament\Resources\SufixResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TotalsRelationManager extends RelationManager
{
    protected static string $relationship = 'total';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('jumlah_alokasi_bnba')
                    ->label('JUMLAH ALOKASI BNBA')
                    ->numeric()
                    ->step(1)
                    ->required(),
                Forms\Components\TextInput::make('jumlah_alokasi_biaya')
                    ->label('JUMLAH ALOKASI BIAYA')
                    ->numeric()
                    ->step(1)
                    ->required(),
                Forms\Components\TextInput::make('jumlah_realisasi')
                    ->label('JUMLAH REALISASI')
                    ->numeric()
                    ->step(1)
                    ->required(),
                Forms\Components\TextInput::make('jumlah_realisasi_biaya')
                    ->label('JUMLAH REALISASI BIAYA')
                    ->numeric()
                    ->step(1)
                    ->required(),
                Forms\Components\TextInput::make('persentase')
                    ->label('PERSENTASE')
                    ->numeric()
                    ->step(0.01)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('jumlah_alokasi_bnba')
            ->columns([
                Tables\Columns\TextColumn::make('jumlah_alokasi_bnba')
                    ->label('JUMLAH ALOKASI BNBA')
                    ->formatStateUsing(fn($state) => number_format((float)$state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_alokasi_biaya')
                    ->label('JUMLAH ALOKASI BIAYA')
                    ->formatStateUsing(fn($state) => number_format((float)$state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_realisasi')
                    ->label('JUMLAH REALISASI')
                    ->formatStateUsing(fn($state) => number_format((float)$state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_realisasi_biaya')
                    ->label('JUMLAH REALISASI BIAYA')
                    ->formatStateUsing(fn($state) => number_format((float)$state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('persentase')
                    ->label('PERSENTASE (%)')
                    ->formatStateUsing(fn($state) => number_format((float)$state, 2, ',', '.') . '%')
                    ->sortable(),
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
