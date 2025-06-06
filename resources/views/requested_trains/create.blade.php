<x-app-layout>
    <div class="max-w-4xl mx-auto mt-12 px-4">
        <h2 class="text-2xl font-bold mb-6">Crea nuova richiesta</h2>

        <form method="POST" action="{{ route('requested-trains.store') }}">
            @csrf

            <div class="mb-4">
                <label for="date" class="block font-medium">Data</label>
                <input type="date" name="date" id="date" class="form-input w-full" required>
                <x-input-error :messages="$errors->get('date')" class="mt-1" />
            </div>

            <div class="mb-4">
                <label for="departure_station_id" class="block font-medium">Stazione di partenza</label>
                <select name="departure_station_id" id="departure_station_id" class="form-select w-full" required>
                    @foreach ($stations as $station)
                        <option value="{{ $station->id }}">{{ $station->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('departure_station_id')" class="mt-1" />
            </div>

            <div class="mb-4">
                <label for="arrival_station_id" class="block font-medium">Stazione di arrivo</label>
                <select name="arrival_station_id" id="arrival_station_id" class="form-select w-full" required>
                    @foreach ($stations as $station)
                        <option value="{{ $station->id }}">{{ $station->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('arrival_station_id')" class="mt-1" />
            </div>

            <div class="mb-4">
                <label for="departure_time" class="block font-medium">Orario di partenza</label>
                <input type="time" name="departure_time" id="departure_time" class="form-input w-full" required>
                <x-input-error :messages="$errors->get('departure_time')" class="mt-1" />
            </div>

            <div class="mb-4">
                <label for="seats" class="block font-medium">Posti richiesti</label>
                <input type="number" name="seats" id="seats" class="form-input w-full" required min="10">
                <x-input-error :messages="$errors->get('seats')" class="mt-1" />
            </div>

            <div class="mb-4">
                <label for="admin_message" class="block font-medium">Messaggio per esercizio (opzionale)</label>
                <textarea name="admin_message" id="admin_message" class="form-textarea w-full">{{ old('admin_message') }}</textarea>
                <x-input-error :messages="$errors->get('admin_message')" class="mt-1" />
            </div>

            <x-primary-button>Crea richiesta</x-primary-button>
        </form>
    </div>
</x-app-layout>
