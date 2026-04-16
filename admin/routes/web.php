<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProducerController;
use App\Http\Controllers\SiteTextController;
use App\Http\Controllers\WineController;
use App\Models\Note;
use App\Models\Producer;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'home'])->name('front.home');
Route::get('/productores', [FrontController::class, 'producers'])->name('front.producers.index');
Route::get('/productores/{producer:slug}', [FrontController::class, 'producerDetail'])->name('front.producers.show');
Route::get('/notas', [FrontController::class, 'notes'])->name('front.notes.index');
Route::get('/notas/{note:slug}', [FrontController::class, 'noteDetail'])->name('front.notes.show');
Route::get('/sitemap.xml', function () {
    return response()
        ->view('front.sitemap', [
            'producers' => Producer::query()
                ->select(['slug', 'updated_at'])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'notes' => Note::query()
                ->published()
                ->select(['slug', 'updated_at', 'published_at'])
                ->orderByDesc('published_at')
                ->get(),
        ])
        ->header('Content-Type', 'application/xml; charset=UTF-8');
})->name('sitemap');

Route::prefix('adminppch1s')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

        Route::post('/producers/reorder', [ProducerController::class, 'reorder'])->name('producers.reorder');
        Route::post('/wines/reorder', [WineController::class, 'reorder'])->name('wines.reorder');
        Route::post('/menu-items/reorder', [MenuItemController::class, 'reorder'])->name('menu-items.reorder');
        Route::post('/notes/editor-image', [NoteController::class, 'storeEditorImage'])->name('notes.editor-image.store');
        Route::get('/nosotros', [SiteTextController::class, 'editAbout'])->name('site-texts.about.edit');
        Route::put('/nosotros', [SiteTextController::class, 'updateAbout'])->name('site-texts.about.update');
        Route::resource('producers', ProducerController::class)->except(['show']);
        Route::resource('attributes', AttributeController::class)->except(['show']);
        Route::resource('wines', WineController::class)->except(['show']);
        Route::resource('notes', NoteController::class)->except(['show']);
        Route::resource('menu-items', MenuItemController::class)->except(['show']);
    });
});
