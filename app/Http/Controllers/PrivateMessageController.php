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
            ->messages()
            ->orderBy('created_at')
            ->with('user', 'advert', 'advert.company');

        return view('account.message-index')
            ->with([
                'messages' => $messages->get()
            ]);
    }

    public function show(PrivateMessage $message)
    {
        $message->markAsRead();
        return view('account.message-show')
            ->with(['message' => $message]);
    }

    public function markAsRead(PrivateMessage $message)
    {
        $message->markAsRead();

        if(ajax())
            return response()->json(['success' => true], 204);

        toast()->success('Message has been marked as read.');
        return back();
    }

    public function markAsUnread(PrivateMessage $message)
    {
        $message->markAsUnread();

        if(ajax())
            return response()->json(['success' => true], 204);

        toast()->success('Message has been marked as unread.');
        return back();
    }
}
