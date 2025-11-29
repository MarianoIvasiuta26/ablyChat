<?php
namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $messages = Message::latest()
            ->take(50)
            ->orderBy('created_at')
            ->get();

        return view('chat', compact('messages'));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:50',
            'content'   => 'required|string|max:500',
        ]);

        $message = Message::create($data);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }
}

