<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
    $bukti = $getRecord()->paymentReview->payment->userCertification->documents ?? null;
    @endphp

    {{-- {{ dd($getRecord()->paymentReview->payment->userCertification) }} --}}
    @foreach ($bukti as $item)

    {{-- <div class="space-y-2">
        <img src="{{ Storage::url( $item->file_path) }}" alt="Bukti Pembayaran" class="w-48 rounded-md shadow border 
                       border-gray-400 dark:border-gray-600 
                       bg-white dark:bg-gray-800">
    </div> --}}

    {{ $item->file_path }}
    @endforeach

</x-dynamic-component>