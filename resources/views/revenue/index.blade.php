<x-app-layout>
    <div class="max-w-6xl mx-auto mt-8 px-4">
        <h1 class="text-2xl font-bold mb-6">Rendiconto treni</h1>

        <table class="min-w-full bg-white shadow rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-100">
                    <th class="text-left px-4 py-2">Train ID</th>
                    <th class="text-left px-4 py-2">Data</th>
                    <th class="text-left px-4 py-2">Partenza</th>
                    <th class="text-left px-4 py-2">Arrivo</th>
                    <th class="text-left px-4 py-2">Biglietti venduti</th>
                    <th class="text-left px-4 py-2">Posti disponibili</th>
                    <th class="text-left px-4 py-2">Occupazione</th>
                    <th class="text-left px-4 py-2">Incasso totale</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trains as $train)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $train['id'] }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($train['departure_time'])->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2">{{ $train['departure'] }}</td>
                        <td class="px-4 py-2">{{ $train['arrival'] }}</td>
                        <td class="px-4 py-2">{{ $train['tickets_sold'] }}</td>
                        <td class="px-4 py-2">{{ $train['total_seats'] }}</td>
                        <td class="px-4 py-2">{{ $train['total_seats'] * $train['tickets_sold'] /100 }} %</td>
                        <td class="px-4 py-2">€ {{ number_format($train['total_revenue'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
