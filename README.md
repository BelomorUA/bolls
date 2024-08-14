# Delivery Service Integration

This Laravel project provides a flexible and scalable architecture for integrating with multiple courier services. Currently, the project supports two services: NovaPoshta and UkrPoshta. The design uses the Abstract Factory pattern, which makes it easy to add new delivery services in the future.

## Features

- **Abstract Factory Pattern:** Easily switch or extend the courier service.
- **API Integration:** Supports sending delivery data to various courier services.
- **Flexible Configuration:** Select the default courier service via configuration.
- **Optional Database Logging:** (Commented out) Log delivery status to the database.

## Installation

1. **Clone the repository:**
    ```bash
    git clone https://github.com/BelomorUA/bolls.git .
    ```

2. **Install dependencies:**
    ```bash
    composer install
    ```

3. **Set up environment variables:**
   Copy the `.env.example` to `.env` and configure your environment variables, including database settings.
    ```bash
    copy .env.example .env
    php artisan key:generate
    ```

4. **Configure delivery services:**
   Modify `config/delivery.php` to set your default delivery service and sender address.

    ```php
    return [
        'sender_address' => 'Your sender address here',
        'default_service' => 'nova_poshta', // Change to 'ukr_poshta' for UkrPoshta
    ];
    ```

5. **Run laravel server:**
```bash
php artisan serve    
```

6. **Open project in your browser:**
   http://127.0.0.1:8000/

## Usage

You can send delivery data by making a `POST` request to the `/delivery` endpoint. The request should include the following data:

```json
{
    "width": 30,
    "height": 20,
    "length": 10,
    "weight": 5,
    "recipient": {
        "name": "John Doe",
        "phone": "+380950000000",
        "email": "john.doe@example.com",
        "address": "Kyiv, Ukraine"
    }
}
```

## Adding a New Delivery Service

To add a new delivery service, follow these steps:

1. **Create a Service Class**
Create a new service class that implements the DeliveryServiceInterface. This class should contain the logic for interacting with the new courier service's API.
```php
// app/Services/YourNewService.php

namespace App\Services;

use App\Services\Contracts\DeliveryServiceInterface;
use Illuminate\Support\Facades\Http;

class YourNewService implements DeliveryServiceInterface
{
    private $apiUrl;
    private $senderAddress;

    public function __construct()
    {
        $this->apiUrl = 'https://yournewservice.test/api/delivery'; // Update with the actual API URL
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
```

2. **Update the Factory**
   Modify the DeliveryFactory class to include the logic for creating an instance of your new service. Add a new case to the switch statement that matches the service name passed in the request.
```php
// app/Factories/DeliveryFactory.php

namespace App\Factories;

use App\Factories\Contracts\DeliveryFactoryInterface;
use App\Services\Contracts\DeliveryServiceInterface;
use App\Services\NovaPoshtaService;
use App\Services\UkrPoshtaService;
use App\Services\YourNewService; // Import your new service
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
            case 'your_new_service': // Add this case
                return new YourNewService(); // Return an instance of your new service
            default:
                throw new InvalidArgumentException("Unsupported delivery service: {$serviceName}");
        }
    }
}
```

## Database Logging
If you want to log the delivery status to the database:

Uncomment the related code in DeliveryController:
```php
// DeliveryStatus::create([
//     'service' => get_class($deliveryService),
//     'status' => isset($response['success']) && $response['success'] ? 'Success' : 'Failed',
//     'response' => json_encode($response),
// ]);
```
