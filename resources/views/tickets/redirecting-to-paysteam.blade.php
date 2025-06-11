<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reindirizzamento a PaySteam
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="mb-4">Stai per essere reindirizzato alla piattaforma di pagamento per completare l’acquisto.</p>
                <p class="mb-6">Se non vieni reindirizzato automaticamente, <a href="{{ $redirectUrl }}" class="text-blue-600 underline">clicca qui</a>.</p>

                <form id="redirectForm" action="{{ $redirectUrl }}" method="GET">
                    @csrf
                </form>

                <script>
                    setTimeout(() => {
                        window.location.href = "{{ $redirectUrl }}";
                    }, 2000);
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
