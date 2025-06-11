<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pagamento non riuscito
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="mb-4 text-red-600 font-semibold">Purtroppo il pagamento non è andato a buon fine.</p>
                <p class="mb-6">Nessun biglietto è stato emesso. Puoi riprovare dalla sezione "I miei biglietti".</p>
                <a href="{{ route('ticket.index') }}" class="text-blue-600 underline">Torna ai tuoi biglietti</a>
            </div>
        </div>
    </div>
</x-app-layout>
