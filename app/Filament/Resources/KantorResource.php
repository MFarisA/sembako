<?php

namespace App\Filament\Resources;

use App\Exports\KantorExport; // Ini adalah Maatwebsite Export
use App\Filament\Exports\AdvancedKantorExporter;
use App\Filament\Exports\DetailedKantorExporter;
use App\Filament\Exports\KantorExporter as FilamentKantorExporter; // Alias untuk menghindari konflik nama
use App\Filament\Resources\KantorResource\Pages;
use App\Filament\Resources\KantorResource\RelationManagers;
use App\Imports\KantorDataImport;
use App\Models\Kantor; //
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
                Action::make('export_all')
                    ->label('Export Semua Data')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        return Excel::download(
                            new KantorExport(),
                            'data-semua-kantor-' . now()->format('YmdHis') . '.xlsx'
                        );
                    }),
                Action::make('import')
                    ->label('Import from Excel/CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->form([
                        FileUpload::make('attachment')
                            ->required()
                            ->label('CSV/Excel File')
                            ->acceptedFileTypes(['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            ->maxSize(10240) // 10MB max
                            ->helperText('Supported formats: CSV, XLS, XLSX. Max size: 10MB.')
                            ->storeFiles(false)
                    ])
                    ->action(function (array $data) {
                        $file = $data['attachment'];
                        try {
                            Excel::import(new KantorDataImport, $file);
                            
                            // Get import statistics
                            $kantorCount = Kantor::count();
                            $sufixCount = \App\Models\Sufix::count();
                            $subSufixCount = \App\Models\SubSufix::count();
                            $totalCount = \App\Models\Total::count();
                            
                            Notification::make()
                                ->title('Import Successful')
                                ->body("Imported: {$kantorCount} Kantors, {$sufixCount} Sufixes, {$subSufixCount} SubSufixes, {$totalCount} Totals")
                                ->success()
                                ->duration(5000)
                                ->send();
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Import error: ' . $e->getMessage());
                            
                            Notification::make()
                                ->title('Import Failed')
                                ->body('Error: ' . $e->getMessage())
                                ->danger()
                                ->duration(8000)
                                ->send();
                        }
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('Export Single')
                    ->label('Export Data Ini')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (?Kantor $record) {
                        if ($record === null) {
                            Notification::make()
                                ->title('Kesalahan Ekspor')
                                ->body('Data kantor tidak ditemukan untuk diekspor.')
                                ->danger()
                                ->send();
                            return;
                        }
                        return Excel::download(
                            new KantorExport([$record->id]),
                            'data-kantor-' . $record->kantor . '-' . now()->format('YmdHis') . '.xlsx'
                        );
                    }),
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