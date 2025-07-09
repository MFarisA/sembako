<?php

namespace App\Filament\Resources\KantorResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SufixesRelationManager extends RelationManager
{
    protected static string $relationship = 'sufixes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_sufix')
                    ->label('NAMA SUFIX')
                    ->required()
                    ->maxLength(255),
                Select::make('kantor_id')
                    ->label('KANTOR')
                    ->relationship('kantor', 'kantor')
                    ->searchable()
                    ->required()
                    ->preload(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('alokasi')
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('Manage Totals')
                    ->url(fn ($record) => route('filament.admin.resources.totals.index', [
                        'sufix' => $record->id,
                    ]))
                    ->icon('heroicon-o-calculator')
                    ->label('Manage Totals')
                    ->color('primary'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
