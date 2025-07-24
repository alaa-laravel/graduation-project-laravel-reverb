<?php

namespace App\Http\Controllers;

use App\Models\message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Events\MessageDeleted;
use App\Events\MessageUpdated;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoremessageRequest;
use App\Http\Requests\UpdatemessageRequest;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function store(StoremessageRequest $request)
    {
        $data = $request->validated();
        $data['sender_id'] = Auth::id();
        $message = message::create($data);

        broadcast(new MessageSent($message))->toOthers();
        $message->load('sender');

        return response()->json([
            'status' => 201,
            'message' => 'Message sent successfully',
            'data' => $message
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(message $message)
    {
        //
    }



    public function update(Request $request, $id)
    {
        $message = Message::find($id);

        if (!$message) {
            return response()->json(['message' => 'Message not found'], 404);
        }

        if ($message->sender_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // تحقق من مرور أكثر من 20 دقيقة
        if (!$message->created_at || $message->created_at->diffInMinutes(now()) > 20) {
            return response()->json(['message' => 'Cannot edit after 20 minutes'], 403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $message->update(['message' => $request->message]);

        broadcast(new MessageUpdated($message))->toOthers();

        return response()->json([
            'status' => 200,
            'message' => 'Message updated successfully',
            'data' => $message,
        ]);
    }

    public function getConversation($receiverId)
{
    $userId = Auth::id();

    $messages = Message::where(function ($query) use ($userId, $receiverId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $receiverId);
        })
        ->orWhere(function ($query) use ($userId, $receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', $userId);
        })
        ->orderBy('created_at', 'asc')
        ->with('sender:id,name')
        ->get();

    return response()->json([
        'status' => 200,
        'message' => 'Conversation loaded successfully',
        'data' => $messages
    ]);
}


    public function destroy($id)
    {
        $message = Message::find($id);

        if (!$message) {
            return response()->json(['message' => 'Message not found'], 404);
        }

        if (!$message->canEditOrDelete()) {
            return response()->json(['message' => 'Cannot delete after 20 minutes'], 403);
        }

        if ($message->sender_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messageId = $message->id;
        $senderId = $message->sender_id;
        $receiverId = $message->receiver_id;

        $message->delete();

        broadcast(new MessageDeleted($messageId, $senderId, $receiverId))->toOthers();

        return response()->json(['message' => 'Message deleted']);
    }
}