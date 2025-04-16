<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\OneCriticPerFilmPerUser;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//Routes du TP2 ici : 
Route::post('/film', 'App\Http\Controllers\FilmController@create')->middleware('auth:sanctum')->middleware(IsAdmin::class);
Route::put('/films/{id}', 'App\Http\Controllers\FilmController@update')->middleware('auth:sanctum')->middleware(IsAdmin::class);
Route::delete('/films/{id}', 'App\Http\Controllers\FilmController@delete')->middleware('auth:sanctum')->middleware(IsAdmin::class);
Route::post('/critic', 'App\Http\Controllers\CriticController@create')->middleware('auth:sanctum')->middleware(OneCriticPerFilmPerUser::class);
Route::get('/user/{id}', 'App\Http\Controllers\UserController@show');

Route::middleware('throttle:5,1')->group( function(){ 
    Route::post('/signin', 'App\Http\Controllers\AuthController@login');
    Route::post('/signup', 'App\Http\Controllers\AuthController@register');
    Route::post('/signout', 'App\Http\Controllers\AuthController@logout')->middleware('auth:sanctum');
});