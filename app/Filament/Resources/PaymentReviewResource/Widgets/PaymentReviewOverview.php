<?php

namespace App\Filament\Resources\PaymentReviewResource\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Resources\PaymentReviewResource\Pages\ListPaymentReviews;

class PaymentReviewOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected ?string $heading = 'Overview';

    protected ?string $description = 'Pembayaran Review Overview Widget';

    protected function getTablePage(): string
    {
        return ListPaymentReviews::class;
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pembayaran', $this->getPageTableQuery()->count())
                ->color('success'),
            Stat::make('Total Verified', $this->getPageTableQuery()->where('is_verified', true)->count()),
            Stat::make('Total Rejected', $this->getPageTableQuery()->where('is_verified', false)->count()),
            Stat::make('Total Proccessing', $this->getPageTableQuery()->where('is_verified', NULL)->count()),
        ];
    }
}
