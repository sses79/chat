<?php

namespace App\Services;

use Auth;
use Pusher\Pusher;
use App\Models\PuMessage as Message;

class PusherMessageService
{
    public $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options'),
        );
    }

    /**
     * create a new message to database
     *
     * @param array $data
     * @return void
     */
    public static function newMessage(array $data)
    {
        $message = new Message();
        $message->id = $data['id'];
        $message->type = $data['type'];
        $message->from_id = $data['from_id'];
        $message->to_id = $data['to_id'];
        $message->body = $data['body'];
        $message->attachment = $data['attachment'];
        $message->save();
    }

    /**
     * Fetch message by id and return the message card
     * view as a response.
     *
     * @param int $id
     * @return array
     */
    public static function fetchMessage($id)
    {
        $attachment = null;
        $attachment_type = null;
        $attachment_title = null;

        $msg = Message::where('id', $id)->first();

        return [
            'id' => $msg->id,
            'from_id' => $msg->from_id,
            'to_id' => $msg->to_id,
            'message' => $msg->body,
            'attachment' => [$attachment, $attachment_title, $attachment_type],
            'time' => $msg->created_at->diffForHumans(),
            'fullTime' => $msg->created_at,
            'viewType' => ($msg->from_id == Auth::user()->id) ? 'sender' : 'default',
            'seen' => $msg->seen,
        ];
    }

    /**
     * Return a message card with the given data.
     *
     * @param array $data
     * @param string $viewType
     */
    public static function messageCard($data, $viewType = null): string
    {
        $data['viewType'] = ($viewType) ? $viewType : $data['viewType'];
        return view('message.messageCard',$data)->render();
    }


    public function push($channel, $event, $data)
    {
        return $this->pusher->trigger($channel, $event, $data);
    }




}
