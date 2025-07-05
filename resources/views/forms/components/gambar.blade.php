<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
    $bukti = $getRecord()->payment->bukti_pembayaran ?? null;
    @endphp

    @if ($bukti)
    <div class="space-y-2">
        <img src="{{ Storage::url($bukti) }}" alt="Bukti Pembayaran" class="w-48 rounded-md shadow border 
                       border-gray-400 dark:border-gray-600 
                       bg-white dark:bg-gray-800">

        {{-- <a href="{{ Storage::url($bukti) }}" target="_blank" download class="inline-flex items-center px-3 py-1.5 
                       bg-blue-600 hover:bg-blue-700 
                       dark:bg-blue-500 dark:hover:bg-blue-400 
                       text-white text-sm font-medium rounded transition">
            Download Bukti Pembayaran
        </a> --}}
    </div>
    @else
    <p class="text-gray-600 dark:text-gray-400 italic">
        Tidak ada bukti pembayaran
    </p>
    @endif
</x-dynamic-component>