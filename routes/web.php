<?php

use App\Models\Review;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AccountController;



Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/book/{id}', [HomeController::class, 'detail'])->name('detail');
Route::post('/save-review', [HomeController::class, 'saveReview'])->name('saveReview');

Route::group(['prefix' => 'account'],function(){
    Route::group(['middleware' => 'guest'],function(){
        Route::get('register',[AccountController::class,'register'])->name('account.register');
        Route::post('register',[AccountController::class,'processRegister'])->name('account.processRegister');
        Route::get('login',[AccountController::class,'login'])->name('account.login');
        Route::post('authenticate',[AccountController::class,'authenticate'])->name('account.authenticate');
    });
    
    Route::group(['middleware' => 'auth'],function(){
        Route::get('profile',[AccountController::class,'profile'])->name('account.profile');        
        Route::get('logout',[AccountController::class,'logout'])->name('account.logout');        
        Route::post('profileUpdate',[AccountController::class,'profileUpdate'])->name('account.profileUpdate');        
        Route::get('my-reviews',[AccountController::class,'myreviews'])->name('account.my-reviews');        
        Route::get('my-reviews/{id}',[AccountController::class,'editReview'])->name('account.my-reviews.editReview');        
        Route::post('my-reviews/{id}',[AccountController::class,'updateReview'])->name('account.my-reviews.updateReview');        

        Route::get('books',[BookController::class, 'index'])->name('books.index');
        Route::get('books/create',[BookController::class, 'create'])->name('books.create');
        Route::post('books/store',[BookController::class, 'store'])->name('books.store');
        Route::get('books/edit/{id}',[BookController::class, 'edit'])->name('books.edit');
        Route::post('books/update/{id}',[BookController::class, 'update'])->name('books.update');
        Route::delete('books',[BookController::class, 'destroy'])->name('books.delete');

        Route::get('reviews',[ReviewController::class,'index'])->name('account.reviews');
        Route::get('reviews/{id}',[ReviewController::class,'edit'])->name('account.reviews.edit');
        Route::post('reviews/{id}',[ReviewController::class,'update'])->name('account.reviews.update');
        Route::delete('reviews',[ReviewController::class, 'destroy'])->name('reviews.delete');
    });
});