<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PaymentController extends Controller
{
    protected $paystack;

    public function __constructor(PaystackService $paystack)
    {
        $this->paystack = $paystack;
    }

    public function initialize(Request $request) {
        // ✅ Step 1: Validate the request
        $request->validate([
            'amount' => 'required|numeric|min:100'
        ]);

        // $data = [
        //     'email' => auth()->user()->email,
        //     'amount' => $request->amount * 100, // convert to Kobo
        //     'metadata' => [
        //         'title' => $request->title,
        //         'content' => $request->content
        //     ],
        //     'callback_url' => route('payment.callback'),
        // ];

        $paystackSecret = env('PAYSTACK_SECRET_KEY');
        $amount = $request->amount * 100; // Convert to kobo

        $response = Http::withToken($paystackSecret)->post('https://api.paystack.co/transaction/initialize', [
            'email' => auth()->user()->email, // $request->email,
            'amount' => $amount,
            'callback_url' => url('/payment/callback'),
        ]);
        dd([$response]);
        // $response = Http::withToken($paystackSecret)->post('https://api.paystack.co/transaction/initialize', [
        //     'email' => $request->email,
        //     'amount' => $amount,
        //     'callback_url' => url('/api/payment/callback'),
        // ])->json();
        // dd($response['data']['authorization_url']); // "https://checkout.paystack.com/bf3bkmj3801jast"
        // return Inertia::forceRedirect($response['data']['authorization_url']);
        return Inertia::location($response['data']['authorization_url']);
        // if ($response['status']) {

        // }    
        // return back()->with('error', 'Unable to initialize payment');


        // if ($response['status']) {
        //     return redirect($response['data']['authorization_url']);
        // }

        // return back()->with('error', 'Payment initialization failed');
    }

    public function callback(Request $request) {
        $reference = $request->query('reference');
        $paystackSecret = env('PAYSTACK_SECRET_KEY');

        $response = Http::withToken($paystackSecret)
            ->get("https://checkout.paystack.com/transaction/verify/{$reference}");
            // ->get("https://api.paystack.co/transaction/verify/{$reference}");

        // dd($response);
        return response()->json($response->json());
        
        //  if(app()->environment('local') && !$request>has('reference')) {
        //     $mockResponse = [
        //         'status' => true,
        //         'data' => [
        //             'status' => 'success',
        //             'reference' => 'TEST_'.rand(1000, 9999),
        //             'amount' => $request->amount * 100, // convert to Kobo
        //             // 'email' => auth()->user()->email,
        //             'metadata' => $request->session()->get('payment_details', [
        //                 'name' => $request->name,
        //                 'phone' => '08061313253'
        //             ]),
        //             'customer' => [
        //                 'email' => $request->session()->get('payment_deails_email', 'test@example.com')
        //             ],
        //         ],
        //     ];

        //     // $paymentDetails = $mockResponse['data'];
        //     // 'name' => $paymentDetails['metadata']['name'];
        //     // 'email' => $paymentDetails['customer']['email'];
        //     // 'amount' => $paymentDetails['amount'] / 100;
        //     // 'reference' => $paymentDetails['reference'];
        //  }

        //  $reference = $request->query('reference');
        //  $response = $this->paystack->verifyTransaction($reference);

        //  dd($response);
        //  if ($response['status']&& $response['data']['status'] === 'success') {
        //     $paymentDetails = $response['data'];
        //     // 'name' => $paymentDetails['metadata']['name'];
        //     // 'email' => $paymentDetails['customer']['email'];
        //     // 'amount' => $paymentDetails['amount'] / 100;
        //     // 'reference' => $paymentDetails['reference'];

        //     // success redirect message
        //  }
        //  return redirect()->route('payment.form')->with('error', 'Payment failed or  was cancelled');
    }
}
