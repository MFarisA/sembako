<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SufixResource\Pages;
use App\Filament\Resources\SufixResource\RelationManagers;
use App\Models\Sufix;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class SufixResource extends Resource
{
    protected static ?string $model = Sufix::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralLabel = 'Sufix';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_sufix')
                    ->label('NAMA SUFIX')
                    ->maxLength(255),
                Select::make('kantor_id')
                    ->label('KANTOR')
                    ->relationship('kantor', 'kantor')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kantor.kantor')
                    ->label('KANTOR')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_sufix')
                    ->label('NAMA SUFIX')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('CREATED AT')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            \App\Filament\Resources\SufixResource\RelationManagers\SubSufixesRelationManager::class,
            \App\Filament\Resources\SufixResource\RelationManagers\TotalsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSufixes::route('/'),
            'create' => Pages\CreateSufix::route('/create'),
            'edit' => Pages\EditSufix::route('/{record}/edit'),
        ];
    }
}
