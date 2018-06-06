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

            if($message && Auth::check() && Auth::user()->id != optional($message)->toUser->id) {
                toast()->error('Cannot change read status of a message you didn\'t receive.');
                return back();
            }

            return $next($request);
        })->only('markAsRead', 'markAsUnread');
    }

    protected function rules()
    {
        return [
            'body' => 'required|string|max:1000'
        ];
    }

    public function index()
    {
        $messages = Auth::user()
            ->receivedMessages();

        if($this->request->get('filterRead'))
            $messages->where('read', false);

        $messages
            ->orderByDesc('created_at');

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
            ->orderByDesc('created_at');

        return view('account.message-index-sent')
            ->with([
                'messages' => $messages->paginate(10),
            ]);
    }

    public function showReply(PrivateMessage $message)
    {
        return view('account.message-create')
            ->with([
                'replyTo' => $message
            ]);
    }

    public function reply(PrivateMessage $message)
    {
        $data = $this->request->validate(self::rules());

        $newMessage = new PrivateMessage();

        $newMessage->advert_id    = $message->advert_id;
        $newMessage->to_user_id   = $message->from_user_id;
        $newMessage->from_user_id = Auth::user()->id;
        $newMessage->body         = $data['body'];
        $newMessage->save();

        if(ajax())
            return response()->json(['success' => true, 'model' => $newMessage], 200);

        toast()->success('Message sent successfully');
        return redirect(route('account.private-message.index'));
    }

    public function show(PrivateMessage $message)
    {
        $isReceiver = Auth::user()->id == optional($message)->toUser->id;

        if($isReceiver)
            $message->markAsRead();

        return view('account.message-show')
            ->with([
                'message' => $message,
                'isReceiver' => $isReceiver,
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
