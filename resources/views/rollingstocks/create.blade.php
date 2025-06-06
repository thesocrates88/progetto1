<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nuovo materiale rotabile
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow rounded">

            @if(session('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 px-4 py-2 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('rolling-stock.store') }}">
                @csrf

                <!-- Codice -->
                <div class="mb-4">
                    <label for="code" class="block font-medium text-sm text-gray-700">Codice</label>
                    <input type="text" name="code" id="code"
                           class="mt-1 block w-full rounded border-gray-300 shadow-sm"
                           value="{{ old('code') }}" required>
                </div>

                <!-- Tipo -->
                <div class="mb-4">
                    <label for="type" class="block font-medium text-sm text-gray-700">Tipo</label>
                    <select name="type" id="type"
                            class="mt-1 block w-full rounded border-gray-300 shadow-sm" required>
                        <option value="">-- Seleziona --</option>
                        <option value="carrozza" {{ old('type') == 'carrozza' ? 'selected' : '' }}>Carrozza</option>
                        <option value="automotrice" {{ old('type') == 'automotrice' ? 'selected' : '' }}>Automotrice</option>
                        <option value="bagagliaio" {{ old('type') == 'bagagliaio' ? 'selected' : '' }}>Bagagliaio</option>
                        <option value="locomotiva" {{ old('type') == 'locomotiva' ? 'selected' : '' }}>Locomotiva</option>
                    </select>
                </div>

                <!-- Posti a sedere -->
                <div class="mb-4">
                    <label for="seats" class="block font-medium text-sm text-gray-700">Posti a sedere</label>
                    <input type="number" name="seats" id="seats"
                           class="mt-1 block w-full rounded border-gray-300 shadow-sm"
                           value="{{ old('seats') }}" required>
                </div>

                <!-- Serie -->
                <div class="mb-6">
                    <label for="series" class="block font-medium text-sm text-gray-700">Serie</label>
                    <input type="text" name="series" id="series"
                           class="mt-1 block w-full rounded border-gray-300 shadow-sm"
                           value="{{ old('series') }}">
                </div>

                <div>
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded">
                        Salva
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
