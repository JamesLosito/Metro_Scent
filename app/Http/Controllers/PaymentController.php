<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class PaymentController extends Controller
{
    public function redirectToStripe(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = CheckoutSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'php',
                        'product_data' => [
                            'name' => 'Order #' . $request->order_id,
                        ],
                        'unit_amount' => $request->total * 100, // in cents
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel'),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        return view('payment.success');
    }

    public function cancel()
    {
        return view('payment.cancel');
    }
}

