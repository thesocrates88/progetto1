<x-app-layout>
    <div class="max-w-4xl mx-auto mt-12 px-4">
        <!-- FORM DI RICERCA -->
        <section class="bg-white shadow rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">Cerca treni disponibili</h2>

            <form method="POST" action="{{ route('ticket.search') }}">
                @csrf

                <!-- Stazione di partenza -->
                <div class="mb-4">
                    <label for="departure_station_id" class="block font-medium">Stazione di partenza</label>
                    <select name="departure_station_id" id="departure_station_id" class="w-full border rounded px-3 py-2">
                        <option value="">-- Seleziona --</option>
                        @foreach ($stations as $station)
                            <option value="{{ $station->id }}" @selected(old('departure_station_id', $selectedDeparture ?? '') == $station->id)>
                                {{ $station->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stazione di arrivo -->
                <div class="mb-4">
                    <label for="arrival_station_id" class="block font-medium">Stazione di arrivo</label>
                    <select name="arrival_station_id" id="arrival_station_id" class="w-full border rounded px-3 py-2">
                        <option value="">-- Seleziona --</option>
                        @foreach ($stations as $station)
                            <option value="{{ $station->id }}" @selected(old('arrival_station_id', $selectedArrival ?? '') == $station->id)>
                                {{ $station->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Data -->
                <div class="mb-4">
                    <label for="date" class="block font-medium">Data</label>
                    <input type="date" name="date" id="date" class="w-full border rounded px-3 py-2"
                           value="{{ old('date', $selectedDate ?? '') }}">
                </div>

                <x-primary-button>Cerca treni</x-primary-button>
            </form>
        </section>

        <!-- RISULTATI -->
        @isset($trains)
            <section class="bg-white shadow rounded-lg p-6">
                <h3 class="text-xl font-semibold mb-4">Treni trovati</h3>

                @if ($trains->isEmpty())
                    <p class="text-gray-600">Nessun treno disponibile per la tratta e data selezionate.</p>
                @else
                    <ul class="space-y-3">
                        @foreach ($trains as $train)
                            <li class="border rounded px-4 py-3">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <strong>Treno #{{ $train->id }}</strong> | {{ $train->date }}<br>
                                       
                                        @php
                                            $subPartenza = $train->subtratte->firstWhere('from_station_id', $selectedDeparture);
                                            $subArrivo = $train->subtratte->firstWhere('to_station_id', $selectedArrival);
                                        @endphp
                                        <strong>Tratta:</strong>
                                        <br>
                                        {{ $stations->firstWhere('id', $selectedDeparture)?->name }} {{ $subPartenza ? \Carbon\Carbon::parse($subPartenza->departure_time)->format('H:i') : '—' }} →
                                        {{ $stations->firstWhere('id', $selectedArrival)?->name }} {{ $subArrivo ? \Carbon\Carbon::parse($subArrivo->arrival_time)->format('H:i') : '—' }}
                                        <br>
                                        <strong>Origine: </strong>{{ $train->departureStation->name }} → <strong>Destinazione: </strong>{{ $train->arrivalStation->name }}<br>

                                    </div>
                                    <div class="text-right flex flex-col items-end">
                                        <a href="{{ route('ticket.show', [
                                                            'train' => $train->id,
                                                            'from_station_id' => $selectedDeparture,
                                                            'to_station_id' => $selectedArrival,
                                                            'date' => $selectedDate,
                                                        ]) }}" class="text-blue-600 hover:underline">
                                            Prenota
                                        </a>
                                        <p class="text-gray-700 mt-1 text-sm">
                                            € {{ number_format($train->sub_cost, 2, ',', '') }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        @endisset
    </div>
</x-app-layout>
