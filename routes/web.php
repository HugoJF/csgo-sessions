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

// parar de usar active e usar closed_at
// corrigir attackersteam no pipeline
// adicionar job para finalizacao de sessoes stale
// adicionar sentry
// adicionar suporte pro servidor 2 e 3
// divulgar
// google analytics
// production
// terminar testes (travis?)
// o que acontece com map change?
// docs + comments
// full object storage?

Route::get('/', 'SessionController@index')->name('sessions.index');
Route::get('/sessions/random', 'SessionController@random')->name('sessions.random');
Route::get('/sessions/search', 'SessionController@search')->name('sessions.search');
Route::get('/sessions/{session}/raw', 'SessionController@raw')->name('sessions.show');
Route::get('/sessions/{session}', 'SessionController@show')->name('sessions.show');

Route::get('search', function () {
	return view('search');
})->name('search');
Route::get('ui', function () {
	return view('ui');
});