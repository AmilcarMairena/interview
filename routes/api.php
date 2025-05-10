<?php

use Illuminate\Support\Facades\Route;


Route::get('available-variables', [\App\Http\Controllers\SoapServiceController::class, 'availableVariables']);
Route::post('exchange-rate-range', [\App\Http\Controllers\SoapServiceController::class, 'exchangeRateRange']);

Route::get('exchange-rate', [\App\Http\Controllers\ExchangeDataController::class, 'index']);
