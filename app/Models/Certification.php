<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Certification extends Model
{
    protected $fillable = ['name'];

    public function url()
    {
        return Storage::url('app/' . $this->file_path);
    }

    public function download_resp()
    {
        return response()->download(storage_path('app/' . $this->file_path),
            $this->profile->first_name . $this->profile->last_name . '-' . $this->name);
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }
}
