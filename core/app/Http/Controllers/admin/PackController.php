<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Pack;
use Illuminate\Support\Facades\Storage;

class PackController extends Controller
{
    // 📌 List all packs
    public function index()
    {
        $packs = Pack::latest()->get();
        $categories = Category::all();
        return view('backend.pack.index', compact('packs', 'categories'));
    }

    // 📌 Show create form
    public function create()
    {
        $categories = Category::all();
        return view('backend.pack.create', compact('categories'));
    }

    // 📌 Store new pack
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'type'        => 'required|in:basic,standard,premium',
            'total_cost'  => 'required|numeric|min:0',
            'pack_price'  => 'required|numeric|min:0|gte:total_cost',
            'discount'    => 'nullable|numeric|min:0|max:100',
            'details'     => 'nullable|string',
            'image'       => 'required|image|max:2048',
        ], [
            'pack_price.gte' => 'Pack price must be greater than or equal to total cost.',
        ]);

        // Upload Image
        $imagePath = $request->file('image')->store('pack', 'public');

        Pack::create([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'type'        => $request->type,
            'total_cost'  => $request->total_cost,
            'pack_price'  => $request->pack_price,
            'discount'    => $request->discount ?? 0,
            'details'     => $request->details,
            'image'       => $imagePath,
        ]);

        return redirect('/admin/pack')->with('success', 'Pack added successfully!');
    }

    // 📌 Edit pack
    public function edit($id)
    {
        $pack = Pack::findOrFail($id);
        $categories = Category::all();
        return view('backend.pack.edit', compact('pack', 'categories'));
    }

    // 📌 Update pack
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'type'        => 'required|in:basic,standard,premium',
            'total_cost'  => 'required|numeric|min:0',
            'pack_price'  => 'required|numeric|min:0|gte:total_cost',
            'discount'    => 'nullable|numeric|min:0|max:100',
            'details'     => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ], [
            'pack_price.gte' => 'Pack price must be greater than or equal to total cost.',
        ]);

        $pack = Pack::findOrFail($id);

        $pack->update([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'type'        => $request->type,
            'total_cost'  => $request->total_cost,
            'pack_price'  => $request->pack_price,
            'discount'    => $request->discount ?? 0,
            'details'     => $request->details,
        ]);

        // Image update
        if ($request->hasFile('image')) {
            if ($pack->image && Storage::disk('public')->exists($pack->image)) {
                Storage::disk('public')->delete($pack->image);
            }

            $pack->image = $request->file('image')->store('pack', 'public');
            $pack->save();
        }

        return redirect('/admin/pack')->with('success', 'Pack updated successfully!');
    }

    // 📌 Delete pack
    public function delete($id)
    {
        $pack = Pack::findOrFail($id);

        if ($pack->image && Storage::disk('public')->exists($pack->image)) {
            Storage::disk('public')->delete($pack->image);
        }

        $pack->delete();

        return redirect('/admin/pack')->with('success', 'Pack deleted successfully!');
    }
}