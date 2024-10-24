<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::all();
        return response()->json($properties);
    }

    public function store(Request $request)
    {
        $property = new Property();
        $property->title = $request->input('title');
        $property->description = $request->input('description');
        $property->price = $request->input('price');
        $property->location = $request->input('location');
        $property->save();
        return response()->json($property, 201);
    }

    public function show($id)
    {
        $property = Property::find($id);
        if (!$property) {
            return response()->json(['message' => 'Property not found'], 404);
        }
        return response()->json($property);
    }

    public function update(Request $request, $id)
    {
        $property = Property::find($id);
        if (!$property) {
            return response()->json(['message' => 'Property not found'], 404);
        }
        $property->title = $request->input('title');
        $property->description = $request->input('description');
        $property->price = $request->input('price');
        $property->location = $request->input('location');
        $property->save();
        return response()->json($property);
    }

    public function destroy($id)
    {
        $property = Property::find($id);
        if (!$property) {
            return response()->json(['message' => 'Property not found'], 404);
        }
        $property->delete();
        return response()->json(['message' => 'Property deleted']);
    }
}
