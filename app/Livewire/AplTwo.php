<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\AplTwo as ModelsAplTwo;
use Filament\Notifications\Notification;

class AplTwo extends Component
{
    public $recordId;
    public $record;

    public array $state = []; // <- tampung data checkbox + input bukti

    public function mount($recordId)
    {
        $this->recordId = $recordId;
        $this->record = ModelsAplTwo::findOrFail($recordId); // Ambil record-nya
    }

    public function send()
    {
        $data = $this->record;
        $untis = $data->aplOne
            ->paymentReview
            ->payment
            ->userCertification
            ->certificationList
            ->assessmentSchedule
            ->certificationScheme
            ->units;
        dd($this->state, $data, $untis, Auth::user()->roles->pluck('name'));
        Notification::make()
            ->success()
            ->title('Berhasil')
            ->body('Data berhasil disimpan ')
            ->send();
    }

    public function render()
    {
        return view('livewire.apl-two');
    }
}
