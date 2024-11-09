<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use App\Models\Article;
use Illuminate\Http\Request;

class CommentController extends Controller


{
  public function store(Request $request)
  {
    $request->validate([
      'body' => 'required',
      'commentable_id' => 'required',
      'commentable_type' => 'required',
    ]);

    // استخدام الاتصال الافتراضي المعرّف في .env (مثل 'jo')
    Comment::on('jo')->create([
      'body' => $request->body,
      'user_id' => auth()->id(), // المستخدم من قاعدة البيانات الرئيسية
      'commentable_id' => $request->commentable_id,
      'commentable_type' => $request->commentable_type,
    ]);

    return redirect()->back()->with('success', 'Comment added successfully!');
  }
}
