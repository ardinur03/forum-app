<?php

use Illuminate\Support\Facades\Route;

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
    if (config('app.env') == 'local') {
        $textHtml = "<h1>LOCAL ENVIRONMENT</h1><p>Hello Everyone 👋, the API forum app has been released !</p>";
    } else if (config('app.env') == 'production') {
        $textHtml = "<h1>PRODUCTION ENVIRONMENT</h1><p>Hello Everyone 👋, the API forum app has been released !</p>";
    } else {
        $textHtml = "<h1>STAGING ENVIRONMENT</h1><p>Hello Everyone 👋, the API forum app has been released !</p>";
    }

    echo $textHtml;
});
