<?php

namespace App\Http\Livewire;

use App\Events\NewMessage;
use App\Models\PuMessage;
use App\Models\PuMessage as Message;
use App\Models\User;
use App\Services\PusherMessageService;
use Auth;
use DB;
use Livewire\Component;

class ShowMessage extends Component
{
    public $messages;
    public $here = [];
    public $messageBody = '';
    public int $toId;
    public $toUser;
    public $showNewMessageNotification = false;
    public $newMessage;

//    protected $listeners = ['updateToId'];
//    protected $listeners = ['echo:pu_message,NewMessage' => 'notifyNewMessage'];

//    protected $listeners = [
//        'echo-presence:demo,here' => '≤here',
//        'echo-presence:demo,joining' => 'joining',
//        'echo-presence:demo,leaving' => 'leaving',
//    ];

    public function render()
    {
        return view('livewire.show-message');
    }

    public function mount()
    {
        $this->toId = 2;
        $this->updateMessages();
        $this->updateToUser();
        $this->users = User::where('id', '<>', Auth::user()->id)->get();
    }

    public function getListeners()
    {
        return [
            "updateToId",
            "echo:pu_message,NewMessage" => "notifyNewMessage"
//            "echo-private:orders.{$this->orderId},OrderShipped" => 'notifyNewOrder',
//            // Or:
//            "echo-presence:orders.{$this->orderId},OrderShipped" => 'notifyNewOrder',
        ];
    }

    public function notifyNewMessage($data)
    {
        if (is_array($data['message'])) {
            if ($data['message']['to_id'] == Auth::user()->id) {
                $newMessage = Message::where('id', $data['message']['id'])->first();
                $newMessage->seen = 1;
                $newMessage->save();

                $this->showNewMessageNotification = true;
                $this->updateMessages();
            }
        }
    }

    public function sendMessage()
    {
        if (!$this->messageBody) {
            $this->addError('messageBody', 'Message body is required.');
            return;
        }

        $messageID = mt_rand(9, 999999999) + time();
        PusherMessageService::newMessage([
            'id' => $messageID,
            'type' => 'user',
            'from_id' => Auth::user()->id,
            'to_id' => $this->toId,
            'body' => trim($this->messageBody),
            'attachment' => null,
        ]);

        NewMessage::dispatch([
            'from_id' => Auth::user()->id,
            'to_id' => $this->toId,
            'id' => $messageID
        ]);

        $this->messageBody = '';
        $this->updateMessages();

//        $message = Auth::user()->messages()->create([
//            'body' => $body,
//        ]);
//
//        $message->load('user');
//
//        broadcast(new MessageSentEvent($message))->toOthers();
//
//        array_push($this->messages, $message);
    }

    public function updateToId($toId)
    {
        $this->toId = $toId;
        $this->updateToUser();
        $this->updateMessages();
    }

    public function updateToUser()
    {
        $toId = $this->toId;
        $this->toUser = User::where('id', $toId)->first();
    }

    public function updateMessages()
    {
        $toId = $this->toId;
        $this->messages = DB::table('pu_messages')
            ->where('from_id', Auth::user()->id)
            ->where('to_id', $toId)
            ->orWhere(function ($query) use ($toId) {
                $query->where('from_id', $toId)
                    ->where('to_id', Auth::user()->id);
            })
            ->latest()
            ->limit(10)
            ->get()->sortBy('created_at')->values()->all();

        // Update all unseen messages
        DB::table('pu_messages')
            ->where('from_id', $toId)
            ->where('to_id', Auth::user()->id)
            ->update(['seen' => 1]);
    }

    /**
     *
     * @param $message
     */
    public function incomingMessage($message)
    {
        // get the hydrated model from incoming json/array.
        $message = Message::with('user')->find($message['id']);

        array_push($this->messages, $message);
    }

    /**
     * @param $data
     */
    public function here($data)
    {
        $this->here = $data;
    }

    /**
     * @param $data
     */
    public function leaving($data)
    {
        $here = collect($this->here);

        $firstIndex = $here->search(function ($authData) use ($data) {
            return $authData['id'] == $data['id'];
        });

        $here->splice($firstIndex, 1);

        $this->here = $here->toArray();
    }

    /**
     * @param $data
     */
    public function joining($data)
    {
        $this->here[] = $data;
    }

}
