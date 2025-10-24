<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'work_id' => 'required|exists:works,id',
            'content' => 'required|string|max:500'
        ]);

        $comment = Comment::create([
            'work_id' => $request->work_id,
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'user_name' => auth()->user()->name,
                'content' => $comment->content
            ]
        ]);
    }
    public function destroy(Comment $comment)
{
    // Hanya pemilik komentar atau admin yang boleh hapus
    if (!Auth::user()->isAdmin() && $comment->user_id !== Auth::id()) {
        return response()->json(['success' => false, 'message' => 'Tidak diizinkan.'], 403);
    }

    $comment->delete();

    return response()->json(['success' => true]);
}
public function update(Request $request, Comment $comment)
{
    if (!Auth::user()->isAdmin() && $comment->user_id !== Auth::id()) {
        return response()->json(['success' => false, 'message' => 'Tidak diizinkan.'], 403);
    }

    $request->validate([
        'content' => 'required|string|max:1000',
    ]);

    $comment->update(['content' => $request->content]);

    return response()->json([
        'success' => true,
        'content' => $comment->content,
        'edited_at' => $comment->updated_at->diffForHumans()
    ]);
}
}