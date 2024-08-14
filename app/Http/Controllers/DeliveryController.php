<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\Contracts\DeliveryFactoryInterface;

class DeliveryController extends Controller
{
    protected $deliveryFactory;

    public function __construct(DeliveryFactoryInterface $deliveryFactory)
    {
        $this->deliveryFactory = $deliveryFactory;
    }

    public function sendDelivery(Request $request)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'length' => 'required|numeric',
            'weight' => 'required|numeric',
            'recipient.name' => 'required|string',
            'recipient.phone' => 'required|string',
            'recipient.email' => 'required|email',
            'recipient.address' => 'required|string',
            'delivery_service' => 'required|string|in:nova_poshta,ukr_poshta'
        ]);

        // Package data
        $packageData = [
            'width' => $validatedData['width'],
            'height' => $validatedData['height'],
            'length' => $validatedData['length'],
            'weight' => $validatedData['weight']
        ];

        // Recipient data
        $recipientData = [
            'name' => $validatedData['recipient']['name'],
            'phone' => $validatedData['recipient']['phone'],
            'email' => $validatedData['recipient']['email'],
            'address' => $validatedData['recipient']['address']
        ];

        $deliveryServiceName = $validatedData['delivery_service'];

        $deliveryService = $this->deliveryFactory->createDeliveryService($deliveryServiceName);

        $response = $deliveryService->sendDeliveryData($packageData, $recipientData);

        // Optionally log the delivery status to the database
        /*DeliveryStatus::create([
            'service' => get_class($deliveryService),
            'status' => isset($response['success']) && $response['success'] ? 'Success' : 'Failed',
            'response' => json_encode($response),
        ]);*/

        return response()->json($response);
    }
}
