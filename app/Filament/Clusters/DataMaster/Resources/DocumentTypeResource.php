<?php

namespace App\Filament\Clusters\DataMaster\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DocumentType;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use App\Filament\Clusters\DataMaster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use App\Filament\Clusters\DataMaster\Resources\DocumentTypeResource\Pages;
use App\Filament\Clusters\DataMaster\Resources\DocumentTypeResource\RelationManagers;

class DocumentTypeResource extends Resource
{
    protected static ?string $model = DocumentType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = DataMaster::class;

    protected static ?string $navigationGroup = 'Pengaturan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Dokumen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->label('Dibuat'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                DateRangeFilter::make('created_at'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth(MaxWidth::Medium),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDocumentTypes::route('/'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Tipe Dokumen';
    }

    public static function getModelLabel(): string
    {
        return 'Tipe Dokumen';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Tipe Dokumen';
    }

    public function getTitle(): string
    {
        return 'Tipe Dokumen';
    }
}
