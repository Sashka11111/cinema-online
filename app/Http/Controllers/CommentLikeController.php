<?php

namespace Liamtseva\Cinema\Http\Controllers;

use Liamtseva\Cinema\Http\Requests\StoreCommentLikeRequest;
use Liamtseva\Cinema\Http\Requests\UpdateCommentLikeRequest;
use Liamtseva\Cinema\Models\CommentLike;

class CommentLikeController extends Controller
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
    public function store(StoreCommentLikeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CommentLike $commentLike)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CommentLike $commentLike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentLikeRequest $request, CommentLike $commentLike)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommentLike $commentLike)
    {
        //
    }
}
