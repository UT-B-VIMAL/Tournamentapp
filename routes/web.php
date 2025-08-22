<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('tournamenttypelist');
});

Route::get('/tournament', function () {
    return view('tournament');
});
Route::get('/edit-tournament/{id}', function ($id) {
    return view('edit_tournament', ['id' => $id]);
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
