<?php

namespace App\Filament\Resources;

use App\Filament\Exports\AdvancedKantorExporter;
use App\Filament\Exports\DetailedKantorExporter;
use App\Filament\Exports\KantorExporter;
use App\Filament\Resources\KantorResource\Pages;
use App\Filament\Resources\KantorResource\RelationManagers;
use App\Imports\KantorDataImport;
use App\Models\Kantor;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class KantorResource extends Resource
{
    protected static ?string $model = Kantor::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $pluralLabel = 'Kantor';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kantor')
                    ->label('Kantor')
                    ->maxLength(255),
                TextInput::make('nopen')
                    ->label('Nopen')
                    ->required()
                    ->numeric(),
                TextInput::make('kab_kota')
                    ->label('Kabupaten/Kota')
                    ->required()
                    ->maxLength(255),
                TextInput::make('alokasi_kpm')
                    ->label('Alokasi KPM')
                    ->required()
                    ->numeric(),
                TextInput::make('alokasi_jml_uang')
                    ->label('Alokasi Jumlah Uang')
                    ->required()
                    ->numeric()
                    ->step(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kantor')
                    ->label('Kantor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nopen')
                    ->label('Nopen')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kab_kota')
                    ->label('Kabupaten/Kota')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alokasi_kpm')
                    ->label('Alokasi KPM')
                    ->formatStateUsing(fn($state) => number_format((float)$state, 0, ',', '.'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alokasi_jml_uang')
                    ->label('Alokasi Jumlah Uang')
                    ->formatStateUsing(fn($state) => number_format((float)$state, 0, ',', '.'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export Basic')
                    ->exporter(KantorExporter::class),
                // ExportAction::make()
                //     ->label('Export Detailed')
                //     ->exporter(DetailedKantorExporter::class),
                // ExportAction::make()
                //     ->label('Export Advanced')
                //     ->exporter(AdvancedKantorExporter::class),
                Action::make('import')
                    ->label('Import from Excel/CSV')
                    ->form([
                        FileUpload::make('attachment')
                            ->required()
                            ->label('CSV/Excel File')
                            ->storeFiles(false)
                    ])
                    ->action(function (array $data) {
                        $file = $data['attachment'];
                        try {
                            Excel::import(new KantorDataImport, $file);
                            Notification::make()
                                ->title('Import Successful')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Import Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make()
                        ->label('Export Basic')
                        ->exporter(KantorExporter::class),
                    // Tables\Actions\ExportBulkAction::make()
                    //     ->label('Export Detailed')
                    //     ->exporter(DetailedKantorExporter::class),
                    // Tables\Actions\ExportBulkAction::make()
                    //     ->label('Export Advanced')
                    //     ->exporter(AdvancedKantorExporter::class)
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SufixesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKantor::route('/'),
            'create' => Pages\CreateKantor::route('/create'),
            'edit' => Pages\EditKantor::route('/{record}/edit'),
        ];
    }
}
