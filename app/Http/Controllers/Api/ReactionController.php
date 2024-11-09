<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'comment_id' => 'required|exists:comments,id',
            'type' => 'required|string',
        ]);

        // تحقق مما إذا كان المستخدم قد أضاف تفاعلًا بالفعل على هذا التعليق
        $existingReaction = Reaction::where('user_id', auth()->id())
                                    ->where('comment_id', $request->comment_id)
                                    ->first();

        if ($existingReaction) {
            // إذا كان التفاعل موجودًا بالفعل، حدث نوعه
            $existingReaction->update(['type' => $request->type]);
            $message = 'Reaction updated successfully!';
        } else {
            // إذا لم يكن التفاعل موجودًا، قم بإنشائه
            Reaction::create([
                'user_id' => auth()->id(),
                'comment_id' => $request->comment_id,
                'type' => $request->type,
            ]);
            $message = 'Reaction added successfully!';
        }

        return response()->json(['message' => $message], 200);
    }
}
