<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crea un nuovo convoglio
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow rounded">

            @if(session('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 px-4 py-2 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('convoys.store') }}">
                @csrf

                <!-- Nome convoglio -->
                <div class="mb-4">
                    <label for="name" class="block font-medium text-sm text-gray-700">Nome del convoglio</label>
                    <input type="text" name="name" id="name" required
                        class="mt-1 block w-full rounded border-gray-300 shadow-sm"
                        value="{{ old('name') }}">
                </div>

                <!-- Composizione -->
                <h3 class="font-semibold text-gray-700 mt-6 mb-2">Composizione (ordine e veicoli)</h3>

                <div class="space-y-4">
                    @foreach($rollingStocks as $rs)
                        <div class="flex items-center space-x-4">
                            <input type="checkbox" id="rs_{{ $rs->id }}" name="rolling_stock_ids[]"
                                   value="{{ $rs->id }}"
                                   {{ is_array(old('rolling_stock_ids')) && in_array($rs->id, old('rolling_stock_ids')) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm">

                            <label for="rs_{{ $rs->id }}" class="flex-1">
                                {{ $rs->code }} ({{ $rs->type }} - {{ $rs->seats }} posti)
                            </label>

                            <label class="text-sm text-gray-500">Posizione:</label>
                            <input type="number" name="positions[{{ $rs->id }}]" min="1"
                                   value="{{ old('positions.' . $rs->id) }}"
                                   class="w-20 rounded border-gray-300 shadow-sm">
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded">
                        Salva convoglio
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
