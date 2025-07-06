<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\CertificationList;
use Illuminate\Support\HtmlString;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CertificationListResource\Pages;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use App\Filament\Resources\CertificationListResource\RelationManagers;

class CertificationListResource extends Resource
{
    protected static ?string $model = CertificationList::class;

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
                                return $record->assessmentSchedule?->certificationScheme?->judul_skema ?? 'Skema Sertifikasi';
                            }
                        )
                            ->schema([
                                Forms\Components\Placeholder::make('nomor_skema')
                                    ->content(fn($record): string => $record->assessmentSchedule->certificationScheme->nomor_skema ?? '-'),
                                Forms\Components\Placeholder::make('tujuan_skema')
                                    ->content(fn($record): string => $record->assessmentSchedule->certificationScheme->tujuan_skema ?? '-'),
                                Forms\Components\Placeholder::make('kode_referensi')
                                    ->label('Kode Referensi / Kategori')
                                    ->content(fn($record): string => $record->assessmentSchedule->certificationScheme->kode_referensi ?? '-'),
                                Forms\Components\Placeholder::make('tahun_terbit')
                                    ->columns(2)
                                    ->content(fn($record): string => $record->assessmentSchedule->certificationScheme->tahun_terbit ?? '-'),
                                Forms\Components\Placeholder::make('harga')
                                    ->content(
                                        fn($record): string =>
                                        $record?->assessmentSchedule?->certificationScheme?->harga !== null
                                            ? 'Rp ' . number_format($record->assessmentSchedule->certificationScheme->harga, 0, ',', '.')
                                            : '-'
                                    ),
                                Forms\Components\Placeholder::make('deskripsi_skema')
                                    ->columnSpanFull()
                                    ->content(fn($record): string => $record->assessmentSchedule->certificationScheme->deskripsi_skema ?? '-'),
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
                Tables\Columns\TextColumn::make('assessmentSchedule.certificationScheme.judul_skema')
                    ->label('Nama Sertifikasi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user_status')
                    ->label('Status Anda')
                    ->state(function ($record) {
                        $userCert = $record->userCertification()
                            ->where('user_id', Auth::user()->id)
                            ->first();

                        return $userCert ? ucfirst(str_replace('_', ' ', $userCert->status)) : 'Belum mendaftar';
                    })
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'pending' => 'warning',
                        'documents uploaded' => 'blue',
                        'payment done' => 'green',
                        'verified' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('participants_count')
                    ->label('Jumlah Peserta')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: ',',
                        thousandsSeparator: '.',
                    )
                    ->getStateUsing(function ($record) {
                        return $record->userCertification()->count();
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->slideOver()
                    ->modalWidth(MaxWidth::SevenExtraLarge),
                Tables\Actions\Action::make('register')
                    ->label('Daftar')
                    ->requiresConfirmation()
                    ->visible(function ($record) {
                        return !$record->userCertification()
                            ->where('user_id', Auth::user()->id)
                            ->exists();
                    })
                    ->action(function ($record) {
                        $user = Auth::user();

                        // Buat userCertification
                        $userCertification = $record->userCertification()->create([
                            'user_id' => $user->id,
                            'status' => 'pending',
                        ]);

                        // Ambil semua dokumen yang wajib dari skema sertifikasi
                        $documentTypes = $record->assessmentSchedule->certificationScheme->documentTypes ?? collect();

                        // Buat entri kosong untuk tiap documentType
                        foreach ($documentTypes as $docType) {
                            $userCertification->documents()->create([
                                'document_type_id' => $docType->id,
                                // file_path akan diisi nanti setelah user upload
                            ]);
                        }

                        // Kirim notifikasi
                        Notification::make()
                            ->success()
                            ->title('Pendaftaran Berhasil')
                            ->body('Silahkan klik tombol "Lihat Pendaftaran Saya" untuk mengunggah dokumen.')
                            ->persistent()
                            ->actions([
                                Action::make('lihat Pendaftaran Saya')
                                    ->button()
                                    ->url(UserCertificationResource::getUrl('edit', ['record' => $userCertification])),
                            ])
                            ->send();
                    }),

                // Tombol Batalkan Pendaftaran
                Tables\Actions\Action::make('cancel_registration')
                    ->label('Batalkan')
                    ->visible(function ($record) {
                        $userCert = $record->userCertification()
                            ->where('user_id', Auth::user()->id)
                            ->first();

                        return $userCert && in_array($userCert->status, ['pending', 'documents_uploaded']);
                    })
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->userCertification()
                            ->where('user_id', Auth::user()->id)
                            ->delete();

                        Notification::make()
                            ->title('Pendaftaran Dibatalkan')
                            ->success()
                            ->send();
                    })
            ]);
    }

    // Add this method to modify the base query
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['assessmentSchedule.certificationScheme', 'userCertification'])
            ->whereHas('assessmentSchedule', function ($query) {
                $query->where('is_active', true);
            });
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
            'index' => Pages\ListCertificationLists::route('/'),
            'create' => Pages\CreateCertificationList::route('/create'),
            'edit' => Pages\EditCertificationList::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Daftar Sertifikasi';
    }

    public static function getModelLabel(): string
    {
        return 'Daftar Sertifikasi';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Daftar Sertifikasi';
    }

    public function getTitle(): string
    {
        return 'Daftar Sertifikasi';
    }
}
