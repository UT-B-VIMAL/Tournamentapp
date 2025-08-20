<?php

use Illuminate\Support\Facades\Route;

Route::get('/tournament', function () {
    return view('tournament');
});
Route::get('/createTournament', function () {
    return view('create_tournament');
});
