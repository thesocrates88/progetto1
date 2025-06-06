<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Convogli disponibili
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Messaggi di errore -->
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Messaggi di successo -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <!-- nascondi nuovo convoy amministrazione -->
            @if (Auth::user()->role != 'backoffice_amministrazione')
                <a href="{{ route('convoys.create') }}" class="inline-block mb-4 text-indigo-600 hover:underline">
                    + Crea nuovo convoglio
                </a>
            @endif

            @forelse ($convoys as $convoy)
                <div class="mb-8 bg-white p-4 shadow rounded">
                    <h3 class="text-lg font-bold mb-2">{{ $convoy->name }} (ID: {{ $convoy->id }})</h3>

                    @if ($convoy->rollingStocks->isEmpty())
                        <p class="text-gray-500"><em>Composizione vuota</em></p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-200 text-left">
                                        <th class="px-4 py-2 border">Posizione</th>
                                        <th class="px-4 py-2 border">Codice</th>
                                        <th class="px-4 py-2 border">Tipo</th>
                                        <th class="px-4 py-2 border">Posti</th>
                                        <th class="px-4 py-2 border">Serie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($convoy->rollingStocks->sortBy('pivot.position') as $rs)
                                        <tr>
                                            <td class="px-4 py-2 border">{{ $rs->pivot->position }}</td>
                                            <td class="px-4 py-2 border">{{ $rs->code }}</td>
                                            <td class="px-4 py-2 border">{{ ucfirst($rs->type) }}</td>
                                            <td class="px-4 py-2 border">{{ $rs->seats }}</td>
                                            <td class="px-4 py-2 border">{{ $rs->series ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    
                    <!-- nascondi azioni per amministrazione -->
                    @if (Auth::user()->role != 'backoffice_amministrazione')

                        <div class="mt-4 flex space-x-4">
                            <a href="{{ route('convoys.edit', $convoy->id) }}" class="text-blue-600 hover:underline">
                                ✏️ Modifica
                            </a>

                            <form action="{{ route('convoys.destroy', $convoy->id) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo convoglio?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">
                                    🗑 Elimina
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">Nessun convoglio creato.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
