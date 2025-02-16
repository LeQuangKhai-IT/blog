<?php

use App\Http\Controllers\API\AdvertisementController;
use App\Http\Controllers\API\AnalyticsController;
use App\Http\Controllers\API\AuditLogController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BackupController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\MediaController;
use App\Http\Controllers\API\NewsletterController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('posts')->controller(PostController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of all posts
    Route::get('/{post}', 'show'); // Retrieve the details of a specific post
    Route::post('/', 'store'); // Create a new post
    Route::match(['put', 'patch'], '/{post}', 'update'); // Update a post
    Route::delete('/{post}', 'destroy'); // Delete a post
    Route::get('/popular', 'getPopular'); // Retrieve a list of the most popular posts (based on views or likes)
    Route::get('/recent', 'getRecent'); // Retrieve a list of the most recent posts
    Route::post('/{post}/like', 'likePost'); // Like a post
    Route::post('/{post}/unlike', 'unlikePost'); // Unlike a post
    Route::get('/{post}/related', 'relatedPosts'); // Retrieve related posts
    Route::get('/{slug}', 'showBySlug'); // Retrieve a post by its slug (commonly used for SEO)
    Route::post('/{post}/share', 'sharePost'); // Share a post on social media
    Route::get('/search?query={keyword}', 'search'); // Search for posts by keyword
});

Route::prefix('categories')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of all categories
    Route::get('/{category}', 'show'); // Retrieve the details of a specific category
    Route::post('/', 'store'); // Create a new category
    Route::match(['put', 'patch'], '/{category}', 'update'); // Update a category
    Route::delete('/{category}', 'destroy'); // Delete a category
    Route::get('/{category}/posts', 'index'); // Retrieve a list of the most popular categories (based on views or likes)
});

Route::prefix('tags')->controller(TagController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of all tags
    Route::get('/{tag}', 'show'); // Retrieve the details of a specific tag
    Route::post('/', 'store'); // Create a new tag
    Route::match(['put', 'patch'], '/{tag}', 'update'); // Update a tag
    Route::delete('/{tag}', 'destroy'); // Delete a tag
});

Route::prefix('comments')->controller(TagController::class)->group(function () {
    Route::match(['put', 'patch'], '/{comment}', 'update'); // Update a comment
    Route::delete('/{comment}', 'destroy'); // Delete a comment
    Route::post('/{comment}/like', 'likeComment'); // Like a comment
    Route::post('/{comment}/reply', 'replyToComment'); // Reply to a comment

    Route::prefix('')->group(function () {
        Route::get('/posts/{comment}/comments', 'index'); // Retrieve a list of comments for a post
        Route::post('/posts/{comment}/comments', 'store'); // Add a comment to a post
    });
});

Route::prefix('users')->middleware(['auth:sanctum', 'verified'])->controller(UserController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of all users
    Route::get('/{user}', 'show'); // Retrieve the details of a specific user
    //Route::post('/', 'store'); // Register a new user
    Route::match(['put', 'patch'], '/{user}', 'update'); // Update user information
    Route::delete('/{user}', 'destroy'); // Delete a user
    Route::get('/{user}/posts', 'getPostsByUser'); // Retrieve a list of posts created by a specific user
    Route::get('/{user}/comments', 'getCommentsByUser'); // Retrieve a list of comments by a specific user
    Route::post('/follow/{user}', 'followUser'); // Follow a user
    Route::post('/unfollow/{user}', 'unfollowUser'); // Unfollow a user
});

Route::prefix('roles')->controller(RoleController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of all roles
    Route::post('/', 'store'); // Register a new role
    Route::match(['put', 'patch'], '/{role}', 'update'); // Update role information
    Route::delete('/{role}', 'destroy'); // Delete a role
});

Route::prefix('permissions')->controller(PermissionController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of all permissions
    Route::post('/', 'store'); // Register a new permission
    Route::match(['put', 'patch'], '/{permission}', 'update'); // Update permission information
    Route::delete('/{permission}', 'destroy'); // Delete a permission
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register'); // Register a new account
    Route::post('/login', 'login'); // Log in a user
    Route::post('/logout', 'logout')->middleware('auth:sanctum'); // Log out
    Route::put('/change-password', 'changePassword')->middleware('auth:sanctum'); // Change the password
    Route::post('/forgot-password', 'forgotPassword'); // Send a password reset request
    Route::post('/reset-password', 'resetPassword')->name('password.reset'); // Reset the password
    Route::post('/email/verification-notification', 'emailVerifyNotification')->middleware('auth:sanctum')->name('verification.notice'); // Send verify email to the user's email address
    Route::get('/email/verify/{id}/{hash}', 'verifyEmail')->middleware(['auth:sanctum'])->name('verification.verify'); // Verify the user's email address
});

Route::prefix('media')->controller(MediaController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of all media files (images, videos, etc.)
    Route::post('/', 'store'); // Upload a media file
    Route::match(['put', 'patch'], '/{media}', 'update'); // Update media file information
    Route::delete('/{media}', 'destroy'); // Delete a media file
    Route::get('/{media}/download', 'download'); // Download a media file
});

// Route::controller(SearchController::class)->group(function () {
//     Route::get('/search?query={keyword}', 'search'); // Search for posts, comments, or categories by keyword
//     Route::get('/search/advanced?query={keyword}&category={id}&tag={id}', 'advancedSearch'); // Advanced search with category and tag filters
// });

Route::prefix('analytics')->controller(AnalyticsController::class)->group(function () {
    Route::get('/posts', 'postAnalytics'); // Retrieve statistics like views or likes for posts
    Route::get('/users', 'userAnalytics'); // Retrieve user statistics (e.g., registered users, activity)
    Route::get('/comments', 'commentAnalytics'); // Retrieve statistics about comment activity
    Route::get('/tags', 'tagAnalytics'); // Retrieve post counts for each tag
    Route::get('/traffic', 'trafficAnalytics'); // Retrieve post counts for each tag
});

Route::prefix('settings')->controller(SettingController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve blog configuration settings (title, description, logo, etc.)
    Route::put('/', 'update'); // Update blog configuration settings
    Route::get('/theme', 'getTheme'); // Retrieve information about the blog theme
    Route::put('/theme', 'updateTheme'); // Change the blog's theme
});

Route::controller(SubscriptionController::class)->group(function () {
    Route::post('/subscribe', 'subscribe'); // Subscribe to email notifications
    Route::post('/unsubscribe', 'unsubscribe'); // Unsubscribe from email notifications
});

Route::prefix('newsletter')->controller(NewsletterController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of sent newsletters
    Route::post('/', 'store'); // Create and send a new newsletter
    Route::delete('/{newsletter}', 'destroy'); // Delete a newsletter
});

Route::prefix('notifications')->controller(NewsletterController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of user notifications
    Route::put('/{notification}/read', 'markAsRead'); // Mark a notification as read
    Route::delete('/{notification}', 'destroy'); //  Delete a notification
});

Route::prefix('ads')->controller(AdvertisementController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of advertisements
    Route::post('/', 'store'); // Create a new advertisement
    Route::match(['put', 'patch'], '/{ads}', 'update'); // Update an advertisement
    Route::delete('/{ads}', 'destroy'); // Delete an advertisement
    Route::get('/{ads}/clicks', 'getClicks'); // Retrieve the number of clicks on an advertisement
});

Route::controller(BackupController::class)->group(function () {
    Route::get('/backup', 'index'); // Retrieve a list of backups
    Route::post('/backup', 'store'); // Create a data backup
    Route::post('/restore', 'restore'); // Restore data from a backup
});

Route::prefix('logs')->controller(AuditLogController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of activity logs
    Route::post('/{log}', 'show'); // Retrieve the details of a specific activity log
    Route::post('/{log}', 'destroy'); // Delete an activity log
});
