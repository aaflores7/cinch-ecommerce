<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // For making API calls
use Illuminate\Support\Facades\DB;   // For database transactions
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\EmailController;
class OrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming data
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'products' => 'required|array',
            'products.*.product_id' => 'required|integer',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $totalAmount = 0;
        $orderItemsData = [];

        try {
            // 2. Loop through products to get details from Catalog Service
            foreach ($validated['products'] as $productData) {
                // IMPORTANT: We use the Nginx service name for the other microservice
                $response = Http::get("http://catalog-nginx/api/products/{$productData['product_id']}");

                if ($response->failed()) {
                    // If product is not found or catalog service is down
                    throw ValidationException::withMessages([
                        'product_id' => "Product with ID {$productData['product_id']} could not be found or catalog service is unavailable."
                    ]);
                }

                $product = $response->json();
                $price = $product['price'];
                $totalAmount += $price * $productData['quantity'];

                $orderItemsData[] = [
                    'product_id' => $product['id'],
                    'product_name' => $product['name'],
                    'quantity' => $productData['quantity'],
                    'price' => $price,
                ];
            }

            // 3. Create the order in a database transaction
            $order = DB::transaction(function () use ($validated, $totalAmount, $orderItemsData) {
                $order = Order::create([
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'total_amount' => $totalAmount,
                ]);

                $order->items()->createMany($orderItemsData);

                return $order;
            });

// ... inside the store() method, after the $order variable is created ...

// Dispatch a request to the Email Service
            try {
                $orderDataForEmail = $order->load('items')->toArray();
                Log::info("Attempting to send email for order {$order->id}");
                Http::post('http://email-nginx/api/send-order-confirmation', [
                    'customer_email' => $order->customer_email,
                    'order' => $orderDataForEmail,
                ]);
            } catch (\Exception $e) {
                // If the email service is down, just log the error.
                // The main order creation should not fail because of this.
                Log::error("Could not send confirmation email for order {$order->id}: " . $e->getMessage());
            }

            return response()->json([
                'message' => 'Order created successfully!',
                'order' => $order->load('items')
            ], 201);

        } catch (\Exception $e) {
            // Handle any exceptions, including validation or service connection issues
            return response()->json(['message' => 'Failed to create order.', 'error' => $e->getMessage()], 500);
        }
    }
}