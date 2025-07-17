<div>
    @php
    $units = $record
    ->aplOne
    ->paymentReview
    ->payment
    ->userCertification
    ->certificationList
    ->assessmentSchedule
    ->certificationScheme
    ->units ?? null;
    @endphp
    <div>
        @foreach ($units as $index => $unit)
        <table class="w-full border border-gray-600 text-sm mb-6" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th colspan="5"
                        class="border border-gray-700 dark:border-gray-600 px-3 py-1 text-left bg-gray-100 dark:bg-gray-800 dark:text-white">
                        <strong>Unit Kompetensi</strong>
                    </th>
                </tr>
                <tr>
                    <td class="border border-gray-700 dark:border-gray-600 px-3 py-1" width="10%"><strong>{{$index +
                            1}}</strong></td>
                    <td class="border border-gray-700 dark:border-gray-600 px-3 py-1 dark:text-white" colspan="4">
                        <strong>{{ $unit->nomor }}</strong><br>{{ $unit->name }}
                    </td>
                </tr>
                <tr class="bg-gray-200 dark:bg-gray-700 text-left dark:text-white">
                    <th colspan="2" class="border border-gray-700 dark:border-gray-600 px-3 py-1">Dapatkah Saya
                        ..............?</th>
                    <th class="border border-gray-700 dark:border-gray-600 px-3 py-1 text-center" width="5%">K</th>
                    <th class="border border-gray-700 dark:border-gray-600 px-3 py-1 text-center" width="5%">BK</th>
                    <th class="border border-gray-700 dark:border-gray-600 px-3 py-1">Bukti yang relevan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($unit->elements as $elementIndex => $element)

                <tr>
                    <td colspan="2" class="border border-gray-700 px-3 py-1">
                        <strong>Elemen {{ $elementIndex + 1 }} {{ $element->name }}</strong><br>
                        <div><strong>&bull; Kriteria Unjuk Kerja:</strong></div>

                        {!! $element->content !!}



                    </td>
                    <td colspan="1" class="border border-gray-700 px-3 py-1 text-center align-middle">
                        <input type="radio" name="state[{{ $index }}][{{ $elementIndex }}][status]"
                            wire:model="state.{{ $index }}.{{ $elementIndex }}.status" value="b"
                            class="form-radio text-green-600">
                    </td>
                    <td colspan="1" class="border border-gray-700 px-3 py-1 text-center align-middle">
                        <input type="radio" name="state[{{ $index }}][{{ $elementIndex }}][status]"
                            wire:model="state.{{ $index }}.{{ $elementIndex }}.status" value="bk"
                            class="form-radio text-red-600">
                    </td>
                    <td colspan="1" class="border border-gray-700 px-3 py-1">
                        <input type="text" name="state[{{ $index }}][{{ $elementIndex }}][bukti]"
                            wire:model="state.{{ $index }}.{{ $elementIndex }}.bukti"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-sm">
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
        @endforeach
        @if (Auth::user()->hasRole('asesi'))
        <x-filament::button wire:click="send" class="w-full bg-red-500 mt-3 text-white py-2 rounded">
            Kirimkan
        </x-filament::button>

        @endif
    </div>
</div>