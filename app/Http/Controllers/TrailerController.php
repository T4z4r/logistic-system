<?php

namespace App\Http\Controllers;

use App\Models\Trailer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrailerController extends Controller
{
    public function index()
    {
        $trailers = Trailer::with('addedBy')->paginate(10);
        return view('trailers.index', compact('trailers'));
    }

    public function active()
    {
        $trailers = Trailer::with('addedBy')
            ->where('status', 1)
            ->paginate(10);
        return view('trailers.active', compact('trailers'));
    }

    public function inactive()
    {
        $trailers = Trailer::with('addedBy')
            ->where('status', 0)
            ->paginate(10);
        return view('trailers.inactive', compact('trailers'));
    }

    public function create()
    {
        $users = User::where('status', 1)->get();
        return view('trailers.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plate_number' => 'required|string|max:255|unique:trailers',
            'purchase_date' => 'required|date',
            'amount' => 'nullable|numeric|min:0',
            'capacity' => 'required|numeric|min:0',
            'manufacturer' => 'required|string|max:255',
            'length' => 'required|numeric|min:0',
            'trailer_type' => 'required|string|max:255',
            'status' => 'required|boolean',
            'added_by' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Trailer::create($request->only([
            'plate_number',
            'purchase_date',
            'amount',
            'capacity',
            'manufacturer',
            'length',
            'trailer_type',
            'status',
            'added_by',
        ]));

        return redirect()->route('trailers.list')->with('success', 'Trailer created successfully.');
    }

    public function edit($id)
    {
        $trailer = Trailer::findOrFail($id);
        $users = User::where('status', 1)->get();
        return view('trailers.edit', compact('trailer', 'users'));
    }

    public function update(Request $request, $id)
    {
        $trailer = Trailer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'plate_number' => 'required|string|max:255|unique:trailers,plate_number,' . $trailer->id,
            'purchase_date' => 'required|date',
            'amount' => 'nullable|numeric|min:0',
            'capacity' => 'required|numeric|min:0',
            'manufacturer' => 'required|string|max:255',
            'length' => 'required|numeric|min:0',
            'trailer_type' => 'required|string|max:255',
            'status' => 'required|boolean',
            'added_by' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $trailer->update($request->only([
            'plate_number',
            'purchase_date',
            'amount',
            'capacity',
            'manufacturer',
            'length',
            'trailer_type',
            'status',
            'added_by',
        ]));

        return redirect()->route('trailers.list')->with('success', 'Trailer updated successfully.');
    }

    public function destroy($id)
    {
        $trailer = Trailer::findOrFail($id);
        $trailer->delete();
        return redirect()->route('trailers.list')->with('success', 'Trailer deleted successfully.');
    }
}
