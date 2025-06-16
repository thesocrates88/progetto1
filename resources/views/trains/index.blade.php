<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Elenco treni
        </h2>
        @if (Auth::user()->role != 'backoffice_amministrazione')
            <div class="flex justify-end mb-4">
                <a href="{{ route('trains.create') }}"
                class="inline-block bg-blue-600 text-white font-semibold px-4 py-2 rounded hover:bg-blue-700">
                    + Crea nuovo treno
                </a>
            </div>
        @endif
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('success') }}
                </div>
            @endif

            <!-- nascondi tabella ad amministrazione -->
            @if (Auth::user()->role != 'backoffice_amministrazione')

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">Codice Treno</th>
                                <th class="px-4 py-2">Convoglio</th>
                                <th class="px-4 py-2">Partenza</th>
                                <th class="px-4 py-2">Arrivo</th>
                                <th class="px-4 py-2">Data</th>
                                <th class="px-4 py-2">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($trains as $train)
                                <tr>
                                    <td class="px-4 py-2">{{ $train->id }}</td>
                                    <td class="px-4 py-2">{{ $train->name }}</td>
                                    <td class="px-4 py-2">{{ $train->convoy->name ?? 'N/D' }}</td>
                                    <td class="px-4 py-2">
                                        {{ $train->departureStation->name ?? 'N/D' }}<br>
                                        {{ $train->departure_time }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ $train->arrivalStation->name ?? 'N/D' }}<br>
                                        {{ $train->arrival_time }}
                                    </td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($train->date)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 space-x-2">

                                        <a href="{{ route('trains.edit', $train->id) }}" class="text-blue-600 hover:underline">Modifica</a>
                                        <form action="{{ route('trains.destroy', $train->id) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Sei sicuro di voler eliminare questo treno?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Elimina</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- tabella per amministrazione -->
            @if (Auth::user()->role === 'backoffice_amministrazione')
                <div class="mt-8 bg-white p-6 rounded shadow">
                    <h2 class="text-xl font-semibold mb-4">Redditività dei treni</h2>
                    <table class="table-auto w-full border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-4 py-2 text-left">ID Treno</th>
                                <th class="border px-4 py-2 text-left">Codice Treno</th>
                                <th class="border px-4 py-2 text-left">Stazione Partenza</th>
                                <th class="border px-4 py-2 text-left">Stazione Arrivo</th>
                                <th class="border px-4 py-2 text-left">Data</th>
                                <th class="border px-4 py-2 text-left">Biglietti venduti</th>
                                <th class="border px-4 py-2 text-left">% Occupazione</th>
                                <th class="border px-4 py-2 text-left">Incasso totale</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trains as $train)
                                @php
                                    $sold = $train->tickets->count();
                                    $totalSeats = $train->convoy->rollingStocks->sum('seats');
                                    $occupancy = $totalSeats > 0 ? number_format(($sold / $totalSeats) * 100, 2, ',', '') : '0,00';
                                    $income = $train->tickets->sum('costo');
                                @endphp
                                <tr>
                                    <td class="border px-4 py-2">{{ $train->id }}</td>
                                    <td class="border px-4 py-2">{{ $train->name }}</td>
                                    <td class="border px-4 py-2">{{ $train->departureStation->name }}</td>
                                    <td class="border px-4 py-2">{{ $train->arrivalStation->name }}</td>
                                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($train->date)->format('d/m/Y') }}</td>
                                    <td class="border px-4 py-2">{{ $sold }} / {{ $totalSeats }}</td>
                                    <td class="border px-4 py-2">{{ $occupancy }}%</td>
                                    <td class="border px-4 py-2">€ {{ number_format($income, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
