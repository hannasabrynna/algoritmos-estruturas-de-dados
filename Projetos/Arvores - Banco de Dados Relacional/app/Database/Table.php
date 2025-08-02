<?php

namespace App\Database;

use App\DataStructures\BPlusTree\BPlusTree;

class Table
{
    public string $name;
    public array $schema;
    public BPlusTree $tree;

    public function __construct(string $name, array $schema)
    {
        $this->name = $name;
        $this->schema = $schema;
        $this->tree = new BPlusTree();
    }

    public function insert(array $record): void
    {
        $primaryKey = $this->schema[0]['name'];
        $key = $record[$primaryKey];
        $this->tree->insert($key, $record);
    }

    public function search($key): ?array
    {
        return $this->tree->search($key);
    }

    public function all(): array
    {
        return $this->tree->all();
    }
}