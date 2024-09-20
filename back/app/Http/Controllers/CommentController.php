<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        return Comment::with('blogEntry', 'user')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'blog_entry_id' => 'required|exists:blog_entries,id',
            'content' => 'required|string',
        ]);
        $validated['user_id'] = Auth::id(); 
        $comment = Comment::create($validated);
        return $comment->load('user');
    }

    public function show(Comment $comment)
    {
        return $comment->load('blogEntry', 'user');
    }

    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'blog_entry_id' => 'sometimes|required|exists:blog_entries,id',
            'content' => 'sometimes|required|string',
        ]);
        $comment->update($validated);
        return $comment;
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->noContent();
    }
}
