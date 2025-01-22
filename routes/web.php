<?php

use App\Http\Controllers\AgoraVideoController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\KalakalkotoController;
use App\Http\Controllers\AdkotoController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GroupChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RechargeWalletController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TextController;
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

Route::post('/profanity-check', [TextController::class, 'checkProfanity']);

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
    Route::get('/bids/list', [AuctionController::class, 'getLatestBidsList']);

    // Live Bidding
    Route::get('/live', [AuctionController::class, 'viewAllLive'])->name('auction.viewAllLive');
    Route::get('/bids/live', [AuctionController::class, 'getLatestBidsLive']);
    Route::get('/stream', [AuctionController::class, 'watchStream'])->name('auction.watchStream');
    Route::get('/stream/bidlist', [AuctionController::class, 'fetchShowWindowData'])->name('auction.fetchShowWindowData');

    // Create
    // Route::get('/create', [AuctionController::class, 'create'])->name('auction.create');
    // Route::post('/', [AuctionController::class, 'store'])->name('auction.store');

    // Show
    Route::get('/{id}', [AuctionController::class, 'show'])->name('auction.show');
    Route::get('/category/{category_name}', [AuctionController::class, 'showCategory'])->name('auction.showCategory');
    Route::get('/u/items/et/', [AuctionController::class, 'showUserItems'])->name('auction.showUserItems');

    // Place Bid
    Route::post('/bid/{id}', [AuctionController::class, 'placeBid'])->name('auction.placeBid');

    Route::get('/{id}/bids', [AuctionController::class, 'getLatestBids']);

    Route::post('/create-checkout-session', [PaymentController::class, 'createCheckoutSession'])->name('payment.createCheckoutSession');
    Route::get('/payment/success/{itemId}', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');

    Route::post('/create-recharge-session', [RechargeWalletController::class, 'createRechargeSession'])->name('recharge.createRechargeSession');
    Route::get('/recharge/success/{userId}', [RechargeWalletController::class, 'rechargeSuccess'])->name('recharge.success');
    Route::get('/recharge/cancel', [RechargeWalletController::class, 'rechargeCancel'])->name('recharge.cancel');
});

// Chatkoto
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/conversation/adktu/{user}', [ChatController::class, 'getConversation'])->name('chat.conversations.show');
    Route::post('/chat/conversations/{conversation}/messages', [ChatController::class, 'sendMessage'])->name('chat.messages.store');
    Route::get('/chat/conversations/{conversationId}/messages', [ChatController::class, 'fetchMessages'])->name('chat.conversations.fetchMessages');

    Route::get('/chat/search-followings', [ChatController::class, 'searchFollowings'])->name('chat.conversations.searchFollowings');
    Route::get('/chat/latest-messages', [ChatController::class, 'getLatestMessages'])->name('chat.conversations.getLatestMessages');
    Route::get('/chat/unread-count', [ChatController::class, 'getUnreadCount'])->name('chat.conversations.getUnreadCount');
    Route::post('/chat/groups/{group}/messages', [ChatController::class, 'sendMessageToGroup']);

    Route::post('/chat/mark-as-read/{conversationId}', [ChatController::class, 'markAsRead']);

    // Route::get('/chat/call/{userId}', [ChatController::class, 'callPage'])->name('chat.callPage');
    // Route::post('/agora/token', [AgoraVideoController::class, 'token']);
    // Route::post('/agora/call-user', [AgoraVideoController::class, 'callUser']);
});

// Group Chat
Route::middleware(['auth', 'verified'])->prefix('/group-chat')->group(function () {
    Route::get('/{groupChat}', [GroupChatController::class, 'index'])->name('group-chats.index');
    Route::post('/create', [GroupChatController::class, 'create'])->name('group-chats.create');
    Route::post('/{groupChat}/update', [GroupChatController::class, 'update'])->name('group-chats.update');
    Route::post('/{groupChat}/add-participant', [GroupChatController::class, 'addParticipant']);
    Route::post('/{groupChatId}/remove-user', [GroupChatController::class, 'removeUser'])->name('group.removeUser');
    Route::get('/{groupChat}/messages', [GroupChatController::class, 'getGroupMessages']);
    Route::post('/{groupChat}/messages', [GroupChatController::class, 'sendMessage']);

    Route::delete('/{groupChat}/leave', [GroupChatController::class, 'leaveGroup'])->name('group-chats.leave');
    Route::get('/group-check/{group_id}', [GroupChatController::class, 'handleGroupChat'])->name('group.handle');
});
Route::middleware(['auth', 'verified'])->prefix('/groupchat')->group(function () {
    Route::get('/fetchUsers', [GroupChatController::class, 'fetchUsers']);
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

Route::get('/g/users/search', [GroupController::class, 'searchUsers'])->name('users.search');

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

    // Updating of cover photo and profile picture
    Route::get('/group/cover/{group}', [GroupController::class, 'groupCoverPage'])->name('group.coverPage');
    Route::post('/group/{group}/update-cover', [GroupController::class, 'updateGroupCover'])
        ->name('group.updateGroupCover');

    Route::get('/group/thumbnail/{group}', [GroupController::class, 'groupThumbnailPage'])->name('group.thumbnailPage');
    Route::post('/group/{group}/update-thumbnail', [GroupController::class, 'updateGroupThumbnail'])
        ->name('group.updateGroupThumbnail');

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

    // Deactivate Profile
    Route::post('/account/validate-password', [ProfileController::class, 'validatePassword'])->name('account.validatePassword');
    Route::delete('/account/deactivate', [ProfileController::class, 'deactivate'])->name('account.deactivate');

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

        Route::get('/{post}/reactions', [PostController::class, 'getReactions'])
            ->name('post.getReactions');
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
