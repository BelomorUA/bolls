<?php

namespace App\Services\Contracts;

interface DeliveryServiceInterface
{
    public function sendDeliveryData(array $packageData, array $recipientData): array;
}
