<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubSufixResource\Pages;
use App\Models\SubSufix;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubSufixResource extends Resource
{
    protected static ?string $model = SubSufix::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Sub Sufix';
    protected static ?string $pluralLabel = 'Sub Sufixes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sufix_id')
                    ->relationship('sufix', 'nama_sufix'),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sufix.nama_sufix')
                    ->sortable()
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubSufixes::route('/'),
            'create' => Pages\CreateSubSufix::route('/create'),
            'edit' => Pages\EditSubSufix::route('/{record}/edit'),
        ];
    }
}
