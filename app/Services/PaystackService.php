<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class PaystackService 
{
    protected $paystack;

    public function __constructor(PaystackService $paystack)
    {
        $this->paystack = $paystack;
    }

    public function initializeTransaction(Request $request, $paystackUrl) {
        $client = new Client();
        try {
            $response = $client->request('POST', $paystackUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ],
                'json' => [
                    'email'  => auth()->user()->email,
                    'phone'  => '08061313253',
                    'amount' => $request->amount * 100, // amount in kobo
                    'callback_url' => route('payment.callback'), // ✅ This is the callback
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            $callback_url = $body['data']['authorization_url'];
            if (!empty($callback_url)) {
                return Inertia::location($callback_url);                                                   
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

    public function verifyTransaction(Request $request, $paystackUrl){
        $reference = $request->query('reference');
        $client = new \GuzzleHttp\Client();

        try {
            // https://api.paystack.co/transaction/verify
            $response = $client->request('GET', "{$paystackUrl}/{$reference}", [
                'verify' => true, // disable SSL validation (for local dev)
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
                    'Accept'        => 'application/json',
                ],
            ]);
            $body = json_decode($response->getBody(), true);
            dd($body);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'response' => json_decode($e->getResponse()->getBody()->getContents(), true),
            ]);
        }
            $body = json_decode($response->getBody(), true);
    }

}