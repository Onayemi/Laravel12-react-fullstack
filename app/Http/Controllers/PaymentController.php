<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\PaystackService;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Stevebauman\Location\Facades\Location;

class PaymentController extends Controller
{
    protected $paystack;

    public function __constructor(PaystackService $paystack)
    {
        $this->paystack = $paystack;
    }

    public function initialize(Request $request) {
        $client = new Client();
        // dd(config('services.paystack.secret_key'));
        try {
            $response = $client->request('POST', 'https://api.paystack.co/transaction/initialize', [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ],
                'json' => [
                    'first_name' => auth()->user()->name,
                    'last_name' => auth()->user()->name,
                    'email'  => auth()->user()->email,
                    'phone'  => '08061313253',
                    'amount' => $request->amount * 100, // amount in kobo
                    'callback_url' => route('payment.callback'), // ✅ This is the callback
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            $url = $body['data']['authorization_url'];
            if (!empty($url)) {
                // echo "<script>window.location.href = '{$url}';</script>";  
                return Inertia::location($url);                                                   
                exit;
            }

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'response' => json_decode($e->getResponse()->getBody()->getContents(), true),
            ]);
        }
    }

    public function callback(Request $request) {

        $reference = $request->query('reference');
        // dd($reference);
        // $response = $this->paystack->verifyTransaction($reference);

        // if ($response['status'] && $response['data']['status'] == 'success') {
        //     // Handle successful transaction
        // } else {
        //     // Handle failed transaction
        // }

        // $client = new Client([
        //     'base_uri' => 'https://api.paystack.co',
        // ]);
        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->request('GET', "https://api.paystack.co/transaction/verify/{$reference}", [
                'verify' => true, // disable SSL validation (for local dev)
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
                    'Accept'        => 'application/json',
                ],
            ]);

            $body = json_decode($response->getBody(), true);
            // dd($body);
            // Get ip Address
            $ip = $request->ip();
        
            $paymentDetails = $body['data'];
            $position = Location::get($paymentDetails['ip_address']);
            // dd($body, "ip", $position);
            if ($paymentDetails['status'] && $paymentDetails['status'] == 'success') {
                // Handle successful transaction
                $dd = [
                    'status' => $body['status'],
                    'message' => $body['message'],
                    'id' => $paymentDetails['id'],
                    'status' => $paymentDetails['status'],
                    'reference' => $paymentDetails['reference'],
                    'transaction_date' => $paymentDetails['transaction_date'],
                    'amount' => $paymentDetails['amount'] / 100,
                    'gateway_response' => $paymentDetails['gateway_response'],
                    'paid_at' => $paymentDetails['paid_at'],
                    'channel' => $paymentDetails['channel'],
                    'currency' => $paymentDetails['currency'],
                    'ip_address' => $paymentDetails['ip_address'],
                    'fees' => $paymentDetails['fees'] / 100,
                    'referrer' => $paymentDetails['metadata']['referrer'],
                    'last4' => $paymentDetails['authorization']['authorization_code'],
                    'channel' => $paymentDetails['authorization']['channel'],
                    'card_type' => $paymentDetails['authorization']['card_type'],
                    'country_code' => $paymentDetails['authorization']['country_code'],
                    'customer_id' => $paymentDetails['customer']['id'],
                    'email' => $paymentDetails['customer']['email'],
                    'customer_code' => $paymentDetails['customer']['customer_code'],
                    'phone' => '08061313253',
                    'first_name' => auth()->user()->name,
                    'country_name' => $position->countryName,
                    'country_code' => $position->countryCode,
                    'timezone' => $position->timezone,
                ];
                // dd($dd);
                // // Insert into Product table
                // Transaction::create([
                //     'user_id' => auth()->user()->id,
                // ]);
                // // Insert into Transaction table
                $amount= $paymentDetails['amount'] / 100;
                Transaction::create([
                    'user_id' => auth()->user()->id,
                    'reference' => $paymentDetails['reference'],
                    'amount' => $amount,
                    'status' => $paymentDetails['status'],
                    'gateway_response' => $paymentDetails['gateway_response'],
                    'ip_address' => $position->ip,
                    'country_name' => $position->countryName,
                    'country_code' => $position->countryCode,
                    'timezone' => $position->timezone,
                ]);
                // return to_route('/products');
                return to_route('products.index')->with('message', 'Product payment is successfully!');
            } else {
                // Handle failed transaction
            }
            // if ($body['status'] && $body['data']['status'] == 'success') {
            //     // Handle successful transaction
            //     // exit;
            // } else {
            //     // Handle failed transaction
            // }
            // dd($url);  // "https://checkout.paystack.com/5d0uw1eikk2h5ey"
            // if (!empty($url)) {
            //     echo "<script>window.location.href = '{$url}';</script>";
            //     exit;
            // }

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'response' => json_decode($e->getResponse()->getBody()->getContents(), true),
            ]);
        }

        // dd($response);
        // return response()->json($response->json());
        
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

    public function testPaystack(Request $request){
        $client = new Client();
        // dd(config('services.paystack.secret_key'));
        try {
            $response = $client->request('POST', 'https://api.paystack.co/transaction/initialize', [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ],
                'json' => [
                    'email'  => auth()->user()->email,
                    'amount' => $request->amount * 100, // amount in kobo
                    'callback_url' => route('payment.callback'),
                ],
            ]);

            // $body = json_decode($response->getBody(), true);
            $body = json_decode($response->getBody()->getContents(), true);
            $url = $body['data']['authorization_url'];
            // dd($url);  // "https://checkout.paystack.com/5d0uw1eikk2h5ey"
            if (!empty($url)) {
                echo "<script>window.location.href = '{$url}';</script>";
                exit;
            }

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'response' => json_decode($e->getResponse()->getBody()->getContents(), true),
            ]);
        }
    }
}
