<?php

use App\Http\Controllers\Auth\login;
use App\Livewire\Main;
use App\Livewire\OrphanIndex;
use App\Livewire\UserIndex;
use App\Livewire\UserPermissions;
use Illuminate\Support\Facades\Route;

Route::view('/', 'cv')->name('cv');

Route::get('/login', [login::class, 'index'])->name('login');
Route::post('/login', [login::class, 'login']);
Route::get('/logout', [login::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () {
    Route::livewire('/dashboard', Main::class)->name('main');

    Route::group(['middleware' => ['role:User.Administration']], function () {
        Route::livewire('/users/create', UserIndex::class)->name('users');
        Route::livewire('/users/permissions/{id}', UserPermissions::class)->name('users.permissions');
    });

    Route::group(['middleware' => ['permission:orphan.view']], function () {
        Route::livewire('/orphan', OrphanIndex::class)->name('orphan.index');
    });

});
