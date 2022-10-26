<?php

use Illuminate\Support\Facades\Route;

use splittlogic\gap\Http\Controllers\gapController;
use splittlogic\gap\Http\Controllers\gapAdminController;

/*
|--------------------------------------------------------------------------
| Routes must be passed through the web middleware to allow for
|   validation errors and flash messages to be displayed.
|--------------------------------------------------------------------------
*/

Route::get(
  'splittlogic/gap',
  [gapController::class, 'index']
)->name('splittlogic.gap');

// Admin Routes
Route::group(['middleware' => ['web', 'admin', 'auth']], function(){

  // Dashboard
  Route::get(
    'admin',
    [gapAdminController::class, 'index']
  )->name('gap.Admin');

});
