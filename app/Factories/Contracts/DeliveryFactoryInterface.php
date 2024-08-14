<?php

namespace App\Factories\Contracts;

use App\Services\Contracts\DeliveryServiceInterface;

interface DeliveryFactoryInterface
{
    public function createDeliveryService(string $serviceName): DeliveryServiceInterface;
}
