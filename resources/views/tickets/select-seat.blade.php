<x-app-layout>

@if ($errors->any())
    <div class="mb-4 p-4 rounded bg-red-100 text-red-800 border border-red-300">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="container mt-5">
        <h2 class="fw-bold mb-4">Seleziona carrozza e posto</h2>

        <div class="mb-4">
            <p><strong>Treno:</strong> #{{ $train->id }} del {{ $train->date }}</p>
            <p><strong>Tratta:</strong> {{ $train->departureStation->name }} → {{ $train->arrivalStation->name }}</p>
            <p><strong>Subtratta selezionata:</strong> {{ $fromId }} → {{ $toId }}</p>
            <p><strong>Distanza:</strong> {{ $km }} km | <strong>Costo per posto:</strong> € {{ number_format($cost, 2, ',', '') }}</p>
        </div>

        <form method="POST" action="{{ route('ticket.checkout', ['train' => $train->id]) }}">
            @csrf
            <input type="hidden" name="from_station_id" value="{{ $fromId }}">
            <input type="hidden" name="to_station_id" value="{{ $toId }}">
            <input type="hidden" name="date" value="{{ $date }}">

            @foreach ($rollingStocks as $carrozza)
                <div class="mb-5 border rounded p-3">
                    <h5 class="mb-3">Carrozza {{ $carrozza->position }} – {{ $carrozza->type }}</h5>

                    @php
                        $occupati = $occupiedSeats->get($carrozza->id)?->pluck('numero_posto') ?? collect();
                    @endphp

                    <div class="row g-2">
                        @for ($posto = 1; $posto <= $carrozza->seats; $posto++)
                            <div class="col-6 col-sm-3">
                                @if ($occupati->contains($posto))
                                    <div class="bg-danger text-white text-center p-2 rounded small">
                                        Posto {{ $posto }}<br><span class="fst-italic">Occupato</span>
                                    </div>

                                @else
                                    <label for="posto-{{ $carrozza->id }}-{{ $posto }}" class="d-flex flex-column align-items-center justify-content-center bg-success bg-opacity-10 p-3 rounded text-center shadow w-100" style="min-height: 80px;">
                                        <input class="form-check-input mb-2" type="checkbox" name="posti[{{ $carrozza->id }}][]" value="{{ $posto }}" id="posto-{{ $carrozza->id }}-{{ $posto }}">
                                        <span class="small">Posto {{ $posto }}</span>
                                    </label>
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
            @endforeach

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Procedi al pagamento</button>
                <p class="text-muted small mt-1">Totale: calcolato alla conferma</p>
            </div>
        </form>
    </div>
</x-app-layout>
