<?php
// Incluir controllers
require_once __DIR__ . '/controller/AuthController.php';
require_once __DIR__ . '/controller/UserController.php';
require_once __DIR__ . '/controller/CustomerController.php';

/**
 * Router simples
 */
class Router {
    private $authController;
    private $routes = [];

    public function __construct() {
        $this->authController = new AuthController();
        $this->setupRoutes();
    }

    private function setupRoutes(){
        // Rotas de autenticação
        $this->addRoute('GET', '/', [$this->authController, 'login']);
        $this->addRoute('GET', '/login', [$this->authController, 'login']);
        $this->addRoute('POST', '/login', [$this->authController, 'processLogin']);
        $this->addRoute('GET', '/logout', [$this->authController, 'logout']);
        
        //Rotas de usuário
        $userController = new UserController();
        $this->addRoute('GET', '/registro', [$userController, 'create']);
        $this->addRoute('POST', '/registro', [$userController, 'store']);
        $this->addRoute('GET', '/forgotten_password', [$userController, 'changePassword']);
        $this->addRoute('POST', '/forgotten_password', [$userController, 'checkUser']);
        $this->addRoute('GET', '/edit_password/{id}', [$userController, 'editPassword']);
        $this->addRoute('POST', '/edit_password', [$userController, 'updatePassword']);

        //Rotas de cliente
        $customerController = new CustomerController();
        $this->addRoute('GET', '/portal', [$customerController, 'index']);
        $this->addRoute('GET', '/cliente', [$customerController, 'create']);
        $this->addRoute('POST', '/cliente', [$customerController, 'store']);
        $this->addRoute('GET', '/edit_cliente/{id}', [$customerController, 'edit']);
        $this->addRoute('POST', '/update_cliente/{id}', [$customerController, 'update']);
        $this->addRoute('DELETE', '/cliente/delete/{id}', [$customerController, 'delete']);
        $this->addRoute('POST', '/cliente/delete/{id}', [$customerController, 'delete']);
        $this->addRoute('GET', '/detalhes_cliente/{id}', [$customerController, 'show']);

    }

    private function addRoute($method, $path, $callback) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        
        // Remover query string
        $requestUri = strtok($requestUri, '?');
        
        // Remover base path
        $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        if ($basePath !== '/') {
            $requestUri = str_replace($basePath, '', $requestUri);
        }
        
        // Garantir que sempre comece com /
        if (empty($requestUri) || $requestUri[0] !== '/') {
            $requestUri = '/' . $requestUri;
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }
            
            $pattern = $this->convertToRegex($route['path']);
            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Remove full match
                $params = [];
                    
                if (preg_match_all('/\{(\w+)\}/', $route['path'], $paramNames)) {
                    foreach ($paramNames[1] as $index => $name) {
                        if (isset($matches[$index])) {
                            $params[$name] = $matches[$index];
                        }
                    }
                }
                 // Chamar callback
                return $this->callCallback($route['callback'], $params);

            }

           
        }
        
    }
    private function convertToRegex($path) {
        $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
    
    private function callCallback($callback, $params = []) {
        if (is_array($callback)) {
            $controller = $callback[0];
            $method = $callback[1];
            
            if (empty($params)) {
                return $controller->$method();
            } else {
                return call_user_func_array([$controller, $method], array_values($params));
            }
        }
        
        return call_user_func($callback, $params);
    }
}

// Inicializar e executar router
try {
    $router = new Router();
    $router->dispatch();
} catch (Exception $e) {
    echo ("Erro no sistema: " . $e->getMessage());exit();
    error_log("Erro no sistema: " . $e->getMessage());
    http_response_code(500);
}

?>