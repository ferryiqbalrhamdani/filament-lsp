{{-- <x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
    $units = $getRecord()
    ->aplOne
    ->paymentReview
    ->payment
    ->userCertification
    ->certificationList
    ->assessmentSchedule
    ->certificationScheme
    ->units ?? null;
    @endphp
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
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
                    <td colspan="5" class="border border-gray-700 px-3 py-1">
                        <strong>Elemen {{ $elementIndex + 1 }} {{ $element->name }}</strong><br>
                        <div><strong>&bull; Kriteria Unjuk Kerja:</strong></div>

                        {!! $element->content !!}



                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="border border-gray-700 dark:border-gray-600 px-3 py-2"></td>

                    <!-- Checkbox K -->
                    <td class="border border-gray-700 dark:border-gray-600 px-3 py-2 text-center">
                        <input type="checkbox" name="state[{{ $index }}][{{ $elementIndex }}][k]"
                            wire:model="state.{{ $index }}.{{ $elementIndex }}.k" class="form-checkbox text-green-600">
                    </td>

                    <!-- Checkbox BK -->
                    <td class="border border-gray-700 dark:border-gray-600 px-3 py-2 text-center">
                        <input type="checkbox" name="state[{{ $index }}][{{ $elementIndex }}][bk]"
                            wire:model="state.{{ $index }}.{{ $elementIndex }}.bk" class="form-checkbox text-red-600">
                    </td>

                    <!-- Bukti yang relevan -->
                    <td class="border border-gray-700 dark:border-gray-600 px-3 py-2">
                        <input type="text" name="state[{{ $index }}][{{ $elementIndex }}][bukti]"
                            wire:model="state.{{ $index }}.{{ $elementIndex }}.bukti"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-sm">
                    </td>
                </tr>


                @endforeach
            </tbody>
        </table>
        @endforeach
        <x-filament::button type="submit" class="w-full bg-red-500 mt-3 text-white py-2 rounded">
            Kirimkan
        </x-filament::button>
    </div>
</x-dynamic-component> --}}
{{-- {{ dd($getRecord()->id) }} --}}
@livewire('apl-two', ['recordId' => $getRecord()->id])