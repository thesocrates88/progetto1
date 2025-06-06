<x-app-layout>
    <div class="max-w-6xl mx-auto mt-12 px-4">
        <h2 class="text-2xl font-bold mb-6">Richieste Treni Straordinari</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (auth()->user()->role === 'backoffice_amministrazione')
            <div class="mb-4">
                <a href="{{ route('requested-trains.create') }}" class="btn btn-primary">Nuova richiesta</a>
            </div>
        @endif

        <table class="w-full table-auto border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">Data</th>
                    <th class="border px-4 py-2">Partenza</th>
                    <th class="border px-4 py-2">Arrivo</th>
                    <th class="border px-4 py-2">Orario</th>
                    <th class="border px-4 py-2">Posti</th>
                    <th class="border px-4 py-2">Stato</th>
                    <th class="border px-4 py-2">Ultimo cambiamento</th>
                    <th class="border px-4 py-2">Azioni</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $req)
                    <tr>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($req->date)->format('d/m/Y') }}</td>
                        <td class="border px-4 py-2">{{ $req->departureStation->name }}</td>
                        <td class="border px-4 py-2">{{ $req->arrivalStation->name }}</td>
                        <td class="border px-4 py-2">{{ $req->departure_time }}</td>
                        <td class="border px-4 py-2">{{ $req->seats }}</td>
                        <td class="border px-4 py-2">{{ $req->status }}</td>
                        <td class="border px-4 py-2">{{ $req->updated_at }}</td>
                        <td class="border px-4 py-2 text-center">
                            <a href="{{ route('requested-trains.show', $req) }}" class="text-blue-600 hover:underline">Vedi</a>
                            <a href="{{ route('requested-trains.edit', $req) }}" class="text-blue-600 hover:underline">Modifica</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
