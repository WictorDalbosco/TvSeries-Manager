<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\SeriesController;
use App\Http\Controllers\Api\AuthController;
use App\Models\Episode;
use App\Models\Series;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function(){

    // Listar todas as séries
    Route::apiResource('/series',SeriesController::class);

    // Fazer upload de uma capa
    Route::post('/series/upload', [SeriesController::class, 'upload']);

    // Listar temporadas de uma série
    Route::get('/series/{series}/seasons', function(Series $series) {
        return $series->seasons;
    });

    // Listar episodios de uma série
    Route::get('/series/{series}/episodes', function(Series $series) {
        return $series->episodes;
    });

    //Marcar como visto
    Route::patch('/episodes/{episode}', function(Episode $episode, Request $request) {
        $episode->watched = $request->watched;
        $episode->save();

        return $episode;
    });
});


// Fazer login
Route::post('/login', [AuthController::class, 'login'] );


