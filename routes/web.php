<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatusController;

Route::get('/', [StatusController::class, 'index']);
