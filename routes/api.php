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

$api->get('vendedores', 'App\Http\Controllers\Api\SalesmenController@index');
$api->post('vendedores', 'App\Http\Controllers\Api\SalesmenController@store');

$api->get('vendas', 'App\Http\Controllers\Api\SalesController@index');
$api->post('vendas', 'App\Http\Controllers\Api\SalesController@store');
