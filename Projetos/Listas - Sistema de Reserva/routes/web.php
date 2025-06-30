<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\AttractionController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\VisitorPortalController;
use App\Models\Attraction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\QueueStatisticsController;

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
    $attractions = Attraction::all();
    return Inertia::render('Dashboard', ['attractions' => $attractions,]);
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

    // Página principal do sistema de fila (interface Vue via Inertia)
    Route::get('/fila', fn() => Inertia::render('Queue'))->name('queue.view');
    Route::get('/queue/show', [QueueController::class, 'showQueue']);
    // Ações da fila virtual (JSON)
    Route::post('/fila/entrar', [QueueController::class, 'enterQueue'])->name('queue.enter');
    Route::get('/fila/ver', [QueueController::class, 'showQueue'])->name('queue.show'); // Exibe a fila por attraction_id
    Route::post('/fila/chamar', [QueueController::class, 'callNext'])->name('queue.call-next'); // Chama próximo da fila
    Route::get('/fila/posicao', [QueueController::class, 'getVisitorPosition'])->name('queue.position'); // Retorna posição   

   Route::get('/fila/estatisticas', [QueueStatisticsController::class, 'getStatistics']);

    // Portal do Visitante (relatórios)
    Route::get('/portal/visitante/{visitorId}/filas', [VisitorPortalController::class, 'getActiveQueues'])->name('visitor.queues');
    Route::get('/portal/visitante/{visitorId}/historico', [VisitorPortalController::class, 'getHistory'])->name('visitor.history');
});



require __DIR__ . '/auth.php';
