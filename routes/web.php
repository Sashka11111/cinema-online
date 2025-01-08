<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/selections', [SelectionController::class, 'index']);
//Route::get('/studios', [StudioController::class, 'index']);

Route::resource('studios', StudioController::class);
//Route::resource('movies.comments', CommentController::class);
