<?php

namespace App\Http\Controllers;

use App\PrivateMessage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivateMessageController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth');
        $this->middleware(function($request, Closure $next) {
            $message = $request->route('message');

            $authUser = Auth::user();
            $toUser = optional($message)->toUser;
            if($message && Auth::check() && $authUser->id != $toUser->id) {
                debug($authUser->id);
                debug($toUser->id);
                toast()->error('Cannot change read status of a message you didn\'t receive.');
                return back();
            }

            return $next($request);
        })->only('markAsRead', 'markAsUnread');
    }

    public function index()
    {
        $messages = Auth::user()
            ->receivedMessages();

        if($this->request->get('filterRead'))
            $messages->where('read', false);

        $messages
            ->orderBy('created_at');

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
            ->orderBy('created_at');

        return view('account.message-index-sent')
            ->with([
                'messages' => $messages->paginate(10),
            ]);
    }

    public function show(PrivateMessage $message)
    {
        $message->markAsRead();

        return view('account.message-show')
            ->with([
                'message' => $message,
            ]);
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
