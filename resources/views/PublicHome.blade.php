<x-app-layout>
    <div class="max-w-4xl mx-auto mt-12 px-4">
        <section class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">La nostra storia</h2>
            <p class="text-gray-700 leading-relaxed">
                Fondata nel 1924, la linea ferroviaria SFT (Società Ferrovie Turistiche) fu creata per collegare i borghi costieri
                e collinari della regione con le principali città. Inizialmente nata per esigenze commerciali, la linea fu
                riconvertita negli anni '80 in un servizio turistico grazie alla bellezza mozzafiato dei paesaggi attraversati.
            </p>
            
            <p class="text-gray-700 mt-4">
                Oggi, con 10 stazioni lungo un percorso di 54 chilometri, la nostra linea offre un'esperienza unica a bordo di
                treni storici restaurati, attraversando colline verdi, borghi medievali e scorci panoramici sul mare. Ogni viaggio
                è un tuffo nel passato, tra cultura, relax e natura.
            </p>
        </section>
    </div>

    <div class="max-w-5xl mx-auto mt-12 px-6">
        <section class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">Orari dei Treni Storici</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">Numero</th>
                            <th class="px-4 py-2 border">Data</th>
                            <th class="px-4 py-2 border">Partenza</th>
                            <th class="px-4 py-2 border">Arrivo</th>
                            <th class="px-4 py-2 border">Percorso</th>
                            <th class="px-4 py-2 border">Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trains as $train)
                            <tr class="text-gray-700 {{ $loop->even ? 'bg-gray-50' : '' }}">
                    
                                <td class="px-4 py-2 border text-center"><a href="{{ route('trains.public.show', $train->id) }}" class="text-gray-700 hover:text-blue-600">{{ $train->id }}</a></td>
                                <td class="px-4 py-2 border text-center">{{ $train->date }}</td>
                                <td class="px-4 py-2 border text-center">{{ \Carbon\Carbon::parse($train->departure_time)->format('H:i') }}</td>
                                <td class="px-4 py-2 border text-center">{{ \Carbon\Carbon::parse($train->arrival_time)->format('H:i') }}</td>
                                <td class="px-4 py-2 border text-center">
                                    {{ $train->departureStation->name ?? '—' }} → {{ $train->arrivalStation->name ?? '—' }}
                                </td>
                                <td class="px-4 py-2 border text-center text-blue-600 underline hover:text-blue-800"><a href="{{ route('trains.public.show', $train->id) }}" class="text-gray-700 hover:text-blue-600">Mostra Treno</a></td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
