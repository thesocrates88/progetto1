<x-app-layout>
    @if (session('success'))
        <div class="mb-4 p-4 rounded bg-green-100 text-green-800 border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 rounded bg-red-100 text-red-800 border border-red-300">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container mt-5">
        <h2 class="fw-bold mb-4">I miei biglietti</h2>

        @if ($tickets->isEmpty())
            <div class="alert alert-info">
                Non hai ancora acquistato nessun biglietto.
            </div>
        @else
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Data</th>
                        <th>Treno</th>
                        <th>Da</th>
                        <th>A</th>
                        <th>Carrozza</th>
                        <th>Posto</th>
                        <th>Costo</th>
                        <th>Acquistato il</th>
                        <th>Modifica prenotazione</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>{{ $ticket->train->date }}</td>
                            <td>#{{ $ticket->train->id }}</td>
                            <td>{{ $ticket->departureStation->name ?? '-' }}</td>
                            <td>{{ $ticket->arrivalStation->name ?? '-' }}</td>
                            <td>{{ $ticket->rollingStock->position ?? '-' }}</td>
                            <td>{{ $ticket->numero_posto }}</td>
                            <td>€ {{ number_format($ticket->costo, 2, ',', '') }}</td>
                            <td>{{ \Carbon\Carbon::parse($ticket->payed_at)->format('d/m/Y H:i') }}</td>
                            <td><a href="{{ route('ticket.edit', $ticket->id) }}" class="btn btn-sm btn-outline-primary">Modifica</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>

