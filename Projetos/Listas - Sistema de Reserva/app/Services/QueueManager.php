<?php

namespace App\Services;
use App\Models\Attraction;
use App\Models\Visitor;
use App\Models\ReservationHistory;
use App\Services\VirtualQueue;
use App\Models\VirtualQueue as VirtualQueueModel;

class QueueManager
{
    private static array $queues = [];

    public static function getQueueForAttraction(int $attractionId): VirtualQueue
    {
        // Carrega da memória se já estiver carregado
        if (isset(self::$queues[$attractionId])) {
            return self::$queues[$attractionId];
        }

        // Caso contrário, carrega do banco
        $record = VirtualQueueModel::where('attraction_id', $attractionId)->first();
        $queue = new VirtualQueue();

        if ($record) {
            $queue->loadFromArray($record->queue_data);
        }

        // Armazena na memória para reutilização durante a requisição
        self::$queues[$attractionId] = $queue;

        return $queue;
    }

    public static function saveQueue(int $attractionId, VirtualQueue $queue)
    {
        VirtualQueueModel::updateOrCreate(
            ['attraction_id' => $attractionId],
            ['queue_data' => $queue->toArray()]
        );

        self::$queues[$attractionId] = $queue;
    }

    public static function addToQueue(Attraction $attraction, Visitor $visitor)
    {
        $queue = self::getQueueForAttraction($attraction->id);

        $hasPriority = $attraction->has_priority_access &&
                       in_array($visitor->ticket_type, ['VIP', 'anual']);

        $queue->enqueue($visitor, $hasPriority);

        self::saveQueue($attraction->id, $queue);
    }

    public static function getQueueList(int $attractionId): array
    {
        return self::getQueueForAttraction($attractionId)->getQueue();
    }

    public static function callNext(int $attractionId): ?Visitor
    {
        $queue = self::getQueueForAttraction($attractionId);
        $visitor = $queue->dequeue();

        if ($visitor) {
            ReservationHistory::create([
                'visitor_id' => $visitor->id,
                'attraction_id' => $attractionId
            ]);
        }

        self::saveQueue($attractionId, $queue);

        return $visitor;
    }

    public static function getVisitorPosition(int $attractionId, int $visitorId): ?int
    {
        return self::getQueueForAttraction($attractionId)->getPosition($visitorId);
    }
}
