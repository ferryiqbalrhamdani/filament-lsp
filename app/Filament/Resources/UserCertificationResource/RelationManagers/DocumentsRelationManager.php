<?php

namespace App\Filament\Resources\UserCertificationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('document_type_id')
                    ->label('Jenis Dokumen')
                    ->options(function () {
                        $certificationScheme = $this->getOwnerRecord()
                            ->certificationList
                            ->assessmentSchedule
                            ->certificationScheme;

                        // Ambil semua document types yang tersedia
                        $allDocumentTypes = $certificationScheme->documentTypes;

                        // Ambil document_type_id yang sudah diinput
                        $existingDocumentTypeIds = $this->getOwnerRecord()
                            ->documents()
                            ->pluck('document_type_id');

                        // Filter hanya yang belum ada
                        $availableDocumentTypes = $allDocumentTypes
                            ->whereNotIn('id', $existingDocumentTypeIds);

                        return $availableDocumentTypes->pluck('name', 'id');
                    })
                    ->required()
                    ->searchable(),

                Forms\Components\FileUpload::make('file_path')
                    ->label('File Dokumen')
                    ->required()
                    ->directory('certification-documents')
                    ->preserveFilenames(),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Dokumen')
            ->defaultSort('created_at', 'desc')
            ->recordTitleAttribute('documentType.name')
            ->columns([
                Tables\Columns\TextColumn::make('documentType.name')
                    ->label('Jenis Dokumen'),

                Tables\Columns\ImageColumn::make('file_path')
                    ->label('File'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Upload Pada')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Upload Dokumen'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        dd($this->getOwnerRecord());

        return $data;
    }
}
