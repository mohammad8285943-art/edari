<?php

use App\Http\Controllers\Auth\login;
use App\Http\Controllers\OrphanController;
use App\Livewire\Guarantee\Index;
use App\Livewire\Widow\Index as WidowIndex;
use App\Livewire\Main;
use App\Livewire\OrphanForm;
use App\Livewire\OrphanIndex;
use App\Livewire\OrphanShow;
use App\Livewire\UserIndex;
use App\Livewire\UserPermissions;
use Illuminate\Support\Facades\Route;
use App\Livewire\Reports\OrphanReport;
use App\Livewire\Aid\Index as AidIndex;
use App\Livewire\Aid\Show as AidShow;
use App\Livewire\Aid\Nomination as AidNomination;
use App\Livewire\ActiveSessionsManager;
use App\Livewire\BeneficiarySearch;
use App\Livewire\Profile;

Route::view('/', 'cv')->name('cv');

Route::get('/login', [login::class, 'index'])->name('login');
Route::post('/login', [login::class, 'login']);
Route::get('/logout', [login::class, 'logout'])->name('logout');
    Route::livewire('/profile', Profile::class)->name('profile');


Route::group(['middleware' => ['auth']], function () {

    Route::livewire('/dashboard', Main::class)->name('main');

    Route::group(['middleware' => ['role:User.Administration']], function () {
        Route::livewire('/users/create', UserIndex::class)->name('users');
        Route::livewire('/users/permissions/{id}', UserPermissions::class)->name('users.permissions');
            Route::livewire('/users/active', ActiveSessionsManager::class)->name('users.active');

    });
    Route::get('/search', BeneficiarySearch::class)->name('search');

    Route::group(['middleware' => ['permission:orphan.view']], function () {
        Route::livewire('/orphan', OrphanIndex::class)->name('orphan.index');
        Route::livewire('/orphans/create', OrphanForm::class)->name('orphans.create');
        Route::livewire('/orphans/edit/{id}', OrphanForm::class)->name('orphans.edit');
        Route::livewire('/orphans/show/{id}', OrphanShow::class)->name('orphans.show');
        Route::livewire('/orphans/guarantee', Index::class)->middleware('permission:orphan.guarantees')->name('orphans.guarantee');
        Route::livewire('/orphans/widows', WidowIndex::class)->middleware('permission:orphan.widows')->name('orphans.widows');
        Route::livewire('/orphans/reports', OrphanReport::class)->middleware('permission:orphan.reports')->name('orphans.reports');

        Route::middleware(['auth'])->group(function () {
            Route::livewire('/aids', AidIndex::class)->name('orphans.aids.index');
            Route::livewire('/aids/{aid}', AidShow::class)->name('orphans.aids.show');
            Route::livewire('/aids/{aid}/nomination', AidNomination::class)->name('orphans.aids.nomination');
        });
    });

});
