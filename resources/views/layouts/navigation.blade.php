<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 mt-4 mb-6">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Navigation allineata a sinistra -->
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800">SFT</a>

                <!-- elementi nav public guest -->
                @guest
                    <a href="{{ route('cities') }}" class="text-gray-700 hover:text-blue-600">Città</a>
                    <a href="{{ route('visited-stations') }}" class="text-gray-700 hover:text-blue-600">Stazioni</a>
                    <a href="{{ route('cities') }}" class="text-gray-700 hover:text-blue-600">Convogli</a>
                @endguest

                @auth
                <!-- elementi nav customer -->

                    @if (Auth::user()->role === 'customer')                    
                        <a href="{{ route('cities') }}" class="text-gray-700 hover:text-blue-600">Città</a>
                        <a href="{{ route('visited-stations') }}" class="text-gray-700 hover:text-blue-600">Stazioni</a>
                        <a href="{{ route('cities') }}" class="text-gray-700 hover:text-blue-600">Convogli</a>
                        <a href="{{ route('ticket.buy') }}" class="text-gray-700 hover:text-blue-600">Acquista biglietto</a>
                        <a href="{{ route('ticket.index') }}" class="text-gray-700 hover:text-blue-600">I miei biglietti</a>
                    @endif

                <!-- elementi nav backoffice amministrativo -->

                    @if (Auth::user()->role === 'backoffice_amministrazione')                    
                        <a href="{{ route('stations.index') }}" class="text-gray-700 hover:text-blue-600">Stazioni</a>
                        <a href="{{ route('rolling-stock.index') }}" class="text-gray-700 hover:text-blue-600">Materiale Rotabile</a>
                        <a href="{{ route('convoys.index') }}" class="text-gray-700 hover:text-blue-600">Convogli</a>
                        <a href="{{ route('trains.index') }}" class="text-gray-700 hover:text-blue-600">Treni</a>
                        <a href="{{ route('requested-trains.index') }}" class="text-gray-700 hover:text-blue-600">Richieste Treni</a>
                    @endif
                <!-- elementi nav backoffice esercizio -->

                    @if (Auth::user()->role === 'backoffice_esercizio')
                        <a href="{{ route('stations.index') }}" class="text-gray-700 hover:text-blue-600">Stazioni</a>
                        <a href="{{ route('rolling-stock.index') }}" class="text-gray-700 hover:text-blue-600">Materiale Rotabile</a>
                        <a href="{{ route('convoys.index') }}" class="text-gray-700 hover:text-blue-600">Convogli</a>
                        <a href="{{ route('trains.index') }}" class="text-gray-700 hover:text-blue-600">Treni</a>
                        <a href="{{ route('requested-trains.index') }}" class="text-gray-700 hover:text-blue-600">Richieste Treni</a>
                    @endif
                @endauth
            </div>

            <!-- navigation allineata a destra - parte di login register -->
            <div class="flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Registrati</a>
                @else
                    <span class="text-gray-700">Ciao, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline">Logout</button>
                    </form>
                @endguest
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @if (Auth::user()->role === 'backoffice_esercizio')
                    <x-responsive-nav-link :href="route('stations.index')">Stazioni</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('rolling-stock.index')">Materiale Rotabile</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('convoys.index')">Convogli</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('trains.index')">Treni</x-responsive-nav-link>
                @endif
            @endauth

            @guest
                <x-responsive-nav-link :href="route('cities')">Città</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('login')">Login</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">Registrati</x-responsive-nav-link>
            @endguest
        </div>

        <!-- Responsive Settings -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">Profilo</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            Logout
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
