<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivateMessage extends Model
{
    protected $fillable = [];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function advert()
    {
        return $this->belongsTo(Advert::class);
    }

    public function company()
    {
        return $this->advert->company;
    }

    public function markAsRead()
    {
        if($this->read) return; // don't execute query if it does nothing

        $this->read = true;
        $this->read_at = now();
        $this->save();
    }

    public function markAsUnread()
    {
        if(!$this->read) return;

        $this->read = false;
        $this->read_at = null;
        $this->save();
    }

    public static function allMessageThreads(User $user = null)
    {
        if ($user == null) return null;

        return Advert::whereIn(
            'id',
            static::where(function($query) use ($user) {
                $query
                    ->where('to_user_id', $user->id)
                    ->orWhere('from_user_id', $user->id);
            })->pluck('advert_id')
        )->get();
    }

    public static function getThread(User $user = null, Advert $advert = null)
    {
        if ($user == null || $advert == null) return null;

        $messages = static::whereAdvertId($advert->id);

        $messages->where(function($query) use ($user) {
            $query
                ->where('to_user_id', $user->id)
                ->orWhere('from_user_id', $user->id);
        });

        return $messages
            ->orderByDesc('created_at');
    }
}
