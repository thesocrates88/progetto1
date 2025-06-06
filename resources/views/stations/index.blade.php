<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Stazioni della linea ferroviaria
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow rounded">

            <!-- Messaggio di successo -->
            @if(session('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Pulsante aggiungi -->
            <!-- nascondi crea per amministrazione -->
            @if (Auth::user()->role != 'backoffice_amministrazione')

                <div class="mb-4">
                    <a href="{{ route('stations.create') }}"
                    class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        + Aggiungi una nuova stazione
                    </a>
                </div>
            @endif

            @if ($stations->isEmpty())
                <p class="text-gray-500">Nessuna stazione inserita.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border text-left">ID</th>
                                <th class="px-4 py-2 border text-left">Nome</th>
                                <th class="px-4 py-2 border text-left">Km</th>
                                <th class="px-4 py-2 border text-left">Ordine</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stations as $station)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border">{{ $station->id }}</td>
                                    <td class="px-4 py-2 border">{{ $station->name }}</td>
                                    <td class="px-4 py-2 border">{{ number_format($station->kilometer, 3) }} km</td>
                                    <td class="px-4 py-2 border">{{ $station->order }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
