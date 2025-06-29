<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\AttractionController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\VisitorPortalController;
use Illuminate\Http\Request;
use Inertia\Inertia;


// Página inicial
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/visitors/create', fn() => Inertia::render('Visitors/Create'));
    Route::post('/visitors', [VisitorController::class, 'store']);

    Route::get('/attractions/create', fn() => Inertia::render('Attractions/Create'));
    Route::post('/attractions', [AttractionController::class, 'store']);

    Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index');
    Route::get('/attractions', [AttractionController::class, 'index'])->name('attractions.index');

    Route::post('/fila/entrar', [QueueController::class, 'enterQueue']);
    Route::get('/fila/{attractionId}', [QueueController::class, 'showQueue']);
    Route::post('/fila/{attractionId}/chamar', [QueueController::class, 'callNext']);
    Route::get('/fila/posicao', [QueueController::class, 'getVisitorPosition']);

    Route::get('/portal/visitante/{visitorId}/filas', [VisitorPortalController::class, 'getActiveQueues']);
    Route::get('/portal/visitante/{visitorId}/historico', [VisitorPortalController::class, 'getHistory']);

    Route::view('/fila', 'queue')->name('queue.view');

    Route::post('/fila/entrar', [QueueController::class, 'enterQueue'])->name('queue.enter');

    Route::get('/fila/ver', function (Request $request) {
        $queue = App\Services\QueueManager::getQueueList($request->attraction_id);
        dd($queue); // ou exibir em view no futuro
    })->name('queue.show');

    Route::get('/fila/chamar', function (Request $request) {
        $visitor = App\Services\QueueManager::callNext($request->attraction_id);
        dd($visitor); // ou exibir em view no futuro
    })->name('queue.call-next');
});



require __DIR__ . '/auth.php';
