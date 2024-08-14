<?php

namespace App\Factories;

use App\Factories\Contracts\DeliveryFactoryInterface;
use App\Services\Contracts\DeliveryServiceInterface;
use App\Services\NovaPoshtaService;
use App\Services\UkrPoshtaService;
use InvalidArgumentException;

class DeliveryFactory implements DeliveryFactoryInterface
{
    public function createDeliveryService(string $serviceName): DeliveryServiceInterface
    {
        switch ($serviceName) {
            case 'nova_poshta':
                return new NovaPoshtaService();
            case 'ukr_poshta':
                return new UkrPoshtaService();
            default:
                throw new InvalidArgumentException("Unsupported delivery service: {$serviceName}");
        }
    }
}
