<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/locales', function (Request $request) {
        $languages = [
            'en' => [], 'fr' => [],
        ];

        foreach ($languages as $language => $strings) {
            $files = glob(resource_path('lang/' . $language . '/*.php'));

            foreach ($files as $file) {
                $name = basename($file, '.php');
                $languages[$language][$name] = require $file;
            }
        }

        return $languages;
    });
});
