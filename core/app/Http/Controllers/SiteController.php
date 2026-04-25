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

    // Pack detail page
    function pack($id)
    {

        $pack = Pack::findOrFail($id);

        $otherPacks = Pack::where('category_id', $pack->category_id)
                                ->where('id', '!=', $id)
                                ->inRandomOrder()
                                ->limit(8)
                                ->get();

        return view('frontend.pack', compact('pack', 'otherPacks'));
    }
}