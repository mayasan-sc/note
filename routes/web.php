<?php


use Illuminate\Http\Request;
use App\Page;


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

Route::get('/','PagesController@index')->name('index');

Route::get('/pages','PagesController@pages')->name('pages');
Route::post('/pages','PagesController@store')->name('store');

Route::post('/pages/edit/{id}', 'PagesController@edit')->name('edit');

Route::delete('/pages/delete/{id}', 'PagesController@delete')->name('delete');
