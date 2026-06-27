<?php

use App\Http\Controllers\Auth\login;
use App\Livewire\Main;
use App\Livewire\UserIndex;
use Illuminate\Support\Facades\Route;

Route::view('/cv', 'cv')->name('cv');

Route::get('/login', [login::class, 'index'])->name('login');
Route::post('/login', [login::class, 'login']);
Route::get('/logout',[login::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () {
    Route::livewire('/dashboard', Main::class)->name('main');
    Route::livewire('/users', UserIndex::class)->name('users');
    Route::livewire('/user/permissions', UserIndex::class)->name('users.permissions');
});


