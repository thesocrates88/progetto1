@auth
    @if (Auth::user()->role === 'backoffice_esercizio')
        <div class="ml-4 flex space-x-4">
            <a href="{{ route('stations.index') }}" class="text-gray-700 hover:text-blue-600">Stazioni</a>
            <a href="{{ route('rolling-stock.index') }}" class="text-gray-700 hover:text-blue-600">Materiale Rotabile</a>
            <a href="{{ route('convoys.index') }}" class="text-gray-700 hover:text-blue-600">Convogli</a>
            <a href="{{ route('trains.index') }}" class="text-gray-700 hover:text-blue-600">Treni</a>
        </div>
    @endif
@endauth
