<?php

namespace App\Database;

use App\DataStructures\BPlusTree\BPlusTree;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Database\TableManager;

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

    public function delete($key): void
    {
        $this->tree->delete($key);
    }

    public function update($key, array $newData): void
    {
        $this->tree->update($key, $newData);
    }

    public function filter(string $field, string $operator, $value): array
{
    $all = $this->tree->getAll(); // ou outro método de acesso
    return array_filter($all, function ($record) use ($field, $operator, $value) {
        if (!isset($record[$field])) return false;

        switch ($operator) {
            case '=': return $record[$field] == $value;
            case '>': return $record[$field] > $value;
            case '<': return $record[$field] < $value;
            case '>=': return $record[$field] >= $value;
            case '<=': return $record[$field] <= $value;
            case '!=': return $record[$field] != $value;
            default: return false;
        }
    });
}

public function validateForeignKeys(array $record, TableManager $manager): bool
{
    foreach ($this->schema as $column => $definition) {
        if (isset($definition['foreign'])) {
            $foreignTable = $manager->getTable($definition['foreign']['table']);
            if (!$foreignTable) return false;

            $foreignKey = $definition['foreign']['column'];
            if (!$foreignTable->exists($record[$column])) return false;
        }
    }
    return true;
}

public function exists($key): bool
{
    return $this->tree->find($key) !== null;
}

}