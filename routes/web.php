<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserFollowController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TopicController as AdminTopicController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\BadgeController as AdminBadgeController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Search
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

// AI Assistant
Route::post('/ai/ask', [\App\Http\Controllers\AiAssistantController::class, 'ask'])->name('ai.ask');

// Activity Feed (JSON for polling)
Route::get('/api/activity-feed', [\App\Http\Controllers\ActivityFeedController::class, 'index'])->name('activities.feed');

// Challenges (JSON)
Route::get('/api/challenges', [\App\Http\Controllers\ChallengeController::class, 'index'])->name('challenges.index');

// Trivia Challenge
Route::get('/trivia/random', [\App\Http\Controllers\TriviaController::class, 'random'])->name('trivia.random');
Route::post('/trivia/verify', [\App\Http\Controllers\TriviaController::class, 'verify'])->name('trivia.verify');

// Leaderboard
Route::get('/leaderboard', [\App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard');

// Topics & Tags
Route::get('/topics', [TopicController::class, 'index'])->name('topics.index');
Route::get('/topics/{slug}', [TopicController::class, 'show'])->name('topics.show');
Route::get('/tags/{slug}', [TagController::class, 'show'])->name('tags.show');

// Articles
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');

// Questions & Answers
Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');

// Threads (Forum)
Route::get('/threads', [ThreadController::class, 'index'])->name('threads.index');

// Profiles
Route::get('/u/{username}', [UserController::class, 'show'])->name('users.show');
Route::get('/u/{username}/collections', [UserController::class, 'collections'])->name('users.collections');
Route::get('/u/{username}/collections/{id}', [UserController::class, 'showCollection'])->name('users.collections.show');

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/settings', [SettingsController::class, 'show'])->name('settings.edit');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Article Write/Edit
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{slug}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{slug}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{slug}', [ArticleController::class, 'destroy'])->name('articles.destroy');

    // Questions Ask
    Route::get('/questions/ask', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::delete('/questions/{slug}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    // Answers
    Route::post('/questions/{id}/answers', [AnswerController::class, 'store'])->name('answers.store');
    Route::post('/answers/{id}/brainliest', [AnswerController::class, 'markAsBrainliest'])->name('answers.brainliest');

    // Threads Write/Edit
    Route::get('/threads/create', [ThreadController::class, 'create'])->name('threads.create');
    Route::post('/threads', [ThreadController::class, 'store'])->name('threads.store');
    Route::delete('/threads/{slug}', [ThreadController::class, 'destroy'])->name('threads.destroy');
    Route::post('/threads/{id}/replies', [ReplyController::class, 'store'])->name('replies.store');
    Route::delete('/replies/{id}', [ReplyController::class, 'destroy'])->name('replies.destroy');

    // Thank You System
    Route::post('/answers/{id}/thank', [\App\Http\Controllers\ThankController::class, 'store'])->name('thanks.store');

    // Comments
    Route::post('/comments/{type}/{id}', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Votes, Likes, Bookmarks
    Route::post('/votes/{type}/{id}', [VoteController::class, 'vote'])->name('votes');
    Route::post('/likes/{articleId}', [LikeController::class, 'toggle'])->name('likes.toggle');
    Route::post('/bookmarks/{type}/{id}', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');

    // Reports
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

    // Topic Follow
    Route::post('/topics/{id}/follow', [TopicController::class, 'follow'])->name('topics.follow');

    // Poll Vote
    Route::post('/polls/{id}/vote', [HomeController::class, 'vote'])->name('polls.vote');

    // User Follow
    Route::post('/users/{user}/follow', [UserFollowController::class, 'toggle'])->name('users.follow');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/start/{user}', [MessageController::class, 'start'])->name('messages.start');
    Route::post('/messages/{conversation}', [MessageController::class, 'store'])->name('messages.store');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
});

// Admin Panel (Admin/Moderator Middleware)
Route::middleware(['auth', 'moderator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User role updating & banning
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::put('/users/{id}/role', [AdminUserController::class, 'updateRole'])->name('users.role');
    Route::post('/users/{id}/ban', [AdminUserController::class, 'ban'])->name('users.ban');

    // Topics Management
    Route::get('/topics', [AdminTopicController::class, 'index'])->name('topics.index');
    Route::post('/topics', [AdminTopicController::class, 'store'])->name('topics.store');
    Route::put('/topics/{id}', [AdminTopicController::class, 'update'])->name('topics.update');
    Route::delete('/topics/{id}', [AdminTopicController::class, 'destroy'])->name('topics.destroy');

    // Tags Management
    Route::get('/tags', [AdminTagController::class, 'index'])->name('tags.index');
    Route::post('/tags', [AdminTagController::class, 'store'])->name('tags.store');
    Route::delete('/tags/{id}', [AdminTagController::class, 'destroy'])->name('tags.destroy');

    // Reports Management
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/{report}/dismiss', [AdminReportController::class, 'dismiss'])->name('reports.dismiss');
    Route::post('/reports/{report}/resolve', [AdminReportController::class, 'resolve'])->name('reports.resolve');

    // Badges Management
    Route::get('/badges', [AdminBadgeController::class, 'index'])->name('badges.index');
    Route::post('/badges', [AdminBadgeController::class, 'store'])->name('badges.store');
    Route::delete('/badges/{id}', [AdminBadgeController::class, 'destroy'])->name('badges.destroy');
});

require __DIR__.'/auth.php';

// Public Wildcard Routes (Must be at the bottom to prevent catching static routes like /questions/ask)
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/questions/{slug}', [QuestionController::class, 'show'])->name('questions.show');
Route::get('/threads/{slug}', [ThreadController::class, 'show'])->name('threads.show');
