<?php
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('view:cache');
    return 'DONE'; //Return anything
});

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
    return view('welcome');
});

Auth::routes();
Route::get('verifikasi-email/{token}','Auth\RegisterController@confirmAccount');

Route::get('notifikasi', 'NotifikasiController@index')->name('notifikasi.index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// MAIL_DRIVER=smtp
// MAIL_HOST=mail.kapcake.com
// MAIL_PORT=465
// MAIL_USERNAME=no-reply@kapcake.com
// MAIL_PASSWORD=NFNLS0mqkTN
// MAIL_ENCRYPTION=ssl