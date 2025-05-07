<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class PaystackService 
{
    // protected $client;
    protected $secretKey;
    protected $baseUrl;

    public function __constructor()
    {
        // $this->client = new Client([
        //     'base_url' => 'https://api.paystack.co',
        //     'headers' => [
        //         'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
        //         'Content-Type' => 'application/json',
        //     ],
        //     'verify' => true // Add this line to disable SSL verification
        // ]);
        $client = new Client([
            'base_uri' => config('services.paystack.base_url'),
            'headers' => [
                'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
                'Accept' => 'application/json',
            ]
        ]);

        // $this->secretKey = config('services.paystack.secret_key');

        // $this->secretKey = config('services.paystack.secret_key');
        // $this->baseUrl = config('services.paystack.base_url');

        // $this->baseUrl = config('services.paystack.base_url', 'https://api.paystack.co');
        // $this->secretKey = config('services.paystack.secret');
    }

    public function initializeTransaction(array $data) {
        $response = $client->post('/transaction/initialize', [
            'json' => $data
        ]);
        return json_decode($response->getBody(), true);
        // $response = $client->post('/transaction/initialize', [
        //     'json' => [
        //         'email' => auth()->user()->email,
        //         'amount' => $request->amount * 100, // in kobo
        //     ]
        // ]);
        
        // $data = json_decode($response->getBody(), true);

        // return Http::withToken($this->secretKey)
        //            ->post("{$this->baseUrl}/transaction/initialize", $data)
        //            ->json();

        // $response = Http::withToken($this->secretKey)
        //     ->post("{$this->baseUrl}/transaction/initialize", $data);

        // return $response->json();
    }

    public function verifyTransaction(string $reference){
        $response = $client->get("/transaction/verify/{$reference}");
        return json_decode($response->getBody(), true);
        
        // return Http::withToken($this->secretKey)
        //            ->get("{$this->baseUrl}/transaction/verify/{$reference}")
        //            ->json();
        // $response = Http::withToken($this->secretKey)
        //     ->get("{$this->baseUrl}/transaction/verify/{$reference}");

        // return $response->json();
    }

}