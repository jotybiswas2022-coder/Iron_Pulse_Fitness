<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SiteController extends Controller
{
    // Homepage
    function index()
    {
        $products = Product::all();
        return view('frontend.index', compact('products'));
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