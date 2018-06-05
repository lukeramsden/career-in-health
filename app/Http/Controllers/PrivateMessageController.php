<?php

namespace App\Http\Controllers;

use App\PrivateMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivateMessageController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $messages = Auth::user()
            ->receivedMessages();

        if($this->request->get('filterRead'))
            $messages->where('read', false);

        $messages
            ->orderBy('created_at')
            ->with('fromUser', 'advert', 'company');

        return view('account.message-index')
            ->with([
                'messages' => $messages->paginate(10)
            ]);
    }

    public function indexSent()
    {
        $messages = Auth::user()
            ->sentMessages();

        if($this->request->get('filterRead'))
            $messages->where('read', false);

        $messages
            ->orderBy('created_at')
            ->with('toUser', 'advert', 'company');

        return view('account.message-index-sent')
            ->with([
                'messages' => $messages->paginate(10)
            ]);
    }
}
