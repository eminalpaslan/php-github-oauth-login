<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // Login olduysa direkt hoşgeldin'e
    if (session()->has('user')) {
        return redirect('/hosgeldin');
    }
    return view('welcome'); // buradan "Giriş Yap" butonu ile /auth/redirect'e gidersin
});

// Giriş başlatma (login butonuna tıklayınca)
Route::get('/auth/redirect', 'Auth\OidcController@redirect')->name('login');

// Callback (provider giriş sonrası buraya yönlendirir)
// SİLME: Route::get('/signin-busign', 'Auth\OidcController@callback');
Route::match(['GET','POST'], '/signin-busign', 'Auth\OidcController@callback');


// Giriş sonrası sayfa (session kontrolü)
Route::get('/hosgeldin', function () {
    if (!session()->has('user')) {
        return redirect('/'); // login değilse ana sayfaya
    }
    return view('hosgeldin', ['user' => session('user')]);
});

// Çıkış (provider+lokal)
Route::post('/logout', 'Auth\OidcController@logout')->name('logout');

