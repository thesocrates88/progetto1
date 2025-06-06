<x-app-layout>
    <!-- info treno -->
    <div class="max-w-3xl mx-auto mt-12 px-4">
        <section class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Dettagli Treno #{{ $train->id }}</h2>

            <ul class="text-gray-700 leading-relaxed">
                <li><strong>Data:</strong> {{ $train->date }}</li>
                <li><strong>Partenza:</strong> {{ $train->departure_time }}</li>
                <li><strong>Arrivo:</strong> {{ $train->arrival_time }}</li>
                <li><strong>Percorso:</strong>
                    {{ $train->departureStation->name ?? '—' }} ->
                    {{ $train->arrivalStation->name ?? '—' }}
                </li>
                <li><strong>Convoglio:</strong> <a href="{{ route('convoys.public.show', $train->convoy->id) }}" class="text-blue-600 underline hover:text-blue-800">{{ $train->convoy->name }}</a></li>
                                <td class="px-4 py-2 border text-center"></td>


            </ul>
        </section>
    </div>


    <!-- info tratte -->

    <section class="bg-white shadow rounded-lg p-6 mt-10">
        <h2 class="text-xl font-semibold mb-4">Fermate lungo il percorso</h2>

        @if ($train->subtratte->isEmpty())
            <p class="text-gray-600">Nessuna sub-tratta associata a questo treno.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">Orario Partenza</th>
                            <th class="px-4 py-2 border">Da</th>
                            <th class="px-4 py-2 border">Orario Arrivo</th>
                            <th class="px-4 py-2 border">A</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($train->subtratte as $subtratta)
                            <tr class="text-gray-700 {{ $loop->even ? 'bg-gray-50' : '' }}">
                                <td class="px-4 py-2 border text-center">{{ \Carbon\Carbon::parse($subtratta->departure_time)->format('H:i') }}</td>
                                <td class="px-4 py-2 border text-center">{{ $subtratta->fromStation->name ?? '—' }}</td>
                                <td class="px-4 py-2 border text-center">{{ \Carbon\Carbon::parse($subtratta->arrival_time)->format('H:i') }}</td>
                                <td class="px-4 py-2 border text-center">{{ $subtratta->toStation->name ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

</x-app-layout>
