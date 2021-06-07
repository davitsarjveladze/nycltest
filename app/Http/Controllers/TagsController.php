<?php

namespace App\Http\Controllers;

use App\Models\Articles;
use App\Models\Tags;
use Illuminate\Http\Request;


class TagsController extends Controller
{
    //
    public function getTSortedTags(Request $request): \Illuminate\Http\JsonResponse
    {
        $sortingData = $request->validate([
            'sort' => 'String',
            'order' => 'String',
        ]);
        return response()->json(Tags::getSortedTags($sortingData));
    }

}
