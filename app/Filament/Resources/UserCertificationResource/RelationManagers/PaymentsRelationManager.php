<?php

namespace App\Filament\Resources\UserCertificationResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use App\Models\UserCertification;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payment';

    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('payment_method_id')
            ->columns([
                Tables\Columns\TextColumn::make('paymentMethod.name'),
                Tables\Columns\TextColumn::make('digital_payment')
                    ->label('Bank')
                    ->default('-'),
                Tables\Columns\TextColumn::make('digital_payment_nomor')
                    ->label('Nomor Rekening')
                    ->default('-'),
                Tables\Columns\ImageColumn::make('bukti_pembayaran'),
                Tables\Columns\TextColumn::make('userCertification.certificationList.assessmentSchedule.certificationScheme.harga')
                    ->numeric()
                    ->label('Harga')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('userCertification.status')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        // 'processing' => 'warning',
                        'verified' => 'success',
                        'payment_failed' => 'danger',
                        default => 'warning',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Pembayaran')
                    ->slideOver()
                    ->modalHeading('Pembayaran')
                    ->form([
                        Forms\Components\Select::make('payment_method_id')
                            ->relationship(name: 'paymentMethod', titleAttribute: 'name')
                            ->label('Metode Pembayaran')
                            ->reactive()
                            ->required(),
                        Forms\Components\TextInput::make('amout')
                            ->default(fn() => $this->getOwnerRecord()->certificationList->assessmentSchedule->certificationScheme->harga)
                            ->mask(RawJs::make('$money($input)'))
                            ->readOnly()
                            ->stripCharacters(',')
                            ->numeric()
                            ->prefix('Rp')
                            ->label('Jumlah Pembayaran')
                            ->required(),
                        Forms\Components\Select::make('digital_payment')
                            ->label('Bank')
                            ->options(function (callable $get) {
                                $paymentMethodId = $get('payment_method_id');
                                if (!$paymentMethodId) {
                                    return [];
                                }

                                $paymentMethod = \App\Models\PaymentMethod::with('digitalPayments')->find($paymentMethodId);

                                return $paymentMethod
                                    ? $paymentMethod->digitalPayments->pluck('name', 'id')->toArray()
                                    : [];
                            })
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $digitalPayment = \App\Models\DigitalPayment::find($state);
                                $set('digital_payment_nomor', $digitalPayment?->nomor);
                            })
                            ->visible(function (callable $get) {
                                $paymentMethodId = $get('payment_method_id');
                                if (!$paymentMethodId) {
                                    return false;
                                }

                                $paymentMethod = \App\Models\PaymentMethod::withCount('digitalPayments')->find($paymentMethodId);
                                return $paymentMethod && $paymentMethod->digital_payments_count > 0;
                            })
                            ->required(),

                        Forms\Components\TextInput::make('digital_payment_nomor')
                            ->label('Nomor Rekening')
                            ->readOnly()
                            ->visible(function (callable $get) {
                                $paymentMethodId = $get('payment_method_id');
                                if (!$paymentMethodId) {
                                    return false;
                                }

                                $paymentMethod = \App\Models\PaymentMethod::withCount('digitalPayments')->find($paymentMethodId);
                                return $paymentMethod && $paymentMethod->digital_payments_count > 0;
                            }),


                        Forms\Components\FileUpload::make('file')
                            ->label('Bukti Pembayaran')
                            ->required()
                            ->columnSpan('full')
                            ->directory('certification-payment')
                            ->preserveFilenames()
                            ->visible(function (callable $get) {
                                $paymentMethodId = $get('payment_method_id');
                                if (!$paymentMethodId) {
                                    return false;
                                }

                                $paymentMethod = \App\Models\PaymentMethod::withCount('digitalPayments')->find($paymentMethodId);
                                return $paymentMethod && $paymentMethod->digital_payments_count > 0;
                            }),
                        Forms\Components\TextArea::make('keterangan')
                            ->maxLength(65535)
                            ->rows(7)
                            ->columnSpan('full'),
                    ])
                    ->action(function (array $data) {

                        $data['keterangan'] = strtoupper($data['keterangan'] ?? '');



                        // Ambil model DigitalPayment berdasarkan ID
                        $digitalPayment = \App\Models\DigitalPayment::find($data['digital_payment'] ?? null);

                        if ($digitalPayment) {
                            // Simpan nama digital payment ke field baru, misalnya: 'digital_payment_name'
                            $data['digital_payment'] = $digitalPayment->name;
                        }

                        // Debug
                        // dd($data, $this->getOwnerRecord());

                        $payment = $this->getOwnerRecord()->payment()->create([
                            'user_certification_id' => $this->getOwnerRecord()->id,
                            'payment_method_id' => $data['payment_method_id'],
                            'amount' => $data['amout'],
                            'bukti_pembayaran' => $data['file'] ?? null,
                            'keterangan' => $data['keterangan'] ?? null,
                            'digital_payment' => $data['digital_payment'] ?? null,
                            'digital_payment_nomor' => $data['digital_payment_nomor'] ?? null,
                        ]);

                        // Tambahkan data ke PaymentReview
                        $payment->review()->create([
                            'keterangan' => 'Menunggu verifikasi pembayaran',
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Pembayaran Berhasil')
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('refund')
                    ->hidden(fn() => $this->getOwnerRecord()->status == 'verified'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        // // Ambil record payment terkait
        $userCertification = $this->getOwnerRecord();

        // if ($payment) {
        //     return true; // Belum ada payment, bisa membuat
        // }

        // // Ambil review dari payment
        // $review = $payment->review;

        // // Jika status review adalah 'verified', tidak boleh membuat payment lagi
        // if ($review && $review->is_verified == true) {
        //     return true; // Form readonly
        // }

        if (!$userCertification->payment) {
            return false;
        }



        return true; // Bisa isi lagi kalau belum ada review atau review gagal
    }
}
