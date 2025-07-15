<?php

namespace App\Services;

use App\Models\Visitor;

class QueueNode
{
    public Visitor $visitor;
    public ?QueueNode $next = null;

    public function __construct(Visitor $visitor)
    {
        $this->visitor = $visitor;
    }
}
