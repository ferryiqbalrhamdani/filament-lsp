<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\UserCertification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserCertificationResource\Pages;
use App\Filament\Resources\UserCertificationResource\RelationManagers;

class UserCertificationResource extends Resource
{
    protected static ?string $model = UserCertification::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Asesi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(
                            function ($record) {
                                return $record->certificationList?->assessmentSchedule?->certificationScheme?->judul_skema ?? 'Skema Sertifikasi';
                            }
                        )
                            ->schema([
                                Forms\Components\Placeholder::make('nomor_skema')
                                    ->content(fn($record): string => $record->certificationList?->assessmentSchedule->certificationScheme->nomor_skema ?? '-'),
                                Forms\Components\Placeholder::make('tujuan_skema')
                                    ->content(fn($record): string => $record->certificationList?->assessmentSchedule->certificationScheme->tujuan_skema ?? '-'),
                                Forms\Components\Placeholder::make('kode_referensi')
                                    ->label('Kode Referensi / Kategori')
                                    ->content(fn($record): string => $record->certificationList?->assessmentSchedule->certificationScheme->kode_referensi ?? '-'),
                                Forms\Components\Placeholder::make('tahun_terbit')
                                    ->columns(2)
                                    ->content(fn($record): string => $record->certificationList?->assessmentSchedule->certificationScheme->tahun_terbit ?? '-'),
                                Forms\Components\Placeholder::make('harga')
                                    ->content(
                                        fn($record): string =>
                                        $record?->certificationList?->assessmentSchedule?->certificationScheme?->harga !== null
                                            ? 'Rp ' . number_format($record->certificationList?->assessmentSchedule->certificationScheme->harga, 0, ',', '.')
                                            : '-'
                                    ),
                                Forms\Components\Placeholder::make('deskripsi_skema')
                                    ->columnSpanFull()
                                    ->content(fn($record): string => $record->certificationList?->assessmentSchedule->certificationScheme->deskripsi_skema ?? '-'),
                            ])
                            ->columns(3),
                        Forms\Components\Section::make('unit')
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('certificationList.assessmentSchedule.certificationScheme.judul_skema')
                    ->words(6)
                    ->label('Skema Sertifikasi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        // 'processing' => 'warning',
                        'verified' => 'success',
                        'payment_failed' => 'danger',
                        default => 'warning',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment.paymentMethod.name')
                    ->label('Metode Pembayaran')
                    ->badge()
                    ->default('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment.review.keterangan')
                    ->label('Keterangan')
                    ->default('-'),

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn($record) => UserCertificationResource::getUrl('edit', [
                        'record' => Crypt::encrypt($record->id),
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('user', function ($query) {
                $query->where('id', Auth::user()->id);
            });
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DocumentsRelationManager::class,
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserCertifications::route('/'),
            'create' => Pages\CreateUserCertification::route('/create'),
            'edit' => Pages\EditUserCertification::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Sertifikasi Saya';
    }

    public static function getModelLabel(): string
    {
        return 'Sertifikasi Saya';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Sertifikasi Saya';
    }

    public function getTitle(): string
    {
        return 'Sertifikasi Saya';
    }
}
