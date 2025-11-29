<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\Message;
use Livewire\Attributes\On;
use Livewire\Component;

class Chat extends Component
{
    public $messages = [];
    public $username = 'Alumno';
    public $content = '';

    public function mount()
    {
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = Message::latest()
            ->take(50)
            ->orderBy('created_at')
            ->get()
            ->toArray();
    }

    public function sendMessage()
    {
        $this->validate([
            'username' => 'required|string|max:50',
            'content' => 'required|string|max:500',
        ]);

        $message = Message::create([
            'username' => $this->username,
            'content' => $this->content,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        // Add message to current user's view immediately
        $this->messages[] = $message->toArray();

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
