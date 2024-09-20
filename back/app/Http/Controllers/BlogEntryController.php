<?php

namespace App\Http\Controllers;

use App\Models\BlogEntry;
use Illuminate\Http\Request;

class BlogEntryController extends Controller
{
    public function index()
    {
        return BlogEntry::with('blog')->get();
    }

    public function getByBlog($blogId)
    {
        return BlogEntry::where('blog_id', $blogId)->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'blog_id' => 'required|exists:blogs,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        return BlogEntry::create($validated);
    }

    public function show(BlogEntry $blogEntry)
    {
        return $blogEntry->load(['comments.user']);
    }

    public function update(Request $request, BlogEntry $blogEntry)
    {
        $validated = $request->validate([
            'blog_id' => 'sometimes|required|exists:blogs,id',
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);
        $blogEntry->update($validated);
        return $blogEntry;
    }

    public function destroy(BlogEntry $blogEntry)
    {
        $blogEntry->delete();
        return response()->noContent();
    }
}
