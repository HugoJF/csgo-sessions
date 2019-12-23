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

// normalizacao das steamid3
// corrigir attackersteam no pipeline
// interface de busca de steamid
// aumentar file do redis
// adicionar job para finalizacao de sessoes stale
// adicionar sentry
// logs de performance
// tracking de kills
// tracking de deaths
// adicionar tempo na metrica
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
Route::get('/sessions/{session}', 'SessionController@show')->name('sessions.show');
Route::get('search', function () {
	return view('search');
})->name('search');
Route::get('ui', function () {
	return view('ui');
});