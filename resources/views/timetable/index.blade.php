<x-app-layout>
  <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold mb-4 text-center">Orario Ferroviario</h2>

    <table class="table-auto border-collapse mx-auto text-center text-sm">
      <thead>
        <tr>
          <th colspan="{{ $northSouth->count() }}" class="border px-2 py-1 bg-gray-200">Nord → Sud</th>
          <th colspan="3" class="border px-2 py-1 bg-white"></th>
          <th colspan="{{ $southNorth->count() }}" class="border px-2 py-1 bg-gray-200">Sud → Nord</th>
        </tr>
        <tr>
          @foreach($northSouth as $train)
            <th class="border px-2 py-1">{{ $train->name }}</th>
          @endforeach

          <th class="border px-2 py-1">km</th>
          <th class="border px-2 py-1">Stazione</th>
          <th class="border px-2 py-1">a/p</th>

          @foreach($southNorth as $train)
            <th class="border px-2 py-1">{{ $train->name }}</th>
          @endforeach
        </tr>
      </thead>
        <tbody>
        @foreach($stations as $station)
            <tr>
            {{-- Colonne nord→sud --}}
            @foreach($northSouth as $train)
                @php
                $a = $train->subTratte->firstWhere('to_station_id', $station->id);
                $p = $train->subTratte->firstWhere('from_station_id', $station->id);
                @endphp
                <td class="border px-2 py-1 text-xs">
                {{ $a ? 'A: '.\Carbon\Carbon::parse($a->arrival_time)->format('H:i') : '' }}
                {{ $p ? ' P: '.\Carbon\Carbon::parse($p->departure_time)->format('H:i') : '' }}
                </td>
            @endforeach

            {{-- km + stazione --}}
            <td class="border">{{ number_format($station->kilometer, 3, '.', '') }}</td>
            <td class="border font-semibold">{{ $station->name }}</td>
            <td class="border"></td>

            {{-- Colonne sud→nord --}}
            @foreach($southNorth as $train)
                @php
                $a = $train->subTratte->firstWhere('to_station_id', $station->id);
                $p = $train->subTratte->firstWhere('from_station_id', $station->id);
                @endphp
                <td class="border px-2 py-1 text-xs">
                {{ $a ? 'A: '.\Carbon\Carbon::parse($a->arrival_time)->format('H:i') : '' }}
                {{ $p ? ' P: '.\Carbon\Carbon::parse($p->departure_time)->format('H:i') : '' }}
                </td>
            @endforeach
            </tr>
        @endforeach
        </tbody>

    </table>

    <p class="mt-6 text-sm text-gray-600 text-center max-w-3xl mx-auto">
      La prima riga mostra i treni in direzione nord→sud, la seconda riga centrale
      indica km e stazione, e i treni direzione sud→nord sono a destra.
      Le celle sono vuote se il treno non transita in quella stazione.
    </p>
  </div>
</x-app-layout>
