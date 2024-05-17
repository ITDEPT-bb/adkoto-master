<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', [HomeController::class, 'index'])
    // ->middleware(['auth', 'verified'])->name('home');
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/u/{user:username}', [ProfileController::class, 'index'])
    ->name('profile');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/profile/update-images', [ProfileController::class, 'updateImage'])
        ->name('profile.updateImages');
});

Route::post('/post', [PostController::class, 'store'])
    ->name('post.create');

Route::put('/post/{post}', [PostController::class, 'update'])
    ->name('post.update');

Route::delete('/post/{post}', [PostController::class, 'destroy'])
    ->name('post.destroy');

Route::get('/post/download/{attachment}', [PostController::class, 'downloadAttachment'])
    ->name('post.download');

Route::post('/post/{post}/reaction', [PostController::class, 'postReaction'])
    ->name('post.reaction');

Route::post('/post/{post}/comment', [PostController::class, 'createComment'])
    ->name('post.comment.create');

Route::delete('/comment/{comment}', [PostController::class, 'deleteComment'])
    ->name('comment.delete');

Route::put('/comment/{comment}', [PostController::class, 'updateComment'])
    ->name('comment.update');

Route::post('/comment/{comment}/reaction', [PostController::class, 'commentReaction'])
    ->name('comment.reaction');

require __DIR__ . '/auth.php';
