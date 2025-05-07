<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class ProductController extends Controller
{
    protected $paystack;

    public function __constructor(PaystackService $paystack)
    {
        $this->paystack = $paystack;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->products()->latest();
        if($request->has('search') && $request->search !== null) {
            $query->whereAny(['title', 'content'], 'like', '%' . $request->search . '%');
        }
        $products = $query->paginate(10)->toArray();
        return Inertia::render('products/index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('products/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ Step 1: Validate the request
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $data = [
            'email' => auth()->user()->email,
            'amount' => $request->amount * 100, // convert to Kobo
            'metadata' => [
                'title' => $request->title,
                'content' => $request->content
            ],
            'callback_url' => route('payment.callback'),
        ];

        $response = $this->paystack->initializeTransaction($data);
        // $url = 'https://api.paystack.co/transaction/initialize';
        // $dd = $this->paystack->initializeTransaction($email, $url);

        return redirect($response['data']['authorization_url']);
    }

    public function initialize(Request $request) {
        // ✅ Step 1: Validate the request
        $request->validate([
            'amount' => 'required|numeric|min:100'
        ]);

        $data = [
            'email' => auth()->user()->email,
            'amount' => $request->amount * 100, // convert to Kobo
            'metadata' => [
                'title' => $request->title,
                'content' => $request->content
            ],
            'callback_url' => route('payment.callback'),
        ];

        $response = $this->paystack->initializeTransaction($data);
        return redirect($response['data']['authorization_url']);
    }

    public function callback(Request $request) {
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

         $reference = $request->query('reference');
         $response = $this->paystack->verifyTransaction($reference);

         dd($response);
         if ($response['status']&& $response['data']['status'] === 'success') {
            $paymentDetails = $response['data'];
            // 'name' => $paymentDetails['metadata']['name'];
            // 'email' => $paymentDetails['customer']['email'];
            // 'amount' => $paymentDetails['amount'] / 100;
            // 'reference' => $paymentDetails['reference'];

            // success redirect message
         }
         return redirect()->route('payment.form')->with('error', 'Payment failed or  was cancelled');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
