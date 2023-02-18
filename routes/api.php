<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/users/me', [UserController::class, 'me']);
Route::get('/users/autocomplete', [UserController::class, 'autocompleteSearch']);
Route::apiResource('users', UserController::class)->except(['update']);

Route::apiResource('courses', CourseController::class)->scoped(['course' => 'uuid']);

Route::post('/uploads/images', [UploadController::class, 'storeImage']);
