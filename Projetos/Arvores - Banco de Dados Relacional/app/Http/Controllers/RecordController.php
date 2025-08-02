<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataStructures\BPlusTree\BPlusTree;
use Inertia\Inertia;
use App\Database\Table;
use App\Database\TableManager;
// use App\Services\StorageTableManager;

class RecordController extends Controller
{
    protected TableManager $manager;

    public function __construct()
    {
        $this->manager = new TableManager();
    }

    public function index(Request $request)
    {
        $tables = $this->manager->all();
        return Inertia::render('Record/Index', [
            'tables' => array_keys($tables),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'table' => 'required|string',
            'schema' => 'required|array',
            'record' => 'required|array',
        ]);

        $table = $this->manager->load($data['table']);

        if (!$table) {
            $table = new Table($data['table'], $data['schema']);
        }

        $table->insert($data['record']);
        $this->manager->save($table);

        return back()->with('message', 'Registro inserido com sucesso.');
    }

    public function show(string $table)
    {
        $tableObj = $this->manager->load($table);

        if (!$tableObj) {
            return back()->withErrors(['message' => 'Tabela não encontrada']);
        }

        return Inertia::render('Record/Show', [
            'table' => $table,
            'schema' => $tableObj->schema,
            'records' => $tableObj->all(),
        ]);
    }

   public function destroy(Request $request)
{
    $data = $request->validate([
        'table' => 'required|string',
        'key' => 'required',
    ]);

    $table = $this->manager->load($data['table']);

    if (!$table) {
        return back()->withErrors(['Tabela não encontrada']);
    }

    if ($this->manager->hasDependencies($data['table'], $data['key'])) {
        return back()->withErrors(['Registro não pode ser excluído: dependências encontradas']);
    }

    $table->delete($data['key']);
    $this->manager->save($table);

    return back()->with('message', 'Registro removido com sucesso');
}


public function update(Request $request)
{
    $data = $request->validate([
        'table' => 'required|string',
        'key' => 'required',
        'record' => 'required|array',
    ]);

    $tables = $this->getTables();
    $table = $tables[$data['table']] ?? null;

    if (!$table) {
        return back()->withErrors(['Tabela não encontrada']);
    }

    $table->update($data['key'], $data['record']);
    $this->saveTables($tables);

    return back()->with('message', 'Registro atualizado com sucesso');
}

private function getTableByName(string $name)
{
    $manager = new TableManager(); // instância do gerenciador
    $tables = $manager->all();     // carrega todas as tabelas existentes

    foreach ($tables as $table) {
        if ($table->getName() === $name) {
            return $table;
        }
    }

    return null;
}

public function filter(Request $request)
{
    $data = $request->validate([
        'table' => 'required|string',
        'field' => 'required|string',
        'operator' => 'required|string', // =, >, <, >=, <=, !=, contains
        'value' => 'required',
    ]);

    $table = $this->getTableByName($data['table']);

    if (!$table) {
        return back()->withErrors(['Tabela não encontrada']);
    }

    $results = $table->filter($data['field'], $data['operator'], $data['value']);

    return Inertia::render('Records/Filtered', [
        'table' => $data['table'],
        'records' => $results,
    ]);
}


public function showFilterPage($table)
{
    $tabela = $this->getTableByName($table);

    if (!$tabela) {
        return back()->withErrors(['Tabela não encontrada']);
    }

    return Inertia::render('Records/FilterForm', [
        'tableName' => $table,
        'fields' => $tabela->getSchema(), //pra mostrar os campos disponíveis
    ]);
}


}