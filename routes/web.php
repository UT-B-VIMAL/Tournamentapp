<?php

use Illuminate\Support\Facades\Route;

Route::get('/tournament', function () {
    return view('tournament');
});
Route::get('/tournamentMode', function () {
    return view('tournamentMode');
});
Route::get('/createTournament', function () {
    return view('create_tournament');
});
