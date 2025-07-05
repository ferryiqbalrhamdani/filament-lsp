<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PaymentReview;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PaymentReviewResource\Pages;
use App\Filament\Resources\PaymentReviewResource\RelationManagers;
use App\Filament\Resources\PaymentReviewResource\Widgets\PaymentReviewOverview;

class PaymentReviewResource extends Resource
{
    protected static ?string $model = PaymentReview::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Sertifikasi';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Placeholder::make('pemohon')
                                    ->content(fn(PaymentReview $record): string => $record->payment->userCertification->user->name ?? '-'),
                                Forms\Components\Placeholder::make('Judul Skema')
                                    ->content(fn(PaymentReview $record): string => $record->payment->userCertification->certificationList->assessmentSchedule->certificationScheme->judul_skema ?? '-'),
                                Forms\Components\Placeholder::make('Tanggal Mulai')
                                    ->label('Tanggal Mulai')
                                    ->content(function (PaymentReview $record) {
                                        $tanggal = $record->payment->userCertification->certificationList->assessmentSchedule->tgl_mulai ?? null;

                                        return $tanggal
                                            ? Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y')  // Output: 23 Oktober 2025
                                            : '-';
                                    }),

                                Forms\Components\Placeholder::make('Metode Pembayaran')
                                    ->content(fn(PaymentReview $record): string => $record->payment->paymentMethod->name ?? '-'),
                                Forms\Components\Placeholder::make('bank')
                                    ->label("BANK")
                                    ->content(fn(PaymentReview $record): string => $record->payment->digital_payment ?? '-'),
                                Forms\Components\Placeholder::make('Nomor Rekening')
                                    ->content(fn(PaymentReview $record): string => $record->payment->digital_payment_nomor ?? '-'),
                                Forms\Components\Placeholder::make('Catatan')
                                    ->content(fn(PaymentReview $record): string => $record->payment->keterangan ?? '-')
                                    ->columnSpanFull(),


                                Forms\Components\Placeholder::make('Jumlah Pembayaran')
                                    ->content(fn(PaymentReview $record): string => 'Rp ' . number_format($record->payment->amount, 2, ',', '.') ?? '-'),

                                // Forms\Components\FileUpload::make('payment.bukti_pembayaran'),

                            ])
                            ->columns(2),
                        Forms\Components\Section::make()
                            ->schema([
                                // Forms\Components\Toggle::make('is_verified')
                                //     ->label('Pembayaran Memenuhi Syarat')
                                //     ->required()
                                //     ->inline(false)
                                //     ->onColor('success')
                                //     ->offColor('danger')
                                //     ->onIcon('heroicon-m-bolt')
                                //     ->offIcon('heroicon-m-x-mark'),
                                Forms\Components\ToggleButtons::make('is_verified')
                                    ->label('Pembayaran Memenuhi Syarat?')
                                    ->boolean()
                                    ->required()
                                    ->grouped(),
                                Forms\Components\Placeholder::make('keterangan')
                                    ->content(fn(PaymentReview $record): string => $record->keterangan ?? '-'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Bukti Pembayaran')
                            ->collapsible()
                            ->schema([
                                Forms\Components\ViewField::make('payment.bukti_pembayaran')
                                    ->hiddenLabel()
                                    ->view('forms.components.gambar'),
                                Forms\Components\Placeholder::make('download')
                                    ->hiddenLabel()
                                    ->content(function (PaymentReview $record) {
                                        $url = $record->payment->bukti_pembayaran
                                            ? Storage::url($record->payment->bukti_pembayaran)
                                            : null;

                                        if ($url) {
                                            return new HtmlString(
                                                '<a href="' . $url . '" target="_blank"  >Lihat Bukti Pembayaran</a>'
                                            );
                                        }

                                        return;
                                    }),

                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),

            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment.userCertification.user.name')
                    ->label('Pemohon')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment.userCertification.certificationList.assessmentSchedule.certificationScheme.judul_skema')
                    ->label('Judul Skema')
                    ->searchable()
                    ->words(6)
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment.amount')
                    ->label('Jumlah Pembayaran')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_verified')
                    ->boolean(),
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
                Tables\Filters\Filter::make('is_verified')
                    ->form([
                        Forms\Components\Select::make('is_verified')
                            ->label('Status Pembayaran')
                            ->options([
                                'processing' => 'Processing',
                                'verified' => 'Verified',
                                'rejected' => 'Rejected',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['is_verified'] ?? null) {
                            'processing' => $query->whereNull('is_verified'),
                            'verified' => $query->where('is_verified', true),
                            'rejected' => $query->where('is_verified', false),
                            default => $query,
                        };
                    })
                    ->indicateUsing(function (array $data): array {
                        return match ($data['is_verified'] ?? null) {
                            'processing' => ['is_verified' => 'Status Pembayaran: Processing'],
                            'verified' => ['is_verified' => 'Status Pembayaran: Verified'],
                            'rejected' => ['is_verified' => 'Status Pembayaran: Rejected'],
                            default => [],
                        };
                    }),

            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListPaymentReviews::route('/'),
            'create' => Pages\CreatePaymentReview::route('/create'),
            'edit' => Pages\EditPaymentReview::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Review Pembayaran';
    }

    public static function getModelLabel(): string
    {
        return 'Review Pembayaran';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Review Pembayaran';
    }

    public function getTitle(): string
    {
        return 'Review Pembayaran';
    }

    public static function getNavigationBadge(): ?string
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = static::$model;

        return (string) $modelClass::where('is_verified', NULL)->count();
    }

    public static function getWidgets(): array
    {
        return [
            PaymentReviewOverview::class,
        ];
    }
}
