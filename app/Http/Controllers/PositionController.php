<?php

// app/Http/Controllers/PositionController.php
namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::latest()->get();
        return view('positions.index', compact('positions'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        Position::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status ?? 1,
        ]);

        return back()->with('success', 'Position created successfully.');
    }

    public function update(Request $request, Position $position)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $position->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status ?? 1,
        ]);

        return back()->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return back()->with('success', 'Position deleted.');
    }
}
