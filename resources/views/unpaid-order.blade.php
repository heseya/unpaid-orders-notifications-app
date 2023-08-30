<x-mail::message>
    <x-mail::panel>
        # {{ $storeName }}
    </x-mail::panel>

    Zamówienie nr. {{ $orderNumber }} nadal czeka na Twoją płatność.

    Możesz dokonać jej klikanąć w link poniżej:

    <x-mail::button :url="$url">
        Opłać zamówienie ({{ $summary }} zł)
    </x-mail::button>

    Dziękujemy,
    {{ $storeName }}
</x-mail::message>
