<x-app-layout>
    <div class="container mt-5">
        <h2 class="fw-bold mb-4">Modifica Biglietto #{{ $ticket->id }}</h2>

        <!-- MESSAGGI -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FASE 1: selezione treno -->
        @if ($treniDisponibili->count())
            <form method="GET" action="{{ route('ticket.edit', $ticket->id) }}" class="mb-5">
                <div class="mb-3">
                    <label for="train_id" class="form-label">Seleziona il treno disponibile</label>
                    <select name="train_id" id="train_id" class="form-select" required>
                        @foreach ($treniDisponibili as $t)
                            <option value="{{ $t->id }}" @selected($train && $t->id == $train->id)>
                                Treno #{{ $t->id }} – {{ $t->date }} – {{ \Carbon\Carbon::parse($t->departure_time)->format('H:i') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-outline-primary">Carica posti disponibili</button>
            </form>
        @else
            <p class="text-muted">Nessun treno disponibile da oggi in poi per questa tratta.</p>
        @endif

        <!-- FASE 2: selezione carrozza e posto -->
        @if ($train)
            <form method="POST" action="{{ route('ticket.update', $ticket->id) }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="date" value="{{ $train->date }}">
                <input type="hidden" name="train_id" value="{{ $train->id }}">

                <div class="mb-3">
                    <label for="rolling_stock_id" class="form-label">Carrozza</label>
                    <select name="rolling_stock_id" class="form-select" required>
                        <option value="">-- Seleziona una carrozza --</option>
                        @foreach ($train->convoy->rollingStocks as $carrozza)
                            <option value="{{ $carrozza->id }}">
                                Carrozza {{ $carrozza->position }} – {{ $carrozza->type }}
                            </option>
                        @endforeach
                    </select>
                    @error('rolling_stock_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="numero_posto" class="form-label">Posto a sedere</label>
                    <select name="numero_posto" class="form-select" required>
                        <option value="">-- Seleziona un posto --</option>
                        @foreach ($train->convoy->rollingStocks as $carrozza)
                            @php
                                $occupati = $occupiedSeats->get($carrozza->id)?->pluck('numero_posto') ?? collect();
                            @endphp
                            @for ($i = 1; $i <= $carrozza->seats; $i++)
                                @if (!$occupati->contains($i))
                                    <option value="{{ $i }}">Posto {{ $i }} (Carrozza {{ $carrozza->position }})</option>
                                @endif
                            @endfor
                        @endforeach
                    </select>
                    @error('numero_posto')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-primary">Conferma modifiche</button>
            </form>
        @endif
    </div>
</x-app-layout>
