<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HostelController;


Route::get('/', function () {
    return view('welcome');
});

//BookingController
Route::get('/bookings', [BookingsController::class, 'book']);

//Admin Dashboard
Route::get('/admin',[AdminController::class,'dash']);

//Hostels Dashboard
Route::get('/hostels',[HostelController::class],'index');
