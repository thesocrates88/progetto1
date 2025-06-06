<x-app-layout>
    <div class="max-w-5xl mx-auto mt-12 px-4">
        <section class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Convogli disponibili</h2>

            @if ($convoys->isEmpty())
                <p class="text-gray-600">Nessun convoglio disponibile al momento.</p>
            @else
                <ul class="space-y-3 text-gray-700">
                    @foreach ($convoys as $convoy)
                        <li>
                            <a href="{{ route('convoys.public.show', $convoy->id) }}"
                               class="text-blue-600 underline hover:text-blue-800">
                                Convoglio {{ $convoy->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
</x-app-layout>
