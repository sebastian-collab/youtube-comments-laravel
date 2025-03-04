<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;

Route::prefix('video-comments')->group(function () {
    Route::get('/', [CommentController::class, 'index']);
    Route::get('/count', [CommentController::class, 'count']);
    Route::post('/', [CommentController::class, 'store']);
    Route::put('/update', [CommentController::class, 'update']);
    Route::post('/like', [CommentController::class, 'like']);
    Route::post('/dislike', [CommentController::class, 'dislike']);
    Route::post('/reply', [CommentController::class, 'reply']);
    Route::get('/{comment}/replies', [CommentController::class, 'getReplies']);
});

Route::get('/health', function () {
    return response()->json(['status' => 'server is running']);
});
