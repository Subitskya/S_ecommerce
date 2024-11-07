<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index(){
        $products = Product::all();
        return view('admin.products.dashboard', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create'); 
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        // Handle the image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validatedData['image_url'] = Storage::url($path);
        }

        Product::create($validatedData);

        return redirect()->route('admin.products.dashboard')->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        // Handle the image upload if a new image is provided
        if ($request->hasFile('image')) {
            // Optionally delete the old image from storage
            if ($product->image_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $product->image_url));
            }
            $path = $request->file('image')->store('products', 'public');
            $validatedData['image_url'] = Storage::url($path);
        }

        $product->update($validatedData);

        return redirect()->route('admin.products.dashboard')->with('success', 'Product updated successfully');
    }
    public function destroy(Product $product)
    {
        // Optionally delete the image from storage
        if ($product->image_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $product->image_url));
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.dashboard')->with('success', 'Product deleted successfully');
    }
}
