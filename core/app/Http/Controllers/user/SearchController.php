<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pack;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->q;

        $packs = Pack::where('name', 'LIKE', "%$query%")
            ->orWhereHas('PackCategory', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%");
            })
            ->latest()
            ->paginate(12);

        return view('frontend.search', compact('packs', 'query'));
    }
}