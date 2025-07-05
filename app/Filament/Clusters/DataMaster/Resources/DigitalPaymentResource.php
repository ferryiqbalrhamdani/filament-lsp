<?php

namespace App\Filament\Clusters\DataMaster\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DigitalPayment;
use Filament\Resources\Resource;
use App\Filament\Clusters\DataMaster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\DataMaster\Resources\DigitalPaymentResource\Pages;
use App\Filament\Clusters\DataMaster\Resources\DigitalPaymentResource\RelationManagers;

class DigitalPaymentResource extends Resource
{
    protected static ?string $model = DigitalPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = DataMaster::class;

    protected static ?string $navigationGroup = 'Pengaturan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('payment_method_id')
                    ->relationship('paymentMethod', 'name')
                    ->required()
                    ->label('Payment Method'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_nomor')
                    ->label('Gunakan Nomor?')
                    ->reactive()
                    ->required()
                    ->inline(false)
                    ->default(false),
                Forms\Components\TextInput::make('nomor')
                    ->label('Nomor')
                    ->placeholder('Contoh: nomor rekening, nomor VA, dll.')
                    ->maxLength(255)
                    ->visible(fn(Get $get) => $get('is_nomor')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('paymentMethod.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor')
                    ->searchable()
                    ->default('-'),
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
            'index' => Pages\ListDigitalPayments::route('/'),
            'create' => Pages\CreateDigitalPayment::route('/create'),
            'edit' => Pages\EditDigitalPayment::route('/{record}/edit'),
        ];
    }
}
