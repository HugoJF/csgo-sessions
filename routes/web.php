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

// fechar sessoes ao comecar
// gerador de estatistica
// XXXXXXXXXX interface inicial
// "encerrador" de sessao
// normalizacao das steamid3
// corrigir attackersteam no pipeline
// interface de busca de steamid
// aumentar file do redis
// adicionar job para finalizacao de sessoes stale
// adicionar scheduler no RC
// expiring keys no redis?
// normalizar nome das partes

Route::get('/', 'SessionController@index')->name('sessions.index');
Route::get('/sessions/{session}', 'SessionController@show')->name('sessions.show');
Route::get('ui', function () {
	return view('ui');
});