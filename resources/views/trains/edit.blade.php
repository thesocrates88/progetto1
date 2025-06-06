<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifica treno #{{ $train->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow rounded">

            @if ($errors->any())
                <div class="mb-4 bg-red-100 text-red-700 border border-red-400 px-4 py-2 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('trains.update', $train->id) }}">
                @csrf
                @method('PUT')

                <!-- Convoglio -->
                <div class="mb-4">
                    <label for="convoy_id" class="block font-medium text-sm text-gray-700">Convoglio</label>
                    <select name="convoy_id" id="convoy_id" required class="mt-1 block w-full border-gray-300 rounded shadow-sm">
                        @foreach ($convoys as $convoy)
                            <option value="{{ $convoy->id }}" {{ old('convoy_id', $train->convoy_id) == $convoy->id ? 'selected' : '' }}>
                                {{ $convoy->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stazione di partenza -->
                <div class="mb-4">
                    <label for="departure_station_id" class="block font-medium text-sm text-gray-700">Stazione di partenza</label>
                    <select name="departure_station_id" id="departure_station_id" required class="mt-1 block w-full border-gray-300 rounded shadow-sm">
                        @foreach ($stations as $station)
                            <option value="{{ $station->id }}" {{ old('departure_station_id', $train->departure_station_id) == $station->id ? 'selected' : '' }}>
                                {{ $station->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stazione di arrivo -->
                <div class="mb-4">
                    <label for="arrival_station_id" class="block font-medium text-sm text-gray-700">Stazione di arrivo</label>
                    <select name="arrival_station_id" id="arrival_station_id" required class="mt-1 block w-full border-gray-300 rounded shadow-sm">
                        @foreach ($stations as $station)
                            <option value="{{ $station->id }}" {{ old('arrival_station_id', $train->arrival_station_id) == $station->id ? 'selected' : '' }}>
                                {{ $station->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Data -->
                <div class="mb-4">
                    <label for="date" class="block font-medium text-sm text-gray-700">Data del viaggio</label>
                    <input type="date" name="date" id="date" class="mt-1 block w-full border-gray-300 rounded shadow-sm"
                           value="{{ old('date', $train->date) }}" required>
                </div>

                <!-- Ora di partenza -->
                <div class="mb-6">
                    <label for="departure_time" class="block font-medium text-sm text-gray-700">Ora di partenza</label>
                    <input type="time" name="departure_time" id="departure_time"
                           class="mt-1 block w-full border-gray-300 rounded shadow-sm"
                           value="{{ old('departure_time', $train->departure_time) }}" required>
                </div>

                <div>
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded">
                        Salva modifiche
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
