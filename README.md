# EasyRoute

EasyRoute is a lightweight PHP router for handling HTTP requests and routing them to the appropriate controller methods.

## Features

- Simple and easy-to-use routing mechanism
- Supports dynamic routes with parameters
- Compatible with PHP 8.1.0 or later

## Installation

You can install EasyRoute via Composer. Run the following command in your terminal:

```bash
composer require vilnis/easy-route
```

## Usage

### Basic Usage

```php
<?php
use EasyRoute\Router;
use EasyRoute\Exceptions\RouteNotFoundException;
use EasyRoute\Exceptions\MethodNotAllowedException;
use MyCustomNamespace\MyCustomController; // Replace with your custom namespace

// Include Composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Create a new router instance
$router = new EasyRoute\Router();

// Define routes using addRoute() with HTTP methods
$router->addRoute('GET', '/users', [MyCustomController::class, 'index']);
$router->addRoute('POST', '/users', [MyCustomController::class, 'create']);
$router->addRoute('PUT', '/users/{id/d}/{text/t}', [MyCustomController::class, 'update']);
$router->addRoute('PATCH', '/users/{id/d}', [MyCustomController::class, 'modify']);
$router->addRoute('DELETE', '/users/{id/d}/{text/t}', [MyCustomController::class, 'delete']);

try {
    // Get request method and URI
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Match the route
    [$routeHandler, $params] = $router->match($method, $uri);

    if ($routeHandler !== null) {
        // Execute the corresponding method based on the matched route
        [$controller, $method] = $routeHandler;
        $controllerInstance = new $controller();

        // Handle the matched route with extracted parameters dynamically as an array
        $response = call_user_func_array([$controllerInstance, $method], [$params]);

        // Output the response or handle it further
        echo $response;
    } else {
        throw new RouteNotFoundException('Route not found.');
    }
} catch (RouteNotFoundException $e) {
    // Handle RouteNotFoundException
    http_response_code(404);
    echo '404 Not Found: ' . $e->getMessage();
} catch (MethodNotAllowedException $e) {
    // Handle MethodNotAllowedException
    http_response_code(405);
    echo '405 Method Not Allowed: ' . $e->getMessage();
}
```
### Routes with Type Markers (/d and /t):

- **`/users/{id/d}/{text/t}`**: Expects two parameters:
    - `{id/d}`: Requires a digit (numeric value) for the `id`.
    - `{text/t}`: Expects alphabetic text.

- **`/users/{id/d}`**: Expects a single parameter:
    - `{id/d}`: Requires a digit (numeric value) for the `id`.


### Routes without Type Markers:

- **`/products/{category}`**: This route includes `{category}` parameter without specific type markers `{category/t}` or `{category/d}`.
    - `{category}`: Accepts various types of parameters (digits, alphabetic characters, etc.).
