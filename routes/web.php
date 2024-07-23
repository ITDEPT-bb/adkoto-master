<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\KalakalkotoController;
use App\Http\Controllers\AdkotoController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/kalakalkoto', [KalakalkotoController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('kalakalkoto');

// Route::get('/kalakalkoto/prod/', [KalakalkotoController::class, 'view'])
//     ->middleware(['auth', 'verified'])
//     ->name('product');

// Kalakalkoto
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('/kalakalkoto')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('kalakalkoto');
        Route::get('/user/items', [ItemController::class, 'userItems'])->name('kalakalkoto.userItems');

        Route::get('/user/item/{id}', [ItemController::class, 'showItem'])->name('kalakalkoto.showItem');
        Route::get('/item/{id}/edit', [ItemController::class, 'edit'])->name('item.edit');
        Route::put('/item/update/{id}', [ItemController::class, 'update'])->name('item.update');
        Route::delete('/item/{id}', [ItemController::class, 'destroy'])->name('item.destroy');
        Route::post('/item/{id}/mark-as-sold', [ItemController::class, 'markAsSold'])->name('item.markAsSold');

        Route::get('/item/{id}', [ItemController::class, 'show'])->name('kalakalkoto.item.show');
        Route::get('/category/{id}', [ItemController::class, 'filterByCategory'])->name('kalakalkoto.category.filter');

        Route::get('/create', [ItemController::class, 'create'])->name('kalakalkoto.item.create');
        Route::post('/item', [ItemController::class, 'store'])->name('kalakalkoto.item.store');
        // Route::put('/item/{id}/mark-as-sold', [ItemController::class, 'markAsSold'])->name('kalakalkoto.item.markAsSold');
    });
});

// Chatkoto
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/conversation/adktu/{user}', [ChatController::class, 'getConversation'])->name('chat.conversations.show');
    Route::post('/chat/conversations/{conversation}/messages', [ChatController::class, 'sendMessage'])->name('chat.messages.store');
    Route::get('/chat/conversations/{conversationId}/messages', [ChatController::class, 'fetchMessages'])->name('chat.conversations.fetchMessages');
});

// Notification
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/users/{user}', [ProfileController::class, 'fetchProfileNotif']);
});

// Adkoto
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/adkoto', [AdkotoController::class, 'index'])->name('adkoto');
    Route::get('/adkoto/create', [AdkotoController::class, 'create'])->name('adkoto.create');
    Route::post('/adkoto', [AdkotoController::class, 'store'])->name('adkoto.store');
    Route::get('/adkoto/{id}', [AdkotoController::class, 'show'])->name('adkoto.show');
    Route::get('/adkoto/{id}/edit', [AdkotoController::class, 'edit'])->name('adkoto.edit');
    Route::put('/adkoto/{id}', [AdkotoController::class, 'update'])->name('adkoto.update');
    Route::delete('/adkoto/{id}', [AdkotoController::class, 'destroy'])->name('adkoto.destroy');

    Route::get('/ads', [AdkotoController::class, 'fetchAllUserAds'])->name('ads.fetchAll');

    // Route for fetching categories
    Route::get('/api/categories', [AdkotoController::class, 'fetchCategories'])->name('api.categories');
});

Route::get('/u/{user:username}', [ProfileController::class, 'index'])
    ->name('profile');

Route::get('/g/{group:slug}', [GroupController::class, 'profile'])
    ->name('group.profile');

Route::get('/group/approve-invitation/{token}', [GroupController::class, 'approveInvitation'])
    ->name('group.approveInvitation');

Route::middleware('auth')->group(function () {

    // Groups
    Route::prefix('/group')->group(function () {

        Route::post('/', [GroupController::class, 'store'])
            ->name('group.create');

        Route::put('/{group:slug}', [GroupController::class, 'update'])
            ->name('group.update');

        Route::post('/update-images/{group:slug}', [GroupController::class, 'updateImage'])
            ->name('group.updateImages');

        Route::post('/invite/{group:slug}', [GroupController::class, 'inviteUsers'])
            ->name('group.inviteUsers');

        Route::post('/join/{group:slug}', [GroupController::class, 'join'])
            ->name('group.join');

        Route::post('/approve-request/{group:slug}', [GroupController::class, 'approveRequest'])
            ->name('group.approveRequest');

        Route::delete('/remove-user/{group:slug}', [GroupController::class, 'removeUser'])
            ->name('group.removeUser');

        Route::post('/change-role/{group:slug}', [GroupController::class, 'changeRole'])
            ->name('group.changeRole');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-images', [ProfileController::class, 'updateImage'])
        ->name('profile.updateImages');

    Route::post('/user/follow/{user}', [UserController::class, 'follow'])->name('user.follow');

    Route::get('/api/check-profile', [ProfileController::class, 'checkProfile']);

    // Posts
    Route::prefix('/post')->group(function () {
        Route::get('/{post}', [PostController::class, 'view'])
            ->name('post.view');

        Route::post('', [PostController::class, 'store'])
            ->name('post.create');

        Route::put('/{post}', [PostController::class, 'update'])
            ->name('post.update');

        Route::delete('/{post}', [PostController::class, 'destroy'])
            ->name('post.destroy');

        Route::get('/download/{attachment}', [PostController::class, 'downloadAttachment'])
            ->name('post.download');

        Route::post('/{post}/reaction', [PostController::class, 'postReaction'])
            ->name('post.reaction');

        Route::post('/{post}/comment', [PostController::class, 'createComment'])
            ->name('post.comment.create');

        Route::post('/fetch-url-preview', [PostController::class, 'fetchUrlPreview'])
            ->name('post.fetchUrlPreview');

        Route::post('/{post}/pin', [PostController::class, 'pinUnpin'])
            ->name('post.pinUnpin');
    });

    // Comments
    Route::delete('/comment/{comment}', [PostController::class, 'deleteComment'])
        ->name('comment.delete');

    Route::put('/comment/{comment}', [PostController::class, 'updateComment'])
        ->name('comment.update');

    Route::post('/comment/{comment}/reaction', [PostController::class, 'commentReaction'])
        ->name('comment.reaction');

    Route::get('/search/{search?}', [SearchController::class, 'search'])
        ->name('search');
});

require __DIR__ . '/auth.php';
