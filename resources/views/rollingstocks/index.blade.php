<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Materiale Rotabile
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow rounded">

            <!-- Pulsante nuovo veicolo -->
            <!-- nascondi crea per amministrazione -->
            @if (Auth::user()->role != 'backoffice_amministrazione')

                <div class="mb-4">
                    <a href="{{ route('rolling-stock.create') }}"
                    class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Inserisci nuovo veicolo
                    </a>
                </div>
            @endif

            @if ($items->isEmpty())
                <p class="text-gray-500">Nessun materiale rotabile inserito.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border text-left">ID</th>
                                <th class="px-4 py-2 border text-left">Codice</th>
                                <th class="px-4 py-2 border text-left">Tipo</th>
                                <th class="px-4 py-2 border text-left">Posti a sedere</th>
                                <th class="px-4 py-2 border text-left">Serie</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border">{{ $item->id }}</td>
                                    <td class="px-4 py-2 border">{{ $item->code }}</td>
                                    <td class="px-4 py-2 border">{{ ucfirst($item->type) }}</td>
                                    <td class="px-4 py-2 border">{{ $item->seats }}</td>
                                    <td class="px-4 py-2 border">{{ $item->series ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
