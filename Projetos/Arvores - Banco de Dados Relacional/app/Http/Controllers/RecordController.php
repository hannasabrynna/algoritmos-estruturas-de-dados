<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataStructures\BPlusTree\BPlusTree;
use Inertia\Inertia;

class RecordController extends Controller
{
    private static ?BPlusTree $tree = null;

    public function __construct()
    {
        if (is_null(self::$tree)) {
            self::$tree = new BPlusTree(); // você pode ajustar o maxKeys se quiser
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'table' => 'required|string',
            'schema' => 'required|array',
            'record' => 'required|array',
        ]);

        // usa a primeira coluna como chave (você pode alterar isso)
        $primaryKey = $data['schema'][0]['name'];
        $key = $data['record'][$primaryKey];
        $value = $data['record'];

        self::$tree->insert($key, $value);

        return back()->with('message', 'Registro inserido com sucesso');
    }
}
