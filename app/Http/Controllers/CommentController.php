<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $videoId = 'dQw4w9WgXcQ'; // hardcoded video

    /**
     * Get comments with optional sorting
     */
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'newest'); // Default to newest

        $query = Comment::where('video_id', $this->videoId)
            ->where('parent_id', null); // Get only top level comments

        // we can add more sorting options here ;ile this
        if ($sort === 'top') {
            $query->orderByDesc('likes');
        } else {
            $query->orderByDesc('created_at');
        }

        $comments = $query->with('replies')->paginate(20);

        // Get the total comment count for the video
        $totalComments = Comment::where('video_id', $this->videoId)->count();

        return response()->json([
            'data' => $comments,
            'video_id' => $this->videoId,
            'total_comments' => $totalComments
        ]);
    }

    /**
     * Get total comment count for the video
     */
    public function count()
    {
        $totalComments = Comment::where('video_id', $this->videoId)->count();

        return response()->json([
            'total_comments' => $totalComments,
            'video_id' => $this->videoId
        ]);
    }

    /**
     * Store a new comment
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'user_name' => 'required|string|max:100', // no auth, we will accept a username
        ]);

        $comment = Comment::create([
            'video_id' => $this->videoId,
            'content' => $request->content,
            'user_name' => $request->user_name,
            'likes' => 0,
            'dislikes' => 0,
        ]);

        return response()->json($comment, 201);
    }

    /**
     * Update an existing comment
     */
    public function update(Request $request)
    {
        $request->validate([
            'comment_id' => 'required|integer|exists:comments,id',
            'content' => 'required|string|max:1000',
            'user_name' => 'required|string|max:100', // To verify "ownership" without auth
        ]);

        // Find the comment using the ID from the request body
        $comment = Comment::findOrFail($request->comment_id);

        // In a real app, you'd check user auth instead of username
        if ($comment->user_name !== $request->user_name) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->update([
            'content' => $request->content,
            'is_edited' => true, // Mark comment as edited
        ]);

        return response()->json($comment);
    }


    /**
     * Like a comment
     */
    public function like(Request $request)
    {
        $request->validate([
            'comment_id' => 'required|exists:comments,id',
        ]);

        $comment = Comment::findOrFail($request->comment_id);
        $comment->increment('likes');

        return response()->json([
            'likes' => $this->formatCount($comment->likes),
            'dislikes' => $this->formatCount($comment->dislikes),
        ]);
    }

    /**
     * Dislike a comment
     */
    public function dislike(Request $request)
    {
        $request->validate([
            'comment_id' => 'required|exists:comments,id',
        ]);

        $comment = Comment::findOrFail($request->comment_id);
        $comment->increment('dislikes');

        return response()->json([
            'likes' => $this->formatCount($comment->likes),
            'dislikes' => $this->formatCount($comment->dislikes),
        ]);
    }

    /**
     * Reply to a comment
     */
    public function reply(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'user_name' => 'required|string|max:100',
            'comment_id' => 'required|exists:comments,id', // Parent comment ID
        ]);

        $parentComment = Comment::findOrFail($request->comment_id);

        $reply = Comment::create([
            'video_id' => $parentComment->video_id,
            'content' => $request->content,
            'user_name' => $request->user_name,
            'parent_id' => $parentComment->id,
            'likes' => 0,
            'dislikes' => 0,
        ]);

        return response()->json($reply, 201);
    }

    /**
     * Get replies for a specific comment
     */
    public function getReplies(Comment $comment)
    {
        $replies = Comment::where('parent_id', $comment->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($replies);
    }

    /**
     * Format count for likes/dislikes (e.g., "1k" for 1000)
     */
    private function formatCount($count)
    {
        if ($count >= 1000000) {
            return round($count / 1000000, 1) . 'M';
        } elseif ($count >= 1000) {
            return round($count / 1000, 1) . 'K';
        }

        return $count;
    }
}
