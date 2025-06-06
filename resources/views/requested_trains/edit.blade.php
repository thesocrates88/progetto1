<x-app-layout>
    <div class="max-w-4xl mx-auto mt-12 px-4">
        <h2 class="text-2xl font-bold mb-6">Modifica richiesta</h2>

        <!-- form edit per backoffice esercizio -->
        @if (Auth::user()->role === 'backoffice_esercizio')                    
            <form method="POST" action="{{ route('requested-trains.update', $requestedTrain) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block font-medium">Status</label>
                    <select name="status" class="form-select w-full" required>
                        <option value="in attesa del backoffice esercizio" @selected($requestedTrain->status === 'in attesa del backoffice esercizio')>
                            In attesa
                        </option>
                        <option value="treno creato" @selected($requestedTrain->status === 'treno creato')>
                            Treno creato
                        </option>
                        <option value="richiesta rifiutata" @selected($requestedTrain->status === 'richiesta rifiutata')>
                            Rifiutata
                        </option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="exercise_message" class="block font-medium">Messaggio da esercizio</label>
                    <textarea name="exercise_message" id="exercise_message" class="form-textarea w-full">
                        {{ old('exercise_message', $requestedTrain->exercise_message) }}
                    </textarea>
                </div>

                <x-primary-button>Aggiorna richiesta</x-primary-button>
            </form>
        @else
            <form method="POST" action="{{ route('requested-trains.update', $requestedTrain) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="date" class="block font-medium">Data</label>
                    <input type="date" name="date" id="date" class="form-input w-full"
                        value="{{ old('date', $requestedTrain->date) }}" required>
                    <x-input-error :messages="$errors->get('date')" class="mt-1" />
                </div>

                <div class="mb-4">
                    <label for="departure_time" class="block font-medium">Orario di partenza</label>
                    <input type="time" name="departure_time" id="departure_time" class="form-input w-full"
                        value="{{ old('departure_time', $requestedTrain->departure_time) }}" required>
                    <x-input-error :messages="$errors->get('departure_time')" class="mt-1" />
                </div>

                <div class="mb-4">
                    <label for="departure_station_id" class="block font-medium">Stazione di partenza</label>
                    <select name="departure_station_id" id="departure_station_id" class="form-select w-full" required>
                        @foreach ($stations as $station)
                            <option value="{{ $station->id }}" @selected($requestedTrain->departure_station_id == $station->id)>
                                {{ $station->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('departure_station_id')" class="mt-1" />
                </div>

                <div class="mb-4">
                    <label for="arrival_station_id" class="block font-medium">Stazione di arrivo</label>
                    <select name="arrival_station_id" id="arrival_station_id" class="form-select w-full" required>
                        @foreach ($stations as $station)
                            <option value="{{ $station->id }}" @selected($requestedTrain->arrival_station_id == $station->id)>
                                {{ $station->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('arrival_station_id')" class="mt-1" />
                </div>

                <div class="mb-4">
                    <label for="seats" class="block font-medium">Posti richiesti</label>
                    <input type="number" name="seats" id="seats" class="form-input w-full"
                        value="{{ old('seats', $requestedTrain->seats) }}" required min="10">
                    <x-input-error :messages="$errors->get('seats')" class="mt-1" />
                </div>

                <div class="mb-4">
                    <label for="admin_message" class="block font-medium">Messaggio da amministrazione</label>
                    <textarea name="admin_message" id="admin_message" class="form-textarea w-full">{{ old('admin_message', $requestedTrain->admin_message) }}</textarea>
                    <x-input-error :messages="$errors->get('admin_message')" class="mt-1" />
                </div>

                <div class="mb-4">
                    <label for="status" class="block font-medium">Stato</label>
                    <select name="status" id="status" class="form-select w-full" required>
                        <option value="in attesa del backoffice esercizio" @selected($requestedTrain->status === 'in attesa del backoffice esercizio')>In attesa</option>
                        <option value="treno creato" @selected($requestedTrain->status === 'treno creato')>Treno creato</option>
                        <option value="richiesta rifiutata" @selected($requestedTrain->status === 'richiesta rifiutata')>Rifiutata</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>

                <x-primary-button>Aggiorna richiesta</x-primary-button>
            </form>
    
        @endif


    </div>
</x-app-layout>
