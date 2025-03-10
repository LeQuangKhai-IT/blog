<?php

use App\Http\Controllers\API\AdvertisementController;
use App\Http\Controllers\API\AnalyticsController;
use App\Http\Controllers\API\AuditLogController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BackupController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\MediaController;
use App\Http\Controllers\API\NewsletterController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\RolePermissionController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\SettingController;
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
// Ok
Route::prefix('posts')->controller(PostController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of all posts
    Route::get('/{post}', 'show'); // Retrieve the details of a specific post
    Route::post('/', 'store'); // Create a new post
    Route::put('/{post}', 'update'); // Update a post
    Route::delete('/{post}', 'destroy'); // Delete a post
    Route::post('/{post}/publish', 'publishPost'); // Publish a post
    Route::post('/{post}/unpublish', 'unpublishPost'); //Unpublish a post
    Route::get('/published', 'getPublished'); // Retrieve a list of all posts published
    Route::get('/popular', 'getPopular'); // Retrieve a list of the most popular posts (based on views)
    Route::get('/recent', 'getRecent'); // Retrieve a list of the most recent posts
    Route::post('/{post}/like', 'likePost'); // Like a post
    Route::post('/{post}/unlike', 'unlikePost'); // Unlike a post
    Route::get('/{post}/related', 'relatedPosts'); // Retrieve related posts
    Route::get('/{slug}', 'showBySlug'); // Retrieve a post by its slug (commonly used for SEO)
    Route::post('/{post}/share', 'sharePost'); // Share a post on social media
    Route::get('/search?query={keyword}', 'search'); // Search for posts by keyword
});
// Ok
Route::prefix('categories')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of all categories
    Route::get('/{category}', 'show'); // Retrieve the details of a specific category
    Route::post('/', 'store'); // Create a new category
    Route::put('/{category}', 'update'); // Update a category
    Route::delete('/{category}', 'destroy'); // Delete a category
    Route::get('/{category}/posts', 'index'); // Retrieve a list of the most popular categories (based on views)
});
// Ok
Route::prefix('tags')->controller(TagController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of all tags
    Route::get('/{tag}', 'show'); // Retrieve the details of a specific tag
    Route::post('/', 'store'); // Create a new tag
    Route::put('/{tag}', 'update'); // Update a tag
    Route::delete('/{tag}', 'destroy'); // Delete a tag
});
// Ok
Route::prefix('comments')->controller(CommentController::class)->group(function () {
    Route::put('/{comment}', 'update'); // Update a comment
    Route::delete('/{comment}', 'destroy'); // Delete a comment
    Route::post('/{comment}/like', 'likeComment'); // Like a comment
    Route::post('/{comment}/reply', 'replyToComment'); // Reply to a comment

    Route::prefix('posts')->group(function () {
        Route::get('/{comment}/comments', 'index'); // Retrieve a list of comments for a post
        Route::post('/{comment}/comments', 'store'); // Add a comment to a post
    });
});
// Ok
Route::prefix('users')->middleware(['auth:sanctum', 'verified'])->controller(UserController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of all users
    Route::get('/{user}', 'show'); // Retrieve the details of a specific user
    Route::post('/', 'store'); // Create a new user
    Route::put('/{user}', 'update'); // Update user information
    Route::delete('/{user}', 'destroy'); // Delete a user
    Route::get('/{user}/posts', 'getPostsByUser'); // Retrieve a list of posts created by a specific user
    Route::get('/{user}/comments', 'getCommentsByUser'); // Retrieve a list of comments by a specific user
});
// Chưa gì hết
Route::prefix('roles')->controller(RolePermissionController::class)->group(function () {
    Route::get('/', 'indexRoles'); // Retrieve all roles
    Route::post('/', 'storeRole'); // Create a new role
    Route::put('/{role}', 'updateRole'); // Update an existing role
    Route::delete('/{role}', 'destroyRole'); // Delete a role
    Route::post('/{role}/permissions', 'assignPermissionToRole'); // Assign permission to a role
    Route::delete('/{role}/permissions/{permission}', 'removePermissionFromRole'); // Remove permission from a role
});
// Chưa gì hết
Route::prefix('permissions')->controller(RolePermissionController::class)->group(function () {
    Route::get('/', 'indexPermissions'); // Retrieve all permissions
    Route::post('/', 'storePermission'); // Create a new permission
    Route::put('/{permission}', 'updatePermission'); // Update an existing permission
    Route::delete('/{permission}', 'destroyPermission'); // Delete a permission
});

// Ok còn 2 cái cuối
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register'); // Register a new account
    Route::post('/login', 'login'); // Log in a user
    Route::post('/logout', 'logout')->middleware('auth:sanctum'); // Log out
    Route::put('/change-password', 'changePassword')->middleware('auth:sanctum'); // Change the password
    Route::post('/forgot-password', 'forgotPassword'); // Send a password reset request
    Route::post('/reset-password', 'resetPassword')->name('password.reset'); // Reset the password
    Route::get('/email/verify/{id}/{hash}', 'verifyEmail')->middleware(['auth:sanctum'])->name('verification.verify'); // Verify the user's email address
    Route::post('/email/verification-notification', 'emailVerifyNotification')->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send'); // Send verify email again to the user's email address
    //Route::get('/auth/{provider}', 'redirectToProvider'); //  Redirects the user to the OAuth provider for authentication
    //Route::get('/auth/{provider}/callback', 'handleProviderCallback'); // Handle the callback after authentication
});
// Ok
Route::prefix('media')->controller(MediaController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of all media files (images, videos, etc.)
    Route::post('/upload', 'store'); // Upload a media file
    Route::get('/{media}', 'show'); // Show media file information
    Route::delete('/{media}', 'destroy'); // Delete a media file
    Route::get('/download/{media}', 'download'); // Download a media file
});

// Route::controller(SearchController::class)->group(function () {
//     Route::get('/search?query={keyword}', 'search'); // Search for posts, comments, or categories by keyword
//     Route::get('/search/advanced?query={keyword}&category={id}&tag={id}', 'advancedSearch'); // Advanced search with category and tag filters
// });

// Okkkkk
Route::prefix('analytics')->controller(AnalyticsController::class)->group(function () {
    Route::get('/posts', 'postAnalytics'); // Retrieve statistics like views or likes for posts
    Route::get('/users', 'userAnalytics'); // Retrieve user statistics (e.g., registered users, activity)
    Route::get('/comments', 'commentAnalytics'); // Retrieve statistics about comment activity
    Route::get('/tags', 'tagAnalytics'); // Retrieve post counts for each tag
    Route::get('/traffic', 'trafficAnalytics'); // Retrieve post counts for each tag
});
// Chưa gì hết
Route::prefix('settings')->controller(SettingController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve blog configuration settings (title, description, logo, etc.)
    Route::put('/', 'update'); // Update blog configuration settings
    Route::get('/theme', 'getTheme'); // Retrieve information about the blog theme
    Route::put('/theme', 'updateTheme'); // Change the blog's theme
});
// Chưa gì hết
Route::prefix('newsletter')->middleware(['auth:sanctum'])->controller(NewsletterController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of sent newsletters
    Route::post('/', 'store'); // Create and send a new newsletter
    Route::post('/subscribe', 'subscribe'); // Subscribe newsletters
    Route::post('/unsubscribe', 'unsubscribe'); // Unsubscribe newsletters
    Route::delete('/{newsletter}', 'destroy'); // Delete a newsletter
});

Route::prefix('notifications')->middleware('auth:sanctum')->controller(NewsletterController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of user notifications
    Route::get('/{notification}', 'show'); // Retrieve a specific notification's details
    Route::post('/', 'store')->middleware('role:admin'); // Admin or System create a new notification
    Route::put('/{notification}/read', 'markAsRead'); // Mark a notification as read
    Route::put('/mark-all-read', 'markAllAsRead'); // Mark all notifications as read
    Route::delete('/{notification}', 'destroy')->middleware('role:admin'); // Delete a notification (Admin only)
});
// Okkk
Route::middleware(['auth:sanctum', 'role:admin'])->controller(BackupController::class)->group(function () {
    Route::get('/backup', 'index'); // Retrieve a list of backups
    Route::post('/backup', 'store'); // Create a data backup
    Route::post('/restore', 'restore'); // Restore data from a backup
});
// Okkk
Route::prefix('logs')->middleware(['auth:sanctum', 'role:admin'])->controller(AuditLogController::class)->group(function () {
    Route::get('/', 'index'); // Retrieve a list of activity logs
    Route::post('/{log}', 'destroy'); // Delete an activity log
});
