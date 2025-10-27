<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    public function sendOrderConfirmation(Request $request)
    {
        // Validate that we received order data
        $orderData = $request->validate([
            'customer_email' => 'required|email',
            'order' => 'required|array'
        ]);

        try {
            // Use Laravel's Mail facade to send the Mailable
            Mail::to($orderData['customer_email'])
                ->send(new OrderConfirmationMail($orderData['order']));
            return response()->json(['message' => 'Confirmation email sent successfully!']);

        } catch (\Exception $e) {
            // Log any errors
            Log::error('Email sending failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to send email.'], 500);
        }
    }
}