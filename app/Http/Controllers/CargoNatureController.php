<?php

namespace App\Http\Controllers;

use App\Models\CargoNature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CargoNatureController extends Controller
{
    public function index()
    {
        $cargoNatures = CargoNature::paginate(10);
        return view('cargo-natures.index', compact('cargoNatures'));
    }

    public function active()
    {
        $cargoNatures = CargoNature::where('status', 1)->paginate(10);
        return view('cargo-natures.index', compact('cargoNatures'));
    }

    public function inactive()
    {
        $cargoNatures = CargoNature::where('status', 0)->paginate(10);
        return view('cargo-natures.index', compact('cargoNatures'));
    }

    public function create()
    {
        return view('cargo-natures.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:cargo_natures,name',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        CargoNature::create($request->only(['name', 'status']));

        return redirect()->route('cargo-natures.list')->with('success', 'Cargo nature created successfully.');
    }

    public function edit($id)
    {
        $cargoNature = CargoNature::findOrFail($id);
        return view('cargo-natures.edit', compact('cargoNature'));
    }

    public function update(Request $request, $id)
    {
        $cargoNature = CargoNature::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:cargo_natures,name,' . $id,
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cargoNature->update($request->only(['name', 'status']));

        return redirect()->route('cargo-natures.list')->with('success', 'Cargo nature updated successfully.');
    }

    public function destroy($id)
    {
        $cargoNature = CargoNature::findOrFail($id);
        $cargoNature->delete();
        return redirect()->route('cargo-natures.list')->with('success', 'Cargo nature deleted successfully.');
    }
}
