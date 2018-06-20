<?php

namespace App\Http\Controllers;

use App\Advert;
use App\PrivateMessage;
use App\User;
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
        return view('account.message-thread-index')
            ->with([
                'threads' => PrivateMessage::allMessageThreads(Auth::user())
            ]);
    }

    public function showThread(Advert $advert)
    {
        $messages = $advert->threadMessages()->sortBy('created_at');

        PrivateMessage
            ::whereIn('id', $messages->pluck('id'))
            ->where('to_user_id', Auth::user()->id)
            ->update([
                'read' => true,
                'read_at' => now()
            ]);

        return view('account.message-thread-show')
            ->with([
                'advert' => $advert,
                'messages' => $messages->all(),
            ]);
    }

    public function store(Advert $advert, User $user)
    {
        $data = $this->request->validate(self::rules());

        $message = new PrivateMessage();

        $message->advert_id    = $advert->id;
        $message->to_user_id   = $user->id;
        $message->from_user_id = Auth::user()->id;
        $message->body         = $data['body'];
        $message->save();

        if(ajax())
            return response()->json(['success' => true, 'model' => $message], 200);

        toast()->success('Message sent successfully');
        return back();
    }
}
