<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'body' => 'required|string',
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string',
        ]);

        try {
            // استخدام الاتصال الافتراضي المعرّف في .env (مثل 'jo')
            $comment = Comment::on('jo')->create([
                'body' => $validated['body'],
                'user_id' => auth()->id(), // المستخدم من قاعدة البيانات الرئيسية
                'commentable_id' => $validated['commentable_id'],
                'commentable_type' => $validated['commentable_type'],
            ]);

            return response()->json([
                'message' => 'Comment added successfully!',
                'comment' => $comment,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to add comment.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
