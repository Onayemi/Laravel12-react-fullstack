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
        // $this->secretKey = config('services.paystack.secret_key');
        $this->secretKey = config('services.paystack.secret_key');
        $this->baseUrl = config('services.paystack.base_url');
    }

    public function initializeTransaction(array $data) {
        // $response = $this->client->post('/transaction/initialize', [
        //     'json' => $data
        // ]);
        // return json_decode($response->getBody(), true);
        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/transaction/initialize", $data);

        return $response->json();
    }

    public function verifyTransaction(string $reference){
        // $response = $this->client->get("/transaction/verify/{$reference}");
        // return json_decode($response->getBody(), true);
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/transaction/verify/{$reference}");

        return $response->json();
    }

}