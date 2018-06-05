<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivateMessage extends Model
{
    protected $fillable = [];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'id', 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'id', 'to_user_id');
    }

    public function advert()
    {
        return $this->belongsTo(Advert::class, 'id', 'advert_id');
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
}
