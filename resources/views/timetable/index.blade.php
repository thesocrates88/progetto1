<x-app-layout>
  <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold mb-6 text-center">Orario Ferroviario</h2>

    <div class="w-full overflow-x-auto flex justify-center">
      <table class="table-auto border-collapse text-center text-sm">
        <thead>
          <tr>
            <th colspan="{{ $northSouth->count() }}" class="bg-gray-100 border px-2 py-1">Nord → Sud</th>
            <th colspan="2" class="border px-2 py-1"></th>
            <th colspan="{{ $southNorth->count() }}" class="bg-gray-100 border px-2 py-1">Sud → Nord</th>
          </tr>
          <tr>
            @foreach($northSouth as $train)
              <th class="border px-2 py-1">{{ $train->name }}</th>
            @endforeach

            <th class="border px-2 py-1">km</th>
            <th class="border px-2 py-1">Stazione</th>

            @foreach($southNorth as $train)
              <th class="border px-2 py-1">{{ $train->name }}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach($stations as $station)
            <tr>
              {{-- Nord → Sud --}}
              @foreach($northSouth as $train)
                @php
                  $a = $train->subTratte->firstWhere('to_station_id', $station->id);
                  $p = $train->subTratte->firstWhere('from_station_id', $station->id);
                @endphp
                <td class="border px-2 py-1 text-xs whitespace-nowrap">
                  {{ $a ? 'A: ' . \Carbon\Carbon::parse($a->arrival_time)->format('H:i') : '' }}
                  {{ $p ? ' P: ' . \Carbon\Carbon::parse($p->departure_time)->format('H:i') : '' }}
                </td>
              @endforeach

              {{-- km + stazione --}}
              <td class="border px-2 py-1">{{ number_format($station->kilometer, 3, '.', '') }}</td>
              <td class="border font-semibold px-2 py-1">{{ $station->name }}</td>

              {{-- Sud → Nord --}}
              @foreach($southNorth as $train)
                @php
                  $a = $train->subTratte->firstWhere('to_station_id', $station->id);
                  $p = $train->subTratte->firstWhere('from_station_id', $station->id);
                @endphp
                <td class="border px-2 py-1 text-xs whitespace-nowrap">
                  {{ $a ? 'A: ' . \Carbon\Carbon::parse($a->arrival_time)->format('H:i') : '' }}
                  {{ $p ? ' P: ' . \Carbon\Carbon::parse($p->departure_time)->format('H:i') : '' }}
                </td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <p class="mt-6 text-sm text-gray-600 text-center max-w-3xl mx-auto">
        Controllare sempre la disponibilità dei treni
    </p>
  </div>
</x-app-layout>
