<?php

use App\Models\Listing;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;

Route::get('/', [ListingController::class,'index']);

//show create form
Route::get('/listings/create',[ListingController::class, 'create'])->middleware('auth');
 
//store Listing data
Route::post('/listings', [ListingController::class, 'store'])->middleware('auth');

//show edit form

Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');
Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth');

Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');


//search
Route::get('/search', [ListingController::class, 'searchJobs']);

// single Listing
Route::get('/listings/{listing}',[ListingController::class,'show']);


//show register/create form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

//create new user
Route::post('/users', [UserController::class, 'store']);


//log user out

Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

//show login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

//log in
Route::post('/users/authenticate',[UserController::class, 'authenticate']);

// Manage Listing
