<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TotalResource\Pages;
use App\Filament\Resources\TotalResource\RelationManagers;
use App\Models\Total;
use App\Filament\Exports\AdvancedKantorExporter;
use Doctrine\DBAL\Connection\StaticServerVersionProvider;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TotalResource extends Resource
{
    protected static ?string $model = Total::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $pluralLabel = 'Total';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('jumlah_alokasi_bnba')
                    ->label('JUMLAH ALOKASI BNBA')
                    ->numeric()
                    ->required(),
                TextInput::make('jumlah_alokasi_biaya')
                    ->label('JUMLAH ALOKASI BIAYA')
                    ->numeric()
                    ->step(1)
                    ->required(),
                TextInput::make('jumlah_realisasi')
                    ->label('JUMLAH REALISASI')
                    ->numeric()
                    ->step(1)
                    ->required(),
                TextInput::make('jumlah_realisasi_biaya')
                    ->label('JUMLAH REALISASI BIAYA')
                    ->numeric()
                    ->step(1)
                    ->required(),
                TextInput::make('persentase')
                    ->label('PERSENTASE')
                    ->numeric()
                    ->step(0.01)
                    ->required(),
                Select::make('sufix_id')
                    ->relationship('sufix', 'nama_sufix')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama_sufix} - {$record->kantor->kantor}")
                    ->searchable()
                    ->nullable()
                    ->placeholder('Leave empty for positional total'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
                TextColumn::make('id')
                    ->label('DATA')
                    ->formatStateUsing(fn($record) => $record->sufix_id ? "Sufix: {$record->sufix->nama_sufix}" : "Data {$record->id}")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jumlah_alokasi_bnba')
                    ->label('JUMLAH ALOKASI BNBA')
                    ->formatStateUsing(fn($state) => number_format((float)$state, 0, ',', '.'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jumlah_alokasi_biaya')
                    ->label('JUMLAH ALOKASI BIAYA')
                    ->formatStateUsing(fn($state) => number_format((float)$state, 0, ',', '.'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jumlah_realisasi')
                    ->label('JUMLAH REALISASI')
                    ->formatStateUsing(fn($state) => number_format((float)$state, 0, ',', '.'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jumlah_realisasi_biaya')
                    ->label('JUMLAH REALISASI BIAYA')
                    ->formatStateUsing(fn($state) => number_format((float)$state, 0, ',', '.'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('persentase')
                    ->label('PERSENTASE')
                    ->formatStateUsing(fn($state) => number_format((float)$state, 2))
                    ->searchable()
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTotals::route('/'),
            'create' => Pages\CreateTotal::route('/create'),
            'edit' => Pages\EditTotal::route('/{record}/edit'),
        ];
    }
}
