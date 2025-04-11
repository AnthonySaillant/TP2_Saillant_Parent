<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//Routes du TP2 ici : 
Route::post('/film', 'App\Http\Controllers\FilmController@create');
Route::put('/films/{id}', 'App\Http\Controllers\FilmController@update');
Route::delete('/films/{id}', 'App\Http\Controllers\FilmController@delete');

Route::middleware('throttle:5,1')->group( function(){ 
    Route::post('/signin', 'App\Http\Controllers\AuthController@login');
    Route::post('/signup', 'App\Http\Controllers\AuthController@register');
    Route::post('/signout', 'App\Http\Controllers\AuthController@logout')->middleware('auth:sanctum');
});