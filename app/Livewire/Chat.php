<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\Message;
use Livewire\Attributes\On;
use Livewire\Component;

class Chat extends Component
{
    public $messages = [];
    public $content = '';

    public function mount()
    {
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = Message::with('user')
            ->latest()
            ->take(50)
            ->orderBy('created_at')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'username' => $message->user->name ?? $message->username,
                    'content' => $message->content,
                    'created_at' => $message->created_at->diffForHumans(),
                ];
            })
            ->toArray();
    }

    public function sendMessage()
    {
        $this->validate([
            'content' => 'required|string|max:500',
        ]);

        $message = Message::create([
            'user_id' => auth()->id(),
            'username' => auth()->user()->name,
            'content' => $this->content,
        ]);

        broadcast(new MessageSent($message));

        // Add message to current user's view immediately
        $this->messages[] = [
            'id' => $message->id,
            'username' => auth()->user()->name,
            'content' => $message->content,
            'created_at' => 'ahora',
        ];

        $this->content = '';
    }

    #[On('echo:chat.global,MessageSent')]
    public function messageReceived($data)
    {
        $this->messages[] = $data['message'];
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
