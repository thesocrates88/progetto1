<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div><a href="{{ route('trains.index') }}">Vai alla gestione treni</a></div>
                <div><a href="{{ route('convoys.index') }}">Vai alla gestione convogli</a></div>
                <div><a href="{{ route('rolling-stock.index') }}">Vai alla gestione materiale rotante</a></div>
                <div><a href="{{ route('stations.index') }}">Vai alla gestione stazioni</a></div>
                


            </div>
        </div>
    </div>
</x-app-layout>
