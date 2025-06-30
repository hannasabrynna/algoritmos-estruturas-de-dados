<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attraction;
use App\Models\Visitor;
use App\Services\QueueManager;
use App\Models\ReservationHistory;
use Inertia\Inertia;



class QueueController extends Controller
{
    public function enterQueue(Request $request)
    {
        $visitor = Visitor::findOrFail($request->visitor_id);
        // $attraction = Attraction::findOrFail($request->attraction_id);
        $attraction = Attraction::whereRaw('LOWER(name) = ?', [strtolower($request->attraction_name)])->firstOrFail();

        QueueManager::addToQueue($attraction, $visitor);

        return response()->json([
            'message' => 'Visitante entrou na fila virtual com sucesso.'
        ]);
    }

    public function showQueue(Request $request)
    {
        // Permite buscar por nome ou id
        if ($request->has('attraction_name')) {
            $attraction = Attraction::whereRaw('LOWER(name) = ?', [strtolower($request->attraction_name)])->firstOrFail();
            $attractionId = $attraction->id;
        } else {
            $attractionId = $request->attraction_id;
        }

        $queue = QueueManager::getQueueList((int) $attractionId);
        return response()->json([
            'queue' => array_map(function ($v) {
                return [
                    'id' => $v->id,
                    'name' => $v->name,
                    'ticket_type' => $v->ticket_type,
                ];
            }, $queue)
        ]);
    }

    public function callNext(Request $request)
    {// Permite buscar por nome ou id
        if ($request->has('attraction_name')) {
            $attraction = Attraction::whereRaw('LOWER(name) = ?', [strtolower($request->attraction_name)])->firstOrFail();
            $attractionId = $attraction->id;
        } else {
            $attractionId = $request->attraction_id;
        }

        $visitor = QueueManager::callNext($attractionId);

        if ($visitor) {
        // Salva no histórico
        ReservationHistory::create([
            'visitor_id' => $visitor->id,
            'attraction_id' => $attractionId,
            'reserved_at' => now(),
        ]);

       
        if ($visitor) {
            return response()->json([
                'message' => 'Próximo visitante chamado com sucesso.',
                'visitor' => [
                    'id' => $visitor->id,
                    'name' => $visitor->name,
                    'ticket_type' => $visitor->ticket_type
                ]
            ]);

        }

        return response()->json(['message' => 'Fila vazia. Nenhum visitante para chamar.'], 404);
    }
    }

    public function getVisitorPosition(Request $request)
    {
        $request->validate([
        'visitor_id' => 'required|exists:visitors,id',
    ]);

    // Permite buscar por nome ou id
        if ($request->has('attraction_name')) {
            $attraction = Attraction::whereRaw('LOWER(name) = ?', [strtolower($request->attraction_name)])->firstOrFail();
            $attractionId = $attraction->id;
        } else {
            $request->validate([
                'attraction_id' => 'required|exists:attractions,id'
            ]);
            $attractionId = $request->attraction_id;
        }

        $position = QueueManager::getVisitorPosition($attractionId, $request->visitor_id);

        $message = $position !== null
            ? "Você está na posição $position da fila."
            : "Você não está na fila.";

        return response()->json([
            'message' => $message,
            'position' => $position
        ]);
    }
}
