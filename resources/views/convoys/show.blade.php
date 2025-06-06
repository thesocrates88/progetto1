<x-app-layout>
    <div class="max-w-5xl mx-auto mt-12 px-4">

        <!-- Intestazione convoglio -->
        <section class="bg-white shadow rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">Convoglio {{ $convoy->name }}</h2>
            <ul class="text-gray-700 leading-relaxed">
            </ul>
        </section>

        <!-- Tabella rolling stock -->
        @if ($convoy->rollingStocks && $convoy->rollingStocks->isNotEmpty())
            <section class="bg-white shadow rounded-lg p-6">
                <h3 class="text-xl font-semibold mb-4">Composizione del convoglio</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border">Sigla</th>
                                <th class="px-4 py-2 border">Serie</th>
                                <th class="px-4 py-2 border">Tipo</th>
                                <th class="px-4 py-2 border">Posti a sedere</th>
                                <th class="px-4 py-2 border">Posizione</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($convoy->rollingStocks as $stock)
                                <tr class="text-gray-700 {{ $loop->even ? 'bg-gray-50' : '' }}">
                                    <td class="px-4 py-2 border text-center">{{ $stock->code }}</td>
                                    <td class="px-4 py-2 border text-center">{{ $stock->series }}</td>
                                    <td class="px-4 py-2 border text-center">{{ $stock->type }}</td>
                                    <td class="px-4 py-2 border text-center">{{ $stock->seats }}</td>
                                    <td class="px-4 py-2 border text-center">{{ $stock->pivot->position ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            <section class="bg-white shadow rounded-lg p-6">
                <p class="text-gray-600">Nessun materiale rotabile associato a questo convoglio.</p>
            </section>
        @endif

    </div>
</x-app-layout>
