<?php

use Illuminate\Support\Facades\Route;

Route::get('/dev', fn() => view('welcome'));
Route::get('/dev/welcome', fn() => view('welcome'));

Route::get('/stg', fn() => view('welcome'));
Route::get('/stg/welcome', fn() => view('welcome'));

Route::get('/dev/{any}', function ($any) {
    return redirect("/{$any}");
})->where('any', '.*');

Route::get('/stg/{any}', function ($any) {
    return redirect("/{$any}");
})->where('any', '.*');

// ALB ヘルスチェック用ルート（HTTP 200を返す）
Route::get('/health', function () {
    return response('OK', 200);
});
