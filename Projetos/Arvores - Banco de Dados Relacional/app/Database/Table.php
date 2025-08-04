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
        $primaryKey = $this->schema[0]['name'] ?? null;

        if (!$primaryKey || !isset($record[$primaryKey])) {
            throw new \InvalidArgumentException("Chave primária inválida ou ausente.");
        }

        $key = $record[$primaryKey];
        $this->tree->insert($key, $record);
    }

    public function search($key): ?array
    {
        return $this->tree->search($key);
    }


    public function all(): array
    {
        // $result = [];
        // $this->collectAll($this->tree->getRoot(), $result);
        // return $result;
        return $this->tree->getAll();
    }

    private function collectAll($node, &$result): void
    {
        if ($node->isLeaf) {
            foreach ($node->children as $child) {
                $result[] = $child;
            }
        } else {
            foreach ($node->children as $child) {
                $this->collectAll($child, $result);
            }
        }
    }

    public function delete($key): void
    {
        $this->tree->delete($key);
    }

    public function update($key, array $newData): void
    {
        $this->tree->update($key, $newData);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSchema(): array
    {
        return $this->schema;
    }


    public function filter($field, $operator, $value): array
    {
        $results = [];

        foreach ($this->all() as $record) {
            if (!isset($record[$field])) continue;

            $recordValue = $record[$field];

            // converter tipo do valor para comparar corretamente
            $schemaField = collect($this->schema)->firstWhere('name', $field);
            if ($schemaField) {
                $type = $schemaField['type'];
                $value = match ($type) {
                    'int' => (int) $value,
                    'float' => (float) $value,
                    'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
                    default => $value, // string ou outros
                };
            }

            $match = match ($operator) {
                '='  => $recordValue == $value,
                '!=' => $recordValue != $value,
                '>'  => $recordValue > $value,
                '<'  => $recordValue < $value,
                '>=' => $recordValue >= $value,
                '<=' => $recordValue <= $value,
                'contains' => is_string($recordValue) && str_contains(strtolower($recordValue), strtolower($value)),
                default => false,
            };

            if ($match) {
                $results[] = $record;
            }
        }

        return $results;
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
