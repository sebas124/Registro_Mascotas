<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['cors', 'authentication']], function () {

});
