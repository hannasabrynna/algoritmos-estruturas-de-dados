<?php

namespace App\Services;

use App\Models\Visitor;

class VirtualQueue
{
    private ?QueueNode $head = null;

    public function enqueue(Visitor $visitor, bool $hasPriority)
    {
        $newNode = new QueueNode($visitor);

        if (!$this->head) {
            $this->head = $newNode;
            return;
        }

        if ($hasPriority && !$this->isPriority($this->head)) {
            $newNode->next = $this->head;
            $this->head = $newNode;
            return;
        }

        $current = $this->head;
        while ($current->next && (!$hasPriority || $this->isPriority($current->next))) {
            $current = $current->next;
        }

        $newNode->next = $current->next;
        $current->next = $newNode;
    }

    public function dequeue(): ?Visitor
    {
        if (!$this->head) return null;

        $visitor = $this->head->visitor;
        $this->head = $this->head->next;
        return $visitor;
    }

    public function getQueue(): array
    {
        $queue = [];
        $current = $this->head;
        while ($current) {
            $queue[] = $current->visitor;
            $current = $current->next;
        }
        return $queue;
    }

    private function isPriority(QueueNode $node): bool
    {
        return in_array($node->visitor->ticket_type, ['VIP', 'anual']);
    }

    public function getPosition(int $visitorId): ?int
    {
        $current = $this->head;
        $position = 1;

        while ($current) {
            if ($current->visitor->id === $visitorId) {
                return $position;
            }
            $current = $current->next;
            $position++;
        }

        return null; // visitante não está na fila
    }

    public function toArray(): array
{
    $result = [];
    $current = $this->head;

    while ($current) {
        $result[] = $current->visitor->id;
        $current = $current->next;
    }

    return $result;
}

public function loadFromArray(array $visitorIds)
{
    foreach ($visitorIds as $id) {
        $visitor = Visitor::find($id);
        if ($visitor) {
            $this->enqueue($visitor, false); // ordem original, sem reprocessar prioridade
        }
    }
}

}
