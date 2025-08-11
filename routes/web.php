<?php

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
Route::get('/hosgeldin', function () {
    return view('hosgeldin');
})->middleware('auth');
Route::get('/auth/github', 'Auth\LoginController@redirectToGithub');
Route::get('/auth/github/redirect', 'Auth\LoginController@redirectToGithub');
Route::get('/auth/github/callback', 'Auth\LoginController@handleGithubCallback');

Route::post('/logout', function() {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
});

Route::get('/home', function () {
    return view('hosgeldin');
});
