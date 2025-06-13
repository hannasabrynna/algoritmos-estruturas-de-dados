<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Inertia\Inertia;

class VisitorController extends Controller
{
    public function index() {
           $visitors = Visitor::all();

    return Inertia::render('Visitors/Index', [
        'visitors' => $visitors
    ]);

    }

    public function store(Request $request) {
        $request->validate([
        'name' => 'required',
        'cpf' => 'required',
        'birth_date' => 'required|date',
        'email' => 'required|email|unique:visitors,email',
        'ticket_type' => 'required',
        'credit_card' => 'required',
    ]);

    Visitor::create($request->all());

    return redirect()->route('dashboard')->with('success', 'Visitor created!');
    }

    public function show($id) {
        return Visitor::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $visitor = Visitor::findOrFail($id);
        $visitor->update($request->all());
        return $visitor;
    }

    public function destroy($id) {
        Visitor::findOrFail($id)->delete();
        return response()->noContent();
    }
}
