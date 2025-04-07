<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PageKiteApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/company-summary', [PageKiteApiController::class, 'companySummary'])->name('company-summary');

Route::post('/get-companies', [PageKiteApiController::class, 'getCompanies'])->name('get-companies');

Route::post('/companies-report', [PageKiteApiController::class, 'getCompanyReport'])->name('companies-report');

Route::post('/companies/findings/databreach', [PageKiteApiController::class, 'getCompanyDataBreach'])->name('get-company-data-breach');
