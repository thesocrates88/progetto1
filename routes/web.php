<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StationController;
use App\Http\Controllers\RollingStockController;
use App\Http\Controllers\ConvoyController;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\PublicHomeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\RequestedTrainController;
use App\Http\Controllers\TimetableController;

//Route::get('/', function () {
//    return view('PublicHome');
//});

//rotta pagine guest
Route::get('/', [PublicHomeController::class, 'index'])->name('home');
Route::view('/cities', 'cities')->name('cities');
Route::view('/visited-stations', 'visited-stations')->name('visited-stations');
Route::get('/public-trains/{train}', [TrainController::class, 'show'])->name('trains.public.show');
Route::get('/public-convoys/{convoy}', [ConvoyController::class, 'show'])->name('convoys.public.show');
Route::get('/public-convoys', [ConvoyController::class, 'publicIndex'])->name('convoys.public.index');
Route::get('/timetable', [TimetableController::class, 'index'])->name('timetable.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ROTTA PUBBLICA: PaySteam chiama questa dopo il pagamento
Route::withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->post('/api/payment/callback', [\App\Http\Controllers\TicketController::class, 'paymentCallback'])
    ->name('payment.callback');


// ROTTE PER IL BACKOFFICE ESERCIZIO
Route::middleware(['auth', 'backoffice_esercizio'])->group(function () {
    Route::resource('stations', StationController::class);
    Route::resource('rolling-stock', RollingStockController::class);
    Route::resource('convoys', ConvoyController::class);
    Route::resource('trains', TrainController::class);
});

//ROTTE PER CUSTOMERS
Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/buy-ticket', [TicketController::class, 'create'])->name('ticket.buy');
    Route::post('/buy-ticket/search', [TicketController::class, 'search'])->name('ticket.search');
    Route::get('/buy-ticket/train/{train}', [TicketController::class, 'show'])->name('ticket.show');
    Route::post('/buy-ticket/train/{train}/checkout', [TicketController::class, 'checkout'])->name('ticket.checkout');
    Route::post('/buy-ticket/train/{train}/purchase', [TicketController::class, 'purchase'])->name('ticket.purchase');
    Route::get('/my-tickets', [TicketController::class, 'index'])->name('ticket.index');
    Route::get('/my-tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('ticket.edit');
    Route::put('/my-tickets/{ticket}', [TicketController::class, 'update'])->name('ticket.update');
    Route::get('/buy-ticket/results', [TicketController::class, 'results'])->name('ticket.results');
});

 //ROTTE COMUNI PER BACKOFFICE AMMINISTRATIVO E ESERCIZIO
Route::middleware(['auth', 'backoffice'])->group(function () {
    Route::resource('requested-trains', RequestedTrainController::class);
});

//ROTTE PER BACKOFFICE AMMINISTRAZIONE
Route::middleware(['auth', 'backoffice_amministrazione'])->group(function () {
    Route::get('/revenue', [\App\Http\Controllers\RevenueController::class, 'index'])->name('admin.trains.revenue');
});

//rotte per utenti loggati ma non basate su ruolo
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //li ho dovuti creare per permettere anche a backoffice amministrativo di poter vedere questi ma non editare
    Route::get('/trains', [TrainController::class, 'index'])->name('trains.index');
    Route::get('/trains/{train}', [TrainController::class, 'show'])->name('trains.show');
    Route::get('/convoys', [ConvoyController::class, 'index'])->name('convoys.index');
    Route::get('/convoys/{convoy}', [ConvoyController::class, 'show'])->name('convoys.show');
    Route::get('/rolling-stock', [RollingStockController::class, 'index'])->name('rolling-stock.index');
    Route::get('/rolling-stock/{rolling_stock}', [RollingStockController::class, 'show'])->name('rolling-stock.show');
    Route::get('/stations', [StationController::class, 'index'])->name('stations.index');
    Route::get('/stations/{station}', [StationController::class, 'show'])->name('stations.show');
});

require __DIR__.'/auth.php';
