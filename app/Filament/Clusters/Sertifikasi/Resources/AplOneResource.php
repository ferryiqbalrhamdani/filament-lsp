<?php

namespace App\Filament\Clusters\Sertifikasi\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\AplOne;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Filament\Clusters\Sertifikasi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Sertifikasi\Resources\AplOneResource\Pages;
use App\Filament\Clusters\Sertifikasi\Resources\AplOneResource\RelationManagers;

class AplOneResource extends Resource
{
    protected static ?string $model = AplOne::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Sertifikasi::class;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('pemohon')
                            ->content(fn(AplOne $record): string => $record->paymentReview->payment->userCertification->user->name ?? '-'),
                        Forms\Components\Placeholder::make('Judul Skema')
                            ->content(fn(AplOne $record): string => $record->paymentReview->payment->userCertification->certificationList->assessmentSchedule->certificationScheme->judul_skema ?? '-'),
                        Forms\Components\Placeholder::make('Tanggal Mulai')
                            ->label('Tanggal Mulai')
                            ->content(function (AplOne $record) {
                                $tanggal = $record->paymentReview->payment->userCertification->certificationList->assessmentSchedule->tgl_mulai ?? null;

                                return $tanggal
                                    ? Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y')  // Output: 23 Oktober 2025
                                    : '-';
                            }),
                        Forms\Components\Placeholder::make('Assesor')
                            ->content(fn(AplOne $record): string => $record->user->name ?? '-'),
                        Forms\Components\Fieldset::make('Dokumnen')
                            ->schema([
                                Forms\Components\Placeholder::make('')
                                    ->content(function ($record) {
                                        // Ambil dokumen yang harus diunggah dari skema sertifikasi
                                        $requiredDocuments = $record?->paymentReview?->payment?->userCertification?->certificationList
                                            ?->assessmentSchedule?->certificationScheme?->documentTypes ?? collect();

                                        // Ambil dokumen yang sudah diupload user
                                        $uploadedDocuments = $record?->paymentReview?->payment?->userCertification?->documents ?? collect();

                                        if ($requiredDocuments->isEmpty()) {
                                            return 'Tidak ada dokumen persyaratan.';
                                        }

                                        $html = '<table class="w-full border border-gray-300 dark:border-gray-700 text-sm">';
                                        $html .= '
                                                    <thead class="bg-gray-200 dark:bg-gray-800 text-left">
                                                        <tr>
                                                            <th class="border border-gray-300 dark:border-gray-700 px-3 py-2">Nama Dokumen</th>
                                                            <th class="border border-gray-300 dark:border-gray-700 px-3 py-2">Tautan</th>
                                                            <th class="border border-gray-300 dark:border-gray-700 px-3 py-2">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                ';

                                        foreach ($requiredDocuments as $required) {
                                            // Cek apakah user sudah upload dokumen dengan tipe ini
                                            $uploaded = $uploadedDocuments->firstWhere('document_type_id', $required->id);

                                            $html .= '<tr>';
                                            $html .= '<td class="border border-gray-300 dark:border-gray-700 px-3 py-2 text-gray-800 dark:text-gray-100">'
                                                . e($required->name) . '</td>';

                                            if ($uploaded && $uploaded->file_path) {
                                                $fileUrl = Storage::url($uploaded->file_path);
                                                $html .= '<td class="border border-gray-300 dark:border-gray-700 px-3 py-2">
                                                            <a href="' . $fileUrl . '" target="_blank"
                                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline">
                                                                Lihat Dokumen
                                                            </a>
                                                        </td>';
                                                $html .= '<td class="border border-gray-300 dark:border-gray-700 px-3 py-2 text-green-600 dark:text-green-400">
                                                            Terkirim ✅
                                                        </td>';
                                            } else {
                                                $html .= '<td class="border border-gray-300 dark:border-gray-700 px-3 py-2 text-red-500">-</td>';
                                                $html .= '<td class="border border-gray-300 dark:border-gray-700 px-3 py-2 text-red-600 dark:text-red-400">
                                                            Belum Upload ❌
                                                        </td>';
                                            }

                                            $html .= '</tr>';
                                        }

                                        $html .= '</tbody></table>';

                                        return new \Illuminate\Support\HtmlString($html);
                                    }),
                            ])
                            ->columns(1),
                        Forms\Components\Radio::make('status')
                            ->required()
                            ->options([
                                'pending' => 'Belum diperiksa',
                                'verified' => 'Rekomendasi',
                                'failed' => 'Tidak disetujui',
                            ])
                            ->inline()
                            ->inlineLabel(false)
                            ->columnSpanFull(),

                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('paymentReview.payment.userCertification.certificationList.assessmentSchedule.certificationScheme.judul_skema')
                    ->label('Judul Skema')
                    ->words(4)
                    ->sortable(),
                Tables\Columns\TextColumn::make('paymentReview.payment.userCertification.user.name')
                    ->label('Nama Asesi')
                    ->badge()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('user.name')
                //     ->label('Asesor Status')
                //     ->badge()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('paymentReview.payment.userCertification.certificationList.assessmentSchedule.users.name')
                    ->label('Assesor')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'pending' => 'warning',
                        'verified' => 'success',
                        'failed' => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn(string $state): string => match (strtolower($state)) {
                        'pending' => 'Belum Diperiksa',
                        'verified' => 'Rekomendasi',
                        'failed' => 'Tidak Disetujui',
                        default => ucfirst($state),
                    })
                    // ->suffix(fn($record): string => $record->user ? ' oleh ' . $record->user->name : '')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn($record) => AplOneResource::getUrl('edit', [
                        'record' => Crypt::encrypt($record->id),
                    ])),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListAplOnes::route('/'),
            'create' => Pages\CreateAplOne::route('/create'),
            'edit' => Pages\EditAplOne::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'APL-01';
    }

    public static function getModelLabel(): string
    {
        return 'APL-01';
    }

    public static function getPluralModelLabel(): string
    {
        return 'APL-01';
    }

    public function getTitle(): string
    {
        return 'APL-01';
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        // Super admin bisa lihat semua
        if ($user->hasRole('super_admin')) {
            return parent::getEloquentQuery();
        }

        // Asesi: tampilkan AplOne yang user_id-nya sama dengan Auth::user()->id
        if ($user->hasRole('asesi')) {
            return parent::getEloquentQuery()
                ->whereHas('paymentReview.payment.userCertification', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                });
        }

        // Asesor: tampilkan AplOne yang terkait dengan schedule yang user-nya adalah asesor
        if ($user->hasRole('asesor')) {
            return parent::getEloquentQuery()
                ->whereHas('paymentReview.payment.userCertification.certificationList.assessmentSchedule.users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                });
        }

        // Default fallback: tampilkan kosong
        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }
}
