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
            ->get()
            ->sortBy('created_at')
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'user_id' => $message->user_id,
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
            'user_id' => auth()->id(),
            'username' => auth()->user()->name,
            'content' => $message->content,
            'created_at' => 'ahora',
        ];

        $this->content = '';

        $this->dispatch('message-sent');
    }

    #[On('echo:chat.global,MessageSent')]
    public function messageReceived($data)
    {
        $incomingMessage = $data['message'];

        // Check if message already exists to prevent duplicates
        foreach ($this->messages as $msg) {
            if ($msg['id'] == $incomingMessage['id']) {
                return;
            }
        }

        $this->messages[] = $incomingMessage;

        $this->dispatch('message-sent');
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
