<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attraction;
use Inertia\Inertia;


class AttractionController extends Controller
{
    public function index() {
        $attractions = Attraction::all();

        return Inertia::render('Attractions/Index', [
        'attractions' => $attractions
    ]);

    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'capacity_per_time_slot' => 'required|integer|min:1',
            'available_time_slots' => 'required|array',
            'minimum_age' => 'required|integer|min:0',
            'has_priority_access' => 'boolean'
        ]);
        Attraction::create($request->all());
        return redirect()->route('dashboard')->with('success', 'Attraction created!');
    }

    public function show($id) {
        return Attraction::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $attraction = Attraction::findOrFail($id);
        $attraction->update($request->all());
        return $attraction;
    }

    public function destroy($id) {
        Attraction::findOrFail($id)->delete();
        return response()->noContent();
    }
}
