<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Attraction;
use App\Services\QueueManager;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class VisitorPortalController extends Controller
{
    public function getActiveQueues($visitorId)
    {
        $attractions = Attraction::all();
        $result = [];

        foreach ($attractions as $attraction) {
            $position = QueueManager::getVisitorPosition($attraction->id, $visitorId);
            if ($position !== null) {
                $result[] = [
                    'attraction' => $attraction->name,
                    'attraction_id' => $attraction->id,
                    'position' => $position
                ];
            }
        }

        return response()->json($result);
    }

        public function getHistory($visitorId)
    {
        $history = \App\Models\ReservationHistory::with('attraction')
                    ->where('visitor_id', $visitorId)
                    ->orderByDesc('reserved_at')
                    ->get()
                    ->map(function ($record) {
                        return [
                            'attraction' => $record->attraction->name,
                            'reserved_at' => $record->reserved_at->format('d/m/Y H:i')
                        ];
                    });

        return response()->json($history);
    }
}


