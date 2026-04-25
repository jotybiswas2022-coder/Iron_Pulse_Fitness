<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    // Show slider management form
    public function index()
    {
        $slider = Slider::latest()->first(); // Show latest slider
        return view('backend.sliders', compact('slider'));
    }

    // Store or update slider
    public function store(Request $request)
    {
        $request->validate([
            'slider1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'slider2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $slider = Slider::latest()->first();

        if (!$slider) {
            // Create new slider if none exists
            $slider = new Slider();
        }

        // Handle slider1
        if ($request->hasFile('slider1')) {
            if ($slider->slider1 && Storage::disk('public')->exists($slider->slider1)) {
                Storage::disk('public')->delete($slider->slider1);
            }
            $slider->slider1 = $request->file('slider1')->store('sliders', 'public');
        }

        // Handle slider2
        if ($request->hasFile('slider2')) {
            if ($slider->slider2 && Storage::disk('public')->exists($slider->slider2)) {
                Storage::disk('public')->delete($slider->slider2);
            }
            $slider->slider2 = $request->file('slider2')->store('sliders', 'public');
        }

        $slider->save();

        return redirect()->back()->with('success', 'Slider updated successfully!');
    }

    // Optional: Delete slider
    public function delete($id)
    {
        $slider = Slider::findOrFail($id);

        if ($slider->slider1 && Storage::disk('public')->exists($slider->slider1)) {
            Storage::disk('public')->delete($slider->slider1);
        }

        if ($slider->slider2 && Storage::disk('public')->exists($slider->slider2)) {
            Storage::disk('public')->delete($slider->slider2);
        }

        $slider->delete();

        return redirect()->back()->with('success', 'Slider deleted successfully!');
    }
}