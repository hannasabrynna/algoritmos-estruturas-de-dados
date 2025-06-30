<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReservationHistory;
use Illuminate\Support\Facades\DB;

class QueueStatisticsController extends Controller
{

public function getStatistics()
{
    $today = now()->toDateString(); // Garantir comparação por data

    // Reservas por hora
    $reservasPorHora = ReservationHistory::selectRaw('HOUR(reserved_at) as hora, COUNT(*) as total')
        ->whereDate('reserved_at', $today)
        ->groupBy('hora')
        ->orderBy('hora')
        ->get();

    // Atração mais popular (com nome)
    $atracaoMaisPopular = ReservationHistory::select('attractions.name as attraction_name', DB::raw('COUNT(*) as total'))
        ->join('attractions', 'reservation_histories.attraction_id', '=', 'attractions.id')
        ->whereDate('reserved_at', $today)
        ->groupBy('attractions.name')
        ->orderByDesc('total')
        ->first();

    // Visitante mais ativo (com nome)
    $visitanteMaisAtivo = ReservationHistory::select(
        'visitors.name as visitor_name',
        'visitors.email as visitor_email',
        DB::raw('COUNT(*) as total')
    )
         ->join('visitors', 'reservation_histories.visitor_id', '=', 'visitors.id')
            ->whereDate('reserved_at', $today)
            ->groupBy('visitors.id', 'visitors.name', 'visitors.email') // agrupar por ID e outros campos únicos
            ->orderByDesc('total')
            ->first();

    return response()->json([
        'reservas_por_hora' => $reservasPorHora,
        'atracao_mais_popular' => $atracaoMaisPopular,
        'visitante_mais_ativo' => $visitanteMaisAtivo
    ]);
}

}