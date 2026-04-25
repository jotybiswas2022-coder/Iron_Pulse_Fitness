<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pack;

class SiteController extends Controller
{
    // Homepage
    function index()
    {
        $packs = Pack::all();
        return view('frontend.index', compact('packs'));
    }

    // Product detail page
    function product($id)
    {

        $product = Product::findOrFail($id);

        $otherProducts = Product::where('category_id', $product->category_id)
                                ->where('id', '!=', $id)
                                ->inRandomOrder()
                                ->limit(8)
                                ->get();

        return view('frontend.product', compact('product', 'otherProducts'));
    }
}