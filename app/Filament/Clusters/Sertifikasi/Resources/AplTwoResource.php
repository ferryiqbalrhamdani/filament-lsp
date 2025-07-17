<?php

namespace App\Filament\Clusters\Sertifikasi\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\AplTwo;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Filament\Clusters\Sertifikasi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Sertifikasi\Resources\AplTwoResource\Pages;
use App\Filament\Clusters\Sertifikasi\Resources\AplTwoResource\RelationManagers;

class AplTwoResource extends Resource
{
    protected static ?string $model = AplTwo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Sertifikasi::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('pemohon')
                            ->content(fn($record): string => $record->aplOne->paymentReview->payment->userCertification->user->name ?? '-'),
                        Forms\Components\Placeholder::make('Judul Skema')
                            ->content(fn($record): string => $record->aplOne->paymentReview->payment->userCertification->certificationList->assessmentSchedule->certificationScheme->judul_skema ?? '-'),
                        Forms\Components\Placeholder::make('Tanggal Mulai')
                            ->label('Tanggal Mulai')
                            ->content(function ($record) {
                                $tanggal = $record->aplOne->paymentReview->payment->userCertification->certificationList->assessmentSchedule->tgl_mulai ?? null;

                                return $tanggal
                                    ? Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y')  // Output: 23 Oktober 2025
                                    : '-';
                            }),
                        Forms\Components\Placeholder::make('Assesor')
                            ->content(fn($record): string => $record->user->name ?? '-'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\ViewField::make('unit_kompetensi')
                            ->view('forms.components.apl-two')
                    ]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Radio::make('status')
                            ->required()
                            ->options([
                                'pending' => 'Pending',
                                'verified' => 'Verified',
                                'failed' => 'Failed',
                            ])
                            ->inline()
                            ->inlineLabel(false)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn() => !Auth::user()->hasRole('asesi')),

                // Forms\Components\Section::make()
                //     ->schema([
                //         Forms\Components\Placeholder::make('unit_kompetensi')
                //             ->label('Unit Kompetensi')
                //             ->content(function ($record) {
                //                 $units = $record
                //                     ?->aplOne
                //                     ?->paymentReview
                //                     ?->payment
                //                     ?->userCertification
                //                     ?->certificationList
                //                     ?->assessmentSchedule
                //                     ?->certificationScheme
                //                     ?->units ?? collect();

                //                 if ($units->isEmpty()) {
                //                     return 'Tidak ada unit kompetensi.';
                //                 }

                //                 $html = '';

                //                 foreach ($units as $index => $unit) {
                //                     $html .= '
                //         <table class="w-full border border-gray-600 text-sm mb-6" style="border-collapse: collapse;">
                //             <thead>
                //                     <tr>
                //                         <th colspan="5" class="border border-gray-700 dark:border-gray-600 px-3 py-1 text-left bg-gray-100 dark:bg-gray-800 dark:text-white">
                //                             <strong>Unit Kompetensi</strong>
                //                         </th>
                //                     </tr>
                //                     <tr>
                //                         <td class="border border-gray-700 dark:border-gray-600 px-3 py-1" width="10%"><strong>' . ($index + 1) . '</strong></td>
                //                         <td class="border border-gray-700 dark:border-gray-600 px-3 py-1 dark:text-white" colspan="4">
                //                             <strong>' . e($unit->nomor) . '</strong><br>' . e($unit->name) . '
                //                         </td>
                //                     </tr>
                //                     <tr class="bg-gray-200 dark:bg-gray-700 text-left dark:text-white">
                //                         <th colspan="2" class="border border-gray-700 dark:border-gray-600 px-3 py-1">Dapatkah Saya ..............?</th>
                //                         <th class="border border-gray-700 dark:border-gray-600 px-3 py-1 text-center" width="5%">K</th>
                //                         <th class="border border-gray-700 dark:border-gray-600 px-3 py-1 text-center" width="5%">BK</th>
                //                         <th class="border border-gray-700 dark:border-gray-600 px-3 py-1">Bukti yang relevan</th>
                //                     </tr>
                //                 </thead>
                //             <tbody>
                //     ';
                //                     foreach ($unit->elements as $elementIndex => $element) {
                //                         // dd($element, $elementIndex);
                //                         $html .= '
                //             <tr>
                //                 <td colspan="5" class="border border-gray-700 px-3 py-1">
                //                     <strong>Elemen ' . ($elementIndex + 1) . ' : ' . e($element->name) . '</strong><br>
                //                     <div><strong>&bull; Kriteria Unjuk Kerja:</strong></div>

                //         ' . $element->content;


                //                         $html .= '</td></tr>';

                //                         // Baris kosong K, BK, Bukti
                //                         $html .= '
                //             <tr>
                //                 <td colspan="2" class="border border-gray-700 px-3 py-2"></td>
                //                 <td class="border border-gray-700 px-3 py-2 text-center">☐</td>
                //                 <td class="border border-gray-700 px-3 py-2 text-center">☐</td>
                //                 <td class="border border-gray-700 px-3 py-2"></td>
                //             </tr>
                //         ';
                //                     }

                //                     $html .= '</tbody></table>';
                //                 }

                //                 return new \Illuminate\Support\HtmlString($html);
                //             }),
                //     ]),




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('aplOne.paymentReview.payment.userCertification.certificationList.assessmentSchedule.certificationScheme.judul_skema')
                    ->label('Judul Skema')
                    ->words(4)
                    ->sortable(),
                Tables\Columns\TextColumn::make('aplOne.paymentReview.payment.userCertification.user.name')
                    ->label('Nama Asesi')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_reg')
                    ->searchable(),
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
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn($record) => AplTwoResource::getUrl('edit', [
                        'record' => Crypt::encrypt($record->id),
                    ])),
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
            'index' => Pages\ListAplTwos::route('/'),
            'create' => Pages\CreateAplTwo::route('/create'),
            'edit' => Pages\EditAplTwo::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'APL-02';
    }

    public static function getModelLabel(): string
    {
        return 'APL-02';
    }

    public static function getPluralModelLabel(): string
    {
        return 'APL-02';
    }

    public function getTitle(): string
    {
        return 'APL-02';
    }
}
