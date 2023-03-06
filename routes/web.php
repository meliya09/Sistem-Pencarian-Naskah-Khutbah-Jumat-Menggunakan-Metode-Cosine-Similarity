<?php

use App\Http\Controllers\CosineSimilarityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ImportDocumentController;
use App\Models\Temp;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::get('/cosinesimilarity',DashboardController::class)->name('cosinesimilarity');
    Route::post('/consinesimilarities', [CosineSimilarityController::class, 'check'])
    ->name('consinesimilarities.check');

    Route::resources([
        'documents' => DocumentController::class,
    ]);

    Route::get('/import-documents',ImportDocumentController::class)
    ->name('import-documents.store');
});

Route::get('/list-documents', function () {
    $temp = Temp::latest()->first();
    $payload = json_decode($temp['payload'], true);
    $data = [
        'documentAndQuery' => $payload['documentAndQuery'],
    ];
    return view('cosim.document', $data);
})->name('list-documents');

Route::get('/step-1', function () {
    $temp = Temp::latest()->first();
    $payload = json_decode($temp['payload'], true);
    $data = [
        'allTokenizing' => $payload['allTokenizing'],
    ];
    return view('cosim.step-1', $data);
})->name('step-1');

Route::get('/step-2', function () {
    $temp = Temp::latest()->first();
    $payload = json_decode($temp['payload'], true);
    $data = [
        'allFiltering' => $payload['allFiltering'],
    ];
    return view('cosim.step-2', $data);
})->name('step-2');

Route::get('/step-3', function () {
    $temp = Temp::latest()->first();
    $payload = json_decode($temp['payload'], true);
    $data = [
        'allStemming' => $payload['allStemming'],
    ];
    return view('cosim.step-3', $data);
})->name('step-3');

Route::get('/step-4', function () {
    $temp = Temp::latest()->first();
    $payload = json_decode($temp['payload'], true);
    $data = [
        'allTokenizing' => $payload['allTokenizing'],
        'terms' => $payload['terms'],
    ];
    return view('cosim.step-4', $data);
})->name('step-4');

Route::get('/step-5', function () {
    $temp = Temp::latest()->first();
    $payload = json_decode($temp['payload'], true);
    $data = [
        'allTokenizing' => $payload['allTokenizing'],
        'sumCrossVector' => $payload['sumCrossVector'],
        'terms' => $payload['terms'],
    ];
    return view('cosim.step-5', $data);
})->name('step-5');

Route::get('/step-6', function () {
    $temp = Temp::latest()->first();
    $payload = json_decode($temp['payload'], true);
    $data = [
        'allTokenizing' => $payload['allTokenizing'],
        'terms' => $payload['terms'],
        'sqrtCs' => $payload['sqrtCs'],
    ];
    return view('cosim.step-6', $data);
})->name('step-6');



Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');
