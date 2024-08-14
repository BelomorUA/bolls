<?php

namespace App\Services;

use App\Services\Contracts\DeliveryServiceInterface;
use Illuminate\Support\Facades\Http;

class UkrPoshtaService implements DeliveryServiceInterface
{
    private $apiUrl;
    private $senderAddress;

    public function __construct()
    {
        $this->apiUrl = 'https://ukrposhta.test/api/delivery';
        $this->senderAddress = config('delivery.sender_address');
    }

    public function sendDeliveryData(array $packageData, array $recipientData): array
    {
        $response = Http::post($this->apiUrl, [
            'customer_name' => $recipientData['name'],
            'phone_number' => $recipientData['phone'],
            'email' => $recipientData['email'],
            'address_sender' => $this->senderAddress,
            'address_delivery' => $recipientData['address'],
        ]);

        return $response->json();
    }
}