<?php

namespace App\Database;

use App\DataStructures\BPlusTree\BPlusTree;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Database\Table;


class TableManager
{
    private string $directory = 'bplus_tables';

    public function __construct()
    {
        if (!Storage::exists($this->directory)) {
            Storage::makeDirectory($this->directory);
        }
    }

    private function getFilePath(string $tableName): string
    {
        return "{$this->directory}/{$tableName}.table";
    }

    public function save(Table $table): void
    {
        Storage::put($this->getFilePath($table->name), serialize($table));
    }

    public function load(string $tableName): ?Table
    {
        $path = $this->getFilePath($tableName);
        if (!Storage::exists($path)) {
            return null;
        }
        return unserialize(Storage::get($path));
    }

    public function delete(string $tableName): void
    {
        Storage::delete($this->getFilePath($tableName));
    }

    public function all(): array
    {
        $tables = [];
        foreach (Storage::files($this->directory) as $file) {
            $content = Storage::get($file);
            $table = unserialize($content);
            $tables[$table->name] = $table;
        }
        return $tables;
    }

    public function hasDependencies(string $tableName, $key): bool
    {
        foreach ($this->tables as $name => $table) {
            foreach ($table->getSchema() as $col => $def) {
                if (isset($def['foreign']) && $def['foreign']['table'] === $tableName) {
                    $records = $table->getAllRecords();
                    foreach ($records as $record) {
                        if ($record[$col] == $key) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
}

}

