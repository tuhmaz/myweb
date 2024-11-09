<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MessageNotification;

class MessageController extends Controller
{
    public function index()
    {
        $user_id = auth()->id();
        $conversations = Conversation::where('user1_id', $user_id)->orWhere('user2_id', $user_id)->pluck('id');

        $messages = Message::whereIn('conversation_id', $conversations)
            ->where('sender_id', '!=', $user_id)
            ->latest()
            ->get();

        return response()->json(['messages' => $messages]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        $sender_id = auth()->id();
        $recipient_id = $request->recipient_id;

        // البحث عن محادثة موجودة أو إنشاء واحدة جديدة
        $conversation = Conversation::firstOrCreate(
            ['user1_id' => $sender_id, 'user2_id' => $recipient_id],
            ['user1_id' => $recipient_id, 'user2_id' => $sender_id]
        );

        // إنشاء الرسالة
        $message = new Message();
        $message->sender_id = $sender_id;
        $message->conversation_id = $conversation->id;
        $message->subject = $request->subject;
        $message->body = $request->body;
        $message->read = false;
        $message->save();

        // إشعار المستلم
        $recipient = User::find($recipient_id);
        $recipient->notify(new MessageNotification($message));

        return response()->json(['message' => 'Message sent successfully', 'message_details' => $message], 201);
    }

    public function show($id)
    {
        $message = Message::findOrFail($id);

        if ($message->sender_id !== auth()->id() && $message->conversation->user1_id !== auth()->id() && $message->conversation->user2_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        return response()->json($message);
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $originalMessage = Message::findOrFail($id);

        // إنشاء رسالة الرد
        $replyMessage = new Message();
        $replyMessage->body = $request->input('body');
        $replyMessage->sender_id = auth()->id();
        $replyMessage->conversation_id = $originalMessage->conversation_id;
        $replyMessage->subject = 'Re: ' . $originalMessage->subject;
        $replyMessage->read = false;
        $replyMessage->save();

        return response()->json(['message' => 'Reply sent successfully', 'reply' => $replyMessage]);
    }

    public function sent()
    {
        $user_id = auth()->id();
        $messages = Message::where('sender_id', $user_id)->latest()->get();

        return response()->json(['sent_messages' => $messages]);
    }

    public function markAsRead($id)
    {
        $message = Message::findOrFail($id);

        if ($message->conversation->user1_id !== auth()->id() && $message->conversation->user2_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $message->read = true;
        $message->save();

        return response()->json(['message' => 'Message marked as read']);
    }

    public function delete($id)
    {
        $message = Message::findOrFail($id);

        if ($message->sender_id !== auth()->id() && $message->conversation->user1_id !== auth()->id() && $message->conversation->user2_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $message->delete();

        return response()->json(['message' => 'Message deleted successfully']);
    }

    public function toggleImportant($id)
    {
        $message = Message::findOrFail($id);

        if ($message->sender_id !== auth()->id() && $message->conversation->user1_id !== auth()->id() && $message->conversation->user2_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $message->is_important = !$message->is_important;
        $message->save();

        return response()->json(['message' => 'Message importance toggled', 'important_status' => $message->is_important]);
    }

    public function deleteSelected(Request $request)
    {
        $request->validate([
            'selected_messages' => 'required|array',
        ]);

        $messages = Message::whereIn('id', $request->selected_messages)
            ->where(function ($query) {
                $query->where('sender_id', auth()->id())
                      ->orWhereHas('conversation', function ($subquery) {
                          $subquery->where('user1_id', auth()->id())->orWhere('user2_id', auth()->id());
                      });
            })->get();

        foreach ($messages as $message) {
            $message->delete();
        }

        return response()->json(['message' => 'Selected messages deleted successfully']);
    }
}
