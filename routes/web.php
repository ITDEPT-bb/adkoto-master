<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\KalakalkotoController;
use App\Http\Controllers\AdkotoController;
use App\Http\Controllers\AuctionController;
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

Route::get('/terms', [HomeController::class, 'terms'])
    ->name('terms.show');

Route::get('/policy', [HomeController::class, 'policy'])
    ->name('policy.show');

Route::get('/copyright', [HomeController::class, 'copyright'])
    ->name('copyright.show');

Route::get('/faqs', [HomeController::class, 'faqs'])
    ->name('faqs.show');

// Kalakalkoto
Route::middleware(['auth', 'verified'])->prefix('/kalakalkoto')->group(function () {
    Route::get('/', [KalakalkotoController::class, 'index'])->name('kalakalkoto');

    // Create
    Route::get('/create', [KalakalkotoController::class, 'create'])->name('kalakalkoto.create');
    Route::post('/', [KalakalkotoController::class, 'store'])->name('kalakalkoto.store');

    // Show
    Route::get('/{id}', [KalakalkotoController::class, 'show'])->name('kalakalkoto.show');
    Route::get('/category/{category_name}', [KalakalkotoController::class, 'showCategory'])->name('kalakalkoto.showCategory');
    Route::get('/u/items/et/', [KalakalkotoController::class, 'showUserItems'])->name('kalakalkoto.showUserItems');

    // Edit
    Route::get('/{id}/edit', [KalakalkotoController::class, 'edit'])->name('kalakalkoto.edit');
    Route::put('/{id}', [KalakalkotoController::class, 'update'])->name('kalakalkoto.update');
    Route::put('/mark-as-sold/{id}', [KalakalkotoController::class, 'markAsSold'])->name('kalakalkoto.markAsSold');

    // Delete
    Route::delete('/{id}', [KalakalkotoController::class, 'destroy'])->name('kalakalkoto.destroy');
});

// Auction
Route::middleware(['auth', 'verified'])->prefix('/auction')->group(function () {
    Route::get('/', [AuctionController::class, 'index'])->name('auction');

    // Create
    Route::get('/create', [AuctionController::class, 'create'])->name('auction.create');
    Route::post('/', [AuctionController::class, 'store'])->name('auction.store');

    // Show
    Route::get('/{id}', [AuctionController::class, 'show'])->name('auction.show');
    Route::get('/category/{category_name}', [AuctionController::class, 'showCategory'])->name('auction.showCategory');
    Route::get('/u/items/et/', [AuctionController::class, 'showUserItems'])->name('auction.showUserItems');
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
    Route::get('/notifications/all', [NotificationController::class, 'fetchAllNotifications'])->name('notifications.fetchAllNotifications');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::get('/users/{user}', [ProfileController::class, 'fetchProfileNotif']);
});

// Adkoto
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/adkoto', [AdkotoController::class, 'index'])->name('adkoto');
    Route::get('/adkoto/create', [AdkotoController::class, 'create'])->name('adkoto.create');
    Route::post('/adkoto', [AdkotoController::class, 'store'])->name('adkoto.store');
    Route::get('adkoto/{id}', [AdkotoController::class, 'show'])->name('adkoto.show');
    Route::get('adkoto/{id}/edit', [AdkotoController::class, 'edit'])->name('adkoto.edit');
    Route::put('adkoto/{id}', [AdkotoController::class, 'update'])->name('adkoto.update');
    Route::delete('adkoto/{id}', [AdkotoController::class, 'destroy'])->name('adkoto.destroy');

    Route::get('/adkoto/category/{category_name}', [AdkotoController::class, 'showCategory'])->name('adkoto.showCategory');
    Route::get('/adkoto/category/{category_name}/{subcategory_name}', [AdkotoController::class, 'showSubcategory'])->name('adkoto.showSubcategory');
    Route::get('/adkoto/u/et/', [AdkotoController::class, 'showUserAds'])->name('adkoto.showUserAds');
});

Route::get('/u/{user:username}', [ProfileController::class, 'index'])
    ->name('profile');

Route::get('/g/{group:slug}', [GroupController::class, 'profile'])
    ->name('group.profile');

Route::get('/group/approve-invitation/{token}', [GroupController::class, 'approveInvitation'])
    ->name('group.approveInvitation');

Route::middleware(['auth', 'verified'])->group(function () {

    // Games
    Route::get('/games', [GamesController::class, 'index'])->name('games.index');
    Route::get('/games/{id}', [GamesController::class, 'show'])->name('game.show');

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

        Route::post('/leave-group/{group:slug}', [GroupController::class, 'leaveGroup'])
            ->name('group.leaveGroup');

        Route::delete('/{id}', [GroupController::class, 'softDelete'])
            ->name('group.delete');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-images', [ProfileController::class, 'updateImage'])
        ->name('profile.updateImages');


    Route::post('/user/follow/{user}', [UserController::class, 'follow'])->name('user.follow');

    // Updating of cover photo and profile picture
    Route::get('/user/cover/{user}', [ProfileController::class, 'coverPage'])->name('profile.coverPage');
    Route::post('/profile/update-cover', [ProfileController::class, 'updateCover'])
        ->name('profile.updateCover');

    Route::get('/user/avatar/{user}', [ProfileController::class, 'avatarPage'])->name('profile.avatarPage');
    Route::post('/profile/update-avatar', [ProfileController::class, 'updateAvatar'])
        ->name('profile.updateAvatar');

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
