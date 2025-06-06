<x-app-layout>
    <div class="max-w-3xl mx-auto mt-12 px-4">
        <h2 class="text-2xl font-bold mb-6">Conferma e paga i tuoi biglietti</h2>

        <section class="bg-white shadow rounded-lg p-6 mb-6">
            <p><strong>Treno:</strong> #{{ $train->id }} del {{ $train->date }}</p>
            <p><strong>Tratta:</strong> {{ $train->departureStation->name }} → {{ $train->arrivalStation->name }}</p>
            <p><strong>Subtratta selezionata:</strong> {{ $from }} → {{ $to }}</p>
            <p><strong>Numero posti:</strong> {{ $posti->count() }}</p>
            <p><strong>Costo per posto:</strong> €{{ number_format($cost, 2, ',', '') }}</p>
            <p><strong>Totale:</strong> €{{ number_format($total, 2, ',', '') }}</p>

            <ul class="mt-3 text-sm text-gray-600 list-disc list-inside">
                @foreach ($posti as $p)
                    <li>Carrozza #{{ $p['rolling_stock_id'] }}, posto {{ $p['numero_posto'] }}</li>
                @endforeach
            </ul>
        </section>

        <form method="POST" action="{{ route('ticket.purchase', ['train' => $train->id]) }}">
            @csrf

            <input type="hidden" name="from_station_id" value="{{ $from }}">
            <input type="hidden" name="to_station_id" value="{{ $to }}">
            <input type="hidden" name="date" value="{{ $date }}">

            @foreach ($posti as $p)
                <input type="hidden" name="posti[{{ $p['rolling_stock_id'] }}][]" value="{{ $p['numero_posto'] }}">
            @endforeach

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="name" class="block font-medium">Nome</label>
                    <input type="text" id="name" name="name" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label for="surname" class="block font-medium">Cognome</label>
                    <input type="text" id="surname" name="surname" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="card_number" class="block font-medium">Numero carta</label>
                <input type="text" id="card_number" name="card_number" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="expiry" class="block font-medium">Scadenza</label>
                    <input type="text" id="expiry" name="expiry" class="w-full border rounded px-3 py-2" placeholder="MM/YY" required>
                </div>
                <div>
                    <label for="cvv" class="block font-medium">CVV</label>
                    <input type="text" id="cvv" name="cvv" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>

            <x-primary-button class="w-full justify-center">
                Conferma e Acquista
            </x-primary-button>
        </form>
    </div>
</x-app-layout>
