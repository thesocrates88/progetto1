<x-app-layout>
    <div class="max-w-3xl mx-auto mt-12 px-4">
        <h2 class="text-2xl font-bold mb-6">Dettaglio richiesta treno straordinario</h2>

        <div class="bg-white shadow rounded-lg p-6 space-y-4">

            <div>
                <strong>Data:</strong>
                {{ \Carbon\Carbon::parse($requestedTrain->date)->format('d/m/Y') }}
            </div>

            <div>
                <strong>Orario di partenza:</strong>
                {{ $requestedTrain->departure_time }}
            </div>

            <div>
                <strong>Stazione di partenza:</strong>
                {{ $requestedTrain->departureStation->name }}
            </div>

            <div>
                <strong>Stazione di arrivo:</strong>
                {{ $requestedTrain->arrivalStation->name }}
            </div>

            <div>
                <strong>Posti richiesti:</strong>
                {{ $requestedTrain->seats }}
            </div>

            <div>
                <strong>Stato:</strong>
                {{ $requestedTrain->status }}
            </div>

            <div>
                <strong>Messaggio da amministrazione:</strong><br>
                <span class="whitespace-pre-line text-gray-800">{{ $requestedTrain->admin_message }}</span>
            </div>

            <div>
                <strong>Messaggio da esercizio:</strong><br>
                <span class="whitespace-pre-line text-gray-800">{{ $requestedTrain->exercise_message }}</span>
            </div>

            <div>
                <strong>Ultima modifica:</strong>
                {{ \Carbon\Carbon::parse($requestedTrain->edited_at)->format('d/m/Y H:i') }}
            </div>

            <div>
                <strong>Richiesta inserita da:</strong>
                {{ $requestedTrain->creator->name ?? '—' }}
            </div>

        </div>

        <div class="mt-6">
            <a href="{{ route('requested-trains.index') }}" class="text-blue-600 hover:underline">
                &larr; Torna alla lista richieste
            </a>
        </div>
    </div>
</x-app-layout>
