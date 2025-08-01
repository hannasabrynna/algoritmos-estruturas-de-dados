<?php

namespace App\DataStructures\BPlusTree;

class BPlusNode
{
    public array $keys = [];
    public array $children = [];
    public bool $isLeaf;
    public ?BPlusNode $parent = null;
    public ?BPlusNode $nextLeaf = null; // usado para encadeamento das folhas

    public function __construct(bool $isLeaf = false)
    {
        $this->isLeaf = $isLeaf;
    }

    public function isFull(int $maxKeys): bool
    {
        return count($this->keys) >= $maxKeys;
    }
}
