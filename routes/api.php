<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemplateController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('create',[TemplateController::class,'create']);
Route::get('update',[TemplateController::class,'update']);
Route::get('delete',[TemplateController::class,'delete']);

Route::post('payload',[TemplateController::class,'payload']);



