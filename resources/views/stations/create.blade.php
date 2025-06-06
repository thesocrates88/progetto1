<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Inserisci una nuova stazione
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

            <form method="POST" action="{{ route('stations.store') }}">
                @csrf

                <!-- Nome stazione -->
                <div class="mb-4">
                    <label for="name" class="block font-medium text-sm text-gray-700">Nome stazione</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                </div>

                <!-- Kilometro -->
                <div class="mb-4">
                    <label for="kilometer" class="block font-medium text-sm text-gray-700">Km (es. 12.680)</label>
                    <input type="text" name="kilometer" id="kilometer" value="{{ old('kilometer') }}" required
                           class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                </div>

                <!-- Ordine -->
                <div class="mb-6">
                    <label for="order" class="block font-medium text-sm text-gray-700">Ordine (1-10)</label>
                    <input type="number" name="order" id="order" value="{{ old('order') }}" required
                           class="mt-1 block w-full rounded border-gray-300 shadow-sm">
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
