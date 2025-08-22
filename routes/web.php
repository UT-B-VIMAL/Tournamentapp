<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('tournament');
});

Route::get('/tournament', function () {
    return view('tournament');
});
Route::get('/', function () {
    return view('tournament');
});
Route::get('/tournamenttypelist', function () {
    return view('tournamenttypeList');
});
Route::get('/tournamentMode', function () {
    return view('tournamentMode');
});
Route::get('/tournamentmodeList', function () {
    return view('tournamentmodeList');
});
Route::get('/createTournament', function () {
    return view('create_tournament');
});
Route::get('/tournamentlist', function () {
    return view('tournamentList');
});
