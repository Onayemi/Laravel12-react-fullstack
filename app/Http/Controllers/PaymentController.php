<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Services\PaystackService;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
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
        // dd(config('services.flutterwave.secret_key'));
        try {
            // $response = $client->request('POST', 'https://api.flutterwave.com/v3/payments', [
            $response = $client->request('POST', 'https://api.paystack.co/transaction/initialize', [
                'headers' => [
                    // 'Authorization' => 'Bearer FLWSECK_TEST-680bb5881d03c5980213208a5bfdc190-X', //config('services.flutterwave.secret_key'),
                    // 'Authorization' => 'Bearer ' . config('services.flutterwave.secret_key'),
                    'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ],
                // Paystack data
                'json' => [
                    'email'  => auth()->user()->email,
                    'amount' => $request->amount * 100, // amount in kobo
                    'metadata' => [
                        'first_name' => auth()->user()->name,
                        'last_name' => auth()->user()->name,
                        'phone'  => '08061313253',
                        'title' => $request->title,
                        'category' => $request->category,
                        'content' => $request->content,
                    ],
                    'callback_url' => route('payment.callback'), // ✅ This is the callback
                ],

                //// Flutterwave data
                // 'json' => [
                //     'tx_ref' => mt_rand(10000000, 99999999), // Str::random(9),
                //     'amount' => $request->amount,
                //     'currency' =>  'NGN',
                //     'redirect_url' => route('payment.callback'),
                //     'customer' => array(
                //         'email' => auth()->user()->email,
                //         'name' => auth()->user()->name,
                //         'phonenumber' => '09012345678',
                //     ),
                // ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            // dd($body);
            $addProduct = Product::create([
                'user_id' => auth()->user()->id,
                'prod_name' => $request->title,
                'slug' => Str::slug($request->title),
                'category' => $request->category,
                'status' => $request->status,
                'price' => $request->amount,
                'description' => $request->content,
            ]);

            //  Paystack redirect
            $url = $body['data']['authorization_url'];

            // //  Flutterwave redirect
            // $url = $body['data']['link'];
            if (!empty($url)) {
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
        // Paystack query
        $reference = $request->query('reference');
        // // Flutterwave query
        // $reference = $request->query('tx_ref');
        // $transactionId = $request->query('transaction_id');
        // dd($reference);

        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('GET', "https://api.paystack.co/transaction/verify/{$reference}", [
            // $response = $client->request('GET', "https://api.flutterwave.com/v3/transactions/{$transactionId}/verify", [
                // https://api.flutterwave.com/v3/transactions/123456/verify
                'verify' => true, // disable SSL validation (for local dev)
                'headers' => [
                    // 'Authorization' => 'Bearer ' . config('services.flutterwave.secret_key'),
                    'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
                    'Accept'        => 'application/json',
                ],
            ]);
            // dd($response);
            // $body = json_decode($response->getBody(), true);
            $body = json_decode($response->getBody()->getContents(), true);
            // dd($body);
            // Get ip Address
            $ip = $request->ip();
        
            $paymentDetails = $body['data'];

            // $reference = $paymentDetails['reference'];
            // $transaction =  Transaction::where('reference', $reference)->first();
            // $transaction_user = User::find($transaction->user_id);
            // $user_wallet = Wallet::find($transaction->wallet_id);

            // if($transaction->status == 'failed'){
            //     return back()->with(['error' => 'Failed transaction, please try again']);
            // }
            // if($transaction->status == 'successful'){
            //     return back()->with(['error' => 'Transaction already processed ']);
            // }
            
            // // Add Wallet and remove the charges
            // $user_wallet->balance += $transaction->amount;
            // $user_wallet->save();
            // return back()->with(['message' => 'Payment made successfully']);

            $position = Location::get($paymentDetails['ip_address']);
            // $position = Location::get($paymentDetails['ip']);
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
                $userId = auth()->user()->id;
                // $getPrduct =  Product::where('user_id', $userId)->where()->first();
                // // Insert into Transaction table
                $amount= $paymentDetails['amount'] / 100;
                Transaction::create([
                    'user_id' => $userId,
                    'prod_name' => $paymentDetails['metadata']['title'],
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
                return back()->with('message', 'Payment failed');
                // Handle failed transaction
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
