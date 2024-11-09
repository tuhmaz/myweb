<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;
use Dotenv\Util\Str;
use App\Notifications\MessageNotification;

class MessageController extends Controller
{
    public function compose()
    {
        $users = User::all();
        return view('dashboard.messages.compose', compact('users'));
    }

    public function index()
    {
      $users = User::all();
        $user_id = auth()->id();
        $totalMessages = Message::where('sender_id', $user_id)
            ->orWhereHas('conversation', function ($query) use ($user_id) {
                $query->where('user1_id', $user_id)->orWhere('user2_id', $user_id);
            })
            ->count();
        $unreadMessagesCount = Message::whereIn('conversation_id', Conversation::where('user1_id', $user_id)->orWhere('user2_id', $user_id)->pluck('id'))
            ->where('sender_id', '!=', $user_id)
            ->where('read', false)
            ->count();
        $sentMessagesCount = Message::where('sender_id', $user_id)->count();
        $conversations = Conversation::where('user1_id', $user_id)->orWhere('user2_id', $user_id)->pluck('id');
        $unreadMessages = Message::whereIn('conversation_id', $conversations)
            ->where('sender_id', '!=', $user_id)
            ->where('read', false)
            ->latest()
            ->get();
        foreach ($unreadMessages as $message) {
            $message->read = true;
            $message->save();
        }
        $messages = Message::whereIn('conversation_id', $conversations)
            ->where('sender_id', '!=', $user_id)
            ->latest()
            ->get();
        return view('dashboard.messages.index', compact('users','unreadMessages', 'messages', 'totalMessages', 'unreadMessagesCount', 'sentMessagesCount'));
    }

    public function createMessage()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('messages.create', compact('users'));
    }

    public function store(Request $request)
    {
        $message = new Message();
        $message->body = $request->body;
        $message->sender_id = auth()->id();
        $message->conversation_id = $request->conversation_id;
        $message->save();
        return redirect()->back();
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'recipient' => 'required|exists:users,id',
            'subject' => 'required|string',
            'message' => 'required|string'
        ]);

        $sender_id = auth()->id();
        $recipient_id = $request->recipient;

        $conversation = Conversation::firstOrCreate(
            ['user1_id' => $sender_id, 'user2_id' => $recipient_id],
            ['user1_id' => $recipient_id, 'user2_id' => $sender_id]
        );

        $message = new Message();
        $message->sender_id = $sender_id;
        $message->conversation_id = $conversation->id;
        $message->subject = $request->subject;
        $message->body = $request->message;
        $message->read = false;
        $message->save();

        $recipient = User::find($recipient_id);
        $recipient->notify(new MessageNotification($message));

        return back()->with('success', 'Message sent successfully!');
    }

    public function sent()
    {
        $user_id = auth()->id();
        $messages = Message::where('sender_id', $user_id)->latest()->get();
        $sentMessages = Message::where('sender_id', $user_id)->latest()->get();
        return view('dashboard.messages.sent', compact('messages', 'sentMessages'));
    }

    public function received()
    {
        $user_id = auth()->id();
        $conversations = Conversation::where('user1_id', $user_id)->orWhere('user2_id', $user_id)->pluck('id');
        $messages = Message::whereIn('conversation_id', $conversations)
            ->where('sender_id', '!=', $user_id)
            ->latest()
            ->get();
        return view('dashboard.messages.received', compact('messages'));
    }

    public function important()
    {
        return view('dashboard.messages.important', ['messages' => Message::where('is_important', true)->get()]);
    }

    public function drafts()
    {
        $user_id = auth()->id(); // احصل على معرف المستخدم الحالي

        // جلب الرسائل المحفوظة كمسودات
        $draftMessages = Message::where('sender_id', $user_id)
                                 ->where('is_draft', true)
                                 ->latest()
                                 ->get();

        // تمرير الرسائل إلى صفحة العرض
        return view('dashboard.messages.drafts', compact('draftMessages'));
    }


    public function trash(Request $request)
{
    if ($request->isMethod('delete')) {
        $messageIds = $request->input('selected_messages');

        if ($messageIds) {
            $messageIdsArray = explode(',', $messageIds); 
            Message::whereIn('id', $messageIdsArray)->delete();
            return redirect()->back()->with('success', 'Selected messages deleted successfully.');
        }
    }

    $user_id = auth()->id();
    $sentMessages = Message::where('sender_id', $user_id)->latest()->get();
    $conversations = Conversation::where('user1_id', $user_id)->orWhere('user2_id', $user_id)->pluck('id');
    $totalMessages = Message::where('sender_id', $user_id)
        ->orWhereHas('conversation', function ($query) use ($user_id) {
            $query->where('user1_id', $user_id)->orWhere('user2_id', $user_id);
        })
        ->count();
    $unreadMessages = Message::whereIn('conversation_id', $conversations)
        ->where('sender_id', '!=', $user_id)
        ->where('read', false)
        ->latest()
        ->get();
    return view('dashboard.messages.trash', compact('sentMessages', 'unreadMessages'));
}

public function toggleImportant($id)
{
    $message = Message::findOrFail($id);
    $message->is_important = !$message->is_important;
    $message->save();

    return redirect()->back()->with('success', 'Message importance toggled.');
}


    public function show($id)
    {
        $message = Message::findOrFail($id);
        return view('dashboard.messages.show', compact('message'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply_body' => 'required|string',
        ]);

        $originalMessage = Message::findOrFail($id);

        $replyMessage = new Message();
        $replyMessage->body = $request->input('reply_body');
        $replyMessage->sender_id = auth()->id();
        $replyMessage->conversation_id = $originalMessage->conversation_id;
        $replyMessage->subject = 'Re: ' . $originalMessage->subject;
        $replyMessage->save();

        return redirect()->route('messages.show', $id)->with('success', 'Reply sent successfully.');
    }

    public function delete($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return redirect()->route('messages.index')->with('success', 'Message deleted successfully.');
    }

      // دالة لجعل الرسالة مقروءة
      public function markAsRead($id)
      {
          $message = Message::findOrFail($id);
          $message->read = true; // تعيين حالة الرسالة كمقروءة
          $message->save();

          return redirect()->back()->with('success', 'Message marked as read.');
      }
}
