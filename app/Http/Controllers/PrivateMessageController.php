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

    public function showReply(PrivateMessage $replyTo)
    {
        return view('account.message-create')
            ->with([
               'replyTo' => $replyTo
            ]);
    }

    public function reply(PrivateMessage $replyTo)
    {
        $data = $this->request->validate(self::rules());

        $message = new PrivateMessage();

        $message->advert_id    = $replyTo->advert_id;
        $message->to_user_id   = $replyTo->from_user_id;
        $message->from_user_id = Auth::user()->id;
        $message->body         = $data['body'];
        $message->save();

        if(ajax())
            return response()->json(['success' => true, 'model' => $message], 200);

        toast()->success('Message sent successfully');
        return redirect(route('private-message.index'));
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
