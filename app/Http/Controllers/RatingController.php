<?php

namespace Liamtseva\Cinema\Http\Controllers;

use Liamtseva\Cinema\Http\Requests\StoreRatingRequest;
use Liamtseva\Cinema\Http\Requests\UpdateRatingRequest;
use Liamtseva\Cinema\Models\Rating;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRatingRequest $request)
    {
        $existingRating = Rating::where('user_id', auth()->id())
            ->where('movie_id', $request->movie_id)
            ->first();

        if ($existingRating) {
            return redirect()->back()->with('error', 'Ви вже залишили відгук для цього фільму!');
        }

        Rating::create([
            'user_id' => auth()->id(),
            'movie_id' => $request->movie_id,
            'number' => $request->number,
            'review' => $request->review,
        ]);

        return redirect()->back()->with('success', 'Ваш відгук додано!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rating $rating)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRatingRequest $request, Rating $rating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating)
    {
        //
    }
}
