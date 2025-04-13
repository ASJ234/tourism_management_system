<?php

namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $query = Destination::query()->where('is_active', true);

        // Apply region filter if selected
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        $destinations = $query->with(['packages' => function($query) {
            $query->where('is_active', true);
        }])
        ->withCount('reviews')
        ->withAvg('reviews', 'rating')
        ->latest()
        ->paginate(12);

        return view('tourist.destinations', compact('destinations'));
    }

    public function show(Destination $destination)
    {
        $destination->load(['packages' => function($query) {
            $query->where('is_active', true);
        }, 'reviews.user', 'images']);

        return view('tourist.destinations.show', compact('destination'));
    }
} 