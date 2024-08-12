<?php

namespace App\Livewire;

use Auth;
use \App\Models\Chat;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Events\ChatSendEvent;

class ChatComponent extends Component
{
    public $user;
    public $sender_id;
    public $receiver_id;
    public $message;
    public $messages = [];

    public function render()
    {
        return view('livewire.chat-component');
    }

    public function mount($user_id) {
        $this->sender_id = Auth::id();
        $this->receiver_id = $user_id;

        $messages = Chat::where(function($query) {
            $query->where('sender_id', $this->sender_id)
                ->orWhere('receiver_id', $this->receiver_id);
        })
        ->orWhere(function($query) {
            $query->where('sender_id', $this->receiver_id)
                ->orWhere('receiver_id', $this->sender_id);
        })
        ->with('sender:id,name', 'receiver:id,name')
        ->get();

        foreach ($messages as $message) {
            $this->appendChat($message);
        }

        $this->user = \App\Models\User::find($user_id);
    }

    public function sendMessage()
    {
        $chat = new Chat();
        $chat->sender_id = $this->sender_id;
        $chat->receiver_id = $this->receiver_id;
        $chat->message = $this->message;
        $chat->save();

        $this->appendChat($chat);

        // broadcast the chat message
        broadcast(new ChatSendEvent($chat))->toOthers();

        $this->message = '';
    }

    #[On('echo-private:chat-channel.{sender_id},ChatSendEvent')]
    public function listenChat($event)
    {
        $chat = Chat::whereId($event['chat']['id'])
            ->with('sender:id,name', 'receiver:id,name')
            ->first();
        $this->appendChat($chat);
    }

    public function appendChat($message)
    {
        $this->messages[] = [
            'id' => $message->id,
            'message' => $message->message,
            'sender_id' => $message->sender->id,
            'sender' => $message->sender->name,
            'receiver_id' => $message->receiver->id,
            'receiver' => $message->receiver->name,
        ];
    }
}
