<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->q;

        $products = Product::where('name', 'LIKE', "%$query%")
            ->orWhereHas('ProductCategory', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%");
            })
            ->latest()
            ->paginate(12);

        return view('frontend.search', compact('products', 'query'));
    }
}