<?php

namespace App\Filament\Clusters\DataMaster\Resources\PaymentMethodResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DigitalPaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'digitalPayments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor')
                    ->searchable()
                    ->default('-'),
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
