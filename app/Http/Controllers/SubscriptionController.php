<?php

namespace App\Http\Controllers;

use Auth;
use Stripe\Token;
use Stripe\Stripe;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

    public function payment()
    {
        return view('subscription.payment')
            ->with([

            ]);
    }

    public function makePayment(Request $request)
    {
        $request->validate([
            'number' => 'required',
            'exp_month' => 'required',
            'exp_year' => 'required',
            'ccv' => 'required',
        ]);

        Stripe::setApiKey(env('STRIPE_KEY'));

        $token = Token::create([
            'card' => [
                'number' => $request->number,
                'exp_month' => $request->exp_month,
                'exp_year' => $request->exp_year,
                'cvc' => $request->ccv,
                'currency' => 'GBP',
            ]
        ]);
        
        Auth::user()->newSubscription('Standard Packages', 'starter')
            ->create($token->id, [
                'email' => Auth::user()->email
            ]);

        return redirect('/home');
    }

}
