<?php

use Illuminate\Support\Facades\Route;

Route::get('/tournament', function () {
    return view('tournament');
});
<<<<<<< HEAD
Route::get('/tournamentMode', function () {
    return view('tournamentMode');
=======
Route::get('/createTournament', function () {
    return view('create_tournament');
>>>>>>> b7ab888dee64c9e257712bd0cb4e5f788d5d94a8
});
