<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('tournamenttypelist');
});

Route::get('/tournament', function () {
    return view('tournament');
});
Route::get('/edit-tournament', function () {
    return view('edit_tournament');
});
Route::get('/tournamenttypelist', function () {
    return view('tournamenttypeList');
});
Route::get('/tournamentMode', function () {
    return view('create_tournament_mode');
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
Route::get('/edit-tournament-mode/{id}', function ($id) {
    return view('edit_tournament_mode', ['id' => $id]);
});
