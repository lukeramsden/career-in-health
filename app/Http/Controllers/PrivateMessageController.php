<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivateMessageController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
