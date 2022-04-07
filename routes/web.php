<?php

use App\Mail\DeltaEmail;
use Illuminate\Support\Facades\Auth;
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

/*
  GET|HEAD   login ....................................................................... login › Auth\LoginController@showLoginForm  
  POST       login ....................................................................................... Auth\LoginController@login  
  POST       logout ............................................................................ logout › Auth\LoginController@logout  
  GET|HEAD   password/confirm ..................................... password.confirm › Auth\ConfirmPasswordController@showConfirmForm  
  POST       password/confirm ................................................................ Auth\ConfirmPasswordController@confirm  
  POST       password/email ....................................... password.email › Auth\ForgotPasswordController@sendResetLinkEmail  
  GET|HEAD   password/reset .................................... password.request › Auth\ForgotPasswordController@showLinkRequestForm  
  POST       password/reset .................................................... password.update › Auth\ResetPasswordController@reset  
  GET|HEAD   password/reset/{token} ..................................... password.reset › Auth\ResetPasswordController@showResetForm  
*/

/** Painel Routes */
//Home
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Products
Route::get('/products', [App\Http\Controllers\HomeController::class, 'products'])->name('products');
Route::get('/product/{id}', [App\Http\Controllers\HomeController::class, 'product'])->name('product');

//Sales
Route::get('/sales', [App\Http\Controllers\HomeController::class, 'sales'])->name('sales');
Route::get('/sale/new', [App\Http\Controllers\HomeController::class, 'saleNew'])->name('sale.new');
Route::get('/sale/{id}', [App\Http\Controllers\HomeController::class, 'sale'])->name('sale');

//Products New
Route::post('/products/new', [App\Http\Controllers\HomeController::class, 'productsNew'])->name('products.new');

//Sale POST
Route::post('/sale/new', [App\Http\Controllers\HomeController::class, 'saleSave'])->name('sale.save');
Route::post('/sale/list', [App\Http\Controllers\HomeController::class, 'saleList'])->name('sale.list');
Route::post('/sale/delete', [App\Http\Controllers\HomeController::class, 'saleDelete'])->name('sale.delete');

//Sale Products POST
Route::post('/sale/product/getPrice', [App\Http\Controllers\HomeController::class, 'saleProductGetPrice'])->name('sale.product.getPrice');
Route::post('/sale/product/save', [App\Http\Controllers\HomeController::class, 'saleProductSave'])->name('sale.product.save');
Route::post('/sale/product/edit', [App\Http\Controllers\HomeController::class, 'saleProdutct'])->name('sale.product.edit');
Route::post('/sale/product/delete', [App\Http\Controllers\HomeController::class, 'saleProductDelete'])->name('sale.product.delete');

/**Auth Routes */
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::get('/password/confirm', [App\Http\Controllers\Auth\ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');

Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::post('password/confirm', [App\Http\Controllers\Auth\ConfirmPasswordController::class, 'confirm']);
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('update');

//email
Route::get('/email', function () {
    //\Illuminate\Support\Facades\Mail::send(new DeltaEmail);
});
