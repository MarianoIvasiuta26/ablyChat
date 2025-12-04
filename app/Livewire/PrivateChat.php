<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\PrivateMessage;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class PrivateChat extends Component
{
    public $users;
    public $activeConversation = null;
    public $selectedUserId = null;
    public $messages = [];
    public $content = '';

    public function mount()
    {
        $this->users = User::where('id', '!=', auth()->id())->get();
    }

    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;

        // Find or create conversation
        $conversation = Conversation::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->whereHas('users', function ($query) {
            $query->where('user_id', auth()->id());
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create(['last_message_at' => now()]);
            $conversation->users()->attach([auth()->id(), $userId]);
        }

        $this->activeConversation = $conversation;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        if (!$this->activeConversation)
            return;

        $this->messages = $this->activeConversation->messages()
            ->with('user')
            ->latest()
            ->take(50)
            ->get()
            ->sortBy('created_at')
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'user_id' => $message->user_id,
                    'username' => $message->user->name,
                    'content' => $message->content,
                    'created_at' => $message->created_at->diffForHumans(),
                ];
            })
            ->toArray();

        $this->dispatch('message-sent');
    }

    public function getListeners()
    {
        return [
            "echo-private:App.Models.User." . auth()->id() . ",PrivateMessageSent" => 'messageReceived',
        ];
    }

    public function messageReceived($event)
    {
        $incomingMessage = $event['message'];
        $authId = auth()->id();
        $senderId = $incomingMessage['user_id'];

        \Illuminate\Support\Facades\Log::info('MessageReceived', [
            'auth_id' => $authId,
            'sender_id' => $senderId,
            'msg_id' => $incomingMessage['id'],
            'is_own' => $authId == $senderId
        ]);

        // Ignore own messages
        if ($authId == $senderId) {
            \Illuminate\Support\Facades\Log::info('Ignoring own message');
            return;
        }

        // Only handle messages for the active conversation
        if ($this->activeConversation && $incomingMessage['conversation_id'] === $this->activeConversation->id) {
            $this->messages[] = $incomingMessage;
            $this->dispatch('message-sent');
        }
    }

    public function sendMessage()
    {
        $this->validate([
            'content' => 'required|string|max:500',
        ]);

        if (!$this->activeConversation)
            return;

        $message = $this->activeConversation->messages()->create([
            'user_id' => auth()->id(),
            'content' => $this->content,
        ]);

        $this->activeConversation->update(['last_message_at' => now()]);


        broadcast(new \App\Events\PrivateMessageSent($message));

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

    public function render()
    {
        return view('livewire.private-chat');
    }
}
