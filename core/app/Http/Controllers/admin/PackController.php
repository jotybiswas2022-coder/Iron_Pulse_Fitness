<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class PackController extends Controller
{
    // Admin: List all Packs
    public function index()
    {
        return view('backend.pack.index');
    }

    // Admin: Show create form
    public function create()
    {
        $categories = Category::all();
        return view('backend.pack.create', compact('categories'));
    }

    // Admin: Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'base_price'  => 'required|numeric|lte:price|min:0',
            'discount'    => 'nullable|numeric|min:0|max:100',
            'stock'       => 'required|integer|min:0',
            'details'     => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ], [
            'base_price.lte' => 'Base price cannot be greater than product price.',
        ]);

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('product', 'public') : null;

        Product::create([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'base_price'  => $request->base_price,
            'price'       => $request->price,
            'discount'    => $request->discount ?? 0,
            'stock'       => $request->stock,
            'details'     => $request->details,
            'image'       => $imagePath,
        ]);

        return redirect('/admin/product')->with('success', 'Product added successfully!');
    }

    // Admin: Edit product
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('backend.product.edit', compact('product', 'categories'));
    }

    // Admin: Update product
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'base_price'  => 'required|numeric|lte:price|min:0',
            'discount'    => 'nullable|numeric|min:0|max:100',
            'stock'       => 'required|integer|min:0',
            'details'     => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ], [
            'base_price.lte' => 'Base price cannot be greater than product price.',
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'base_price'  => $request->base_price,
            'price'       => $request->price,
            'discount'    => $request->discount ?? 0,
            'stock'       => $request->stock,
            'details'     => $request->details,
        ]);

        // Handle image
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('product', 'public');
            $product->save();
        }

        return redirect('/admin/product')->with('success', 'Product updated successfully!');
    }

    // Admin: Delete product
    public function delete($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect('/admin/product')->with('success', 'Product deleted successfully!');
    }

}