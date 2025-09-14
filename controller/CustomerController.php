<?php
require_once __DIR__ . '/../model/Customer.php';
require_once __DIR__ . '/../model/CustomerAddress.php';
require_once __DIR__ . '/AuthController.php';

/**
 * Controller para gerenciamento de clientes
 */
class CustomerController {
    private $authController;
    
    public function __construct() {
        $this->authController = new AuthController();
    }
    
    /**
     * Lista clientes com filtros e paginação
     */
    public function index() {
        $this->authController->requireLogin();
        
        $customerModel = new Customer();
        
        // Filtros
        $search = $_GET['search'] ?? '';
        $limit = $_GET['limit'] ?? 10;
        $page = $_GET['page'] ?? 1;

        
        // Calcular offset para paginação
        $offset = ($page - 1) * $limit;
        
        // Buscar clientes
        $customers = $customerModel->getAll(1, $limit, $search, $offset);
        
        // Contar total para paginação
        $totalCustomer = $customerModel->count();
        $totalPaginas = ceil($totalCustomer / $limit);
        
        $dados = [
            'customers' => $customers,
            'search' => $search,
            'page' => $page,
            'limit' => $limit,
            'total_customers' => $totalCustomer,
            'total_page' => $totalPaginas
        ];
        
        include __DIR__ . '/../view/customer/index.php';
    }
    
    /**
     * Exibe formulário de novo cliente
     */
    public function create() {
        $this->authController->requireLogin();
        include __DIR__ . '/../view/customer/create.php';
    }
    
    /**
     * Salva novo cliente
     */
    public function store() {

        $this->authController->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/cliente');
            exit;
        }
        
        $dados = [
            'name' => trim($_POST['name'] ?? ''),
            'phone' => preg_replace('/\D/', '', $_POST['phone'] ?? ''),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'date_birth' => $_POST['date_birth'] ?? null,
            'cpf' => $_POST['cpf'] ?? null,
            'rg' => $_POST['rg'] ?? null
        ];
        
        // Validações
        $errors = $this->validateCliente($dados);
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['form_data'] = $dados;
            header('Location: ' . SITE_URL . '/cliente');
            exit;
        }
        
        $customerModel = new Customer();
        $customerAddressModel = new CustomerAddress();

        $addresses = $_POST['addresses'];

        $customerId = $customerModel->create($dados);
        
        if ($customerId) {
            $customerAddressModel->deactivateAll($customerId);
            foreach ($addresses as $endereco) {
                if (!empty(trim($endereco))) {
                    $dataAddress = ['id_customer' => $customerId, 'address' => $endereco];
                    $customerAddressModel->create($dataAddress);
                }
            }
            $_SESSION['success'] = 'Cliente cadastrado com sucesso!';
            header('Location: ' . SITE_URL . '/portal');
        } else {
            $_SESSION['error'] = 'Erro ao cadastrar cliente';
            header('Location: ' . SITE_URL . '/cliente');
        }
        exit;
    }
    

/**
 * Exibir detalhes de um cliente específico
 */
public function show($id) {
    try {
        // Validar ID
        if (!is_numeric($id) || $id <= 0) {
            $_SESSION['error'] = 'Cliente não encontrado';
            header('Location: ' . SITE_URL . '/portal');
            return;
        }

        $customerModel = new Customer();
        $customerAddressModel = new CustomerAddress();
        $customer = $customerModel->getById($id);

        if (!$customer) {
            $_SESSION['error'] = 'Cliente não encontrado';
            header('Location: ' . SITE_URL . '/portal');
            return;
        }

        $addresses = $customerAddressModel->getAddresses($id);

        include __DIR__ . '/../view/customer/show.php';

    } catch (Exception $e) {
        logError('Erro ao exibir cliente: ' . $e->getMessage(), [
            'cliente_id' => $id,
            'user_id' => $_SESSION['user_id'] ?? null
        ]);
        $_SESSION['error'] = 'Erro interno do sistema. Tente novamente';
        header('Location: ' . SITE_URL . '/portal');
    }
}

  
    /**
     * Exibe formulário de edição
     */
    public function edit($id) {
        $this->authController->requireLogin();

        
        $customerModel = new Customer();
        $customerAddressModel = new CustomerAddress();

        $customer = $customerModel->getById($id);
        $addresses = $customerAddressModel->getAddresses($id);


        if (!$customer) {
            $_SESSION['error'] = 'Cliente não encontrado';
            header('Location: ' . SITE_URL . '/cliente');
            exit;
        }

        
        include __DIR__ . '/../view/customer/edit.php';
    }
    
    /**
     * Atualiza dados do cliente
     */
    public function update($id) {
        $this->authController->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/edit_cliente/' . $id);
            exit;
        }
        
        $customerModel = new Customer();
        $customerAddressModel = new CustomerAddress();

        $customer = $customerModel->getById($id);

        
        if (!$customer) {
            $_SESSION['error'] = 'Cliente não encontrado';
            header('Location: ' . SITE_URL . '/portal');
            exit;
        }
        
        $dados = [
            'name' => trim($_POST['name'] ?? ''),
            'phone' => preg_replace('/\D/', '', $_POST['phone'] ?? ''),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'date_birth' => $_POST['date_birth'] ?? null,
            'active' => 1,
            'cpf' => $_POST['cpf'] ?? null,
            'rg' => $_POST['rg'] ?? null
        ];
        
        // Validações
        $errors = $this->validateCliente($dados, $id);
        
        if (!empty($errors)) {
            $_SESSION['error'] = $errors;
            $_SESSION['form_data'] = $dados;
            header('Location: ' . SITE_URL . '/edit_cliente/' . $id);
            exit;
        }
        
        if (!empty($_POST['addresses'])) {
            $customerAddressModel->deactivateAll($id);
            foreach ($_POST['addresses'] as $addr) {
                $addressId = $addr['id'];
                $addressData = [
                    'address' => $addr['address'],
                    'id_customer' => $id,
                    'active' => 1
                ];
                if (!empty($addr['id'])) {
                    $customerAddressModel->update($addressId, $addressData);
                } else {
                    $customerAddressModel->create($addressData);
                }
            }
        }
        if ($customerModel->update($id, $dados)) {
            $_SESSION['success'] = 'Cliente atualizado com sucesso!';
            header('Location: ' . SITE_URL . '/portal');
        } else {
            $_SESSION['error'] = 'Erro ao atualizar cliente';
            header('Location: ' . SITE_URL . '/edit_cliente/' . $id);
        }
        exit;
    }
    
    /**
     * Desativa cliente 
     */
public function delete($id) {
        $this->authController->requireLogin();
        
        $customerModel = new Customer();
        $customer = $customerModel->getById($id);
        
        if (!$customer) {
            $_SESSION['error'] = 'Cliente não encontrado';
            header('Location: ' . SITE_URL . '/portal');
            exit;
        }
        
        
        if ($customerModel->deactivate($id)) {
            $_SESSION['success'] = 'Cliente desativado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao desativar cliente';
        }
        
        header('Location: ' . SITE_URL . '/portal');
        exit;
    }
    /**
     * Validação dos dados do cliente
     */
    private function validateCliente($dados, $customerId = null) {
        $errors = [];
        // CPF obrigatório
        if (empty($dados['cpf'])) {
            $errors[] = 'CPF é obrigatório';
        } elseif (!($this->validateCPF($dados['cpf']))) {
            $errors[] = 'CPF Inválido';
        }
        
        // RG obrigatório
        if (empty($dados['rg'])) {
            $errors[] = 'RG é obrigatório';
        } 
        
        // Nome obrigatório
        if (empty($dados['name'])) {
            $errors[] = 'Nome é obrigatório';
        } elseif (strlen($dados['name']) < 2) {
            $errors[] = 'Nome deve ter pelo menos 2 caracteres';
        }
        
        // Telefone obrigatório e único
        if (empty($dados['phone'])) {
            $errors[] = 'Telefone é obrigatório';
        } elseif (strlen($dados['phone']) < 10) {
            $errors[] = 'Telefone deve ter pelo menos 10 dígitos';
        } else {
            $customerModel = new Customer();
            if ($customerModel->phoneExists($dados['phone'], $customerId)) {
                $errors[] = 'Este telefone já está cadastrado para outro cliente';
            }
        }
        
        // Email (opcional, mas se informado deve ser válido e único)
        if (!empty($dados['email'])) {
            if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email inválido';
            } else {
                $customerModel = new Customer();
                if ($customerModel->emailExists($dados['email'], $customerId)) {
                    $errors[] = 'Este email já está cadastrado para outro cliente';
                }
            }
        }
        
        // Data de nascimento (opcional, mas se informada deve ser válida)
        if (!empty($dados['date_birth'])) {
            $dataNasc = DateTime::createFromFormat('Y-m-d', $dados['date_birth']);
            if (!$dataNasc || $dataNasc->format('Y-m-d') !== $dados['date_birth']) {
                $errors[] = 'Data de nascimento inválida';
            } elseif ($dataNasc > new DateTime()) {
                $errors[] = 'Data de nascimento não pode ser futura';
            } elseif ($dataNasc < new DateTime('1900-01-01')) {
                $errors[] = 'Data de nascimento muito antiga';
            }
        }
        
        return $errors;
    }

    public function validateCPF ($cpf){

        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $soma = 0;
            for ($i = 0; $i < $t; $i++) {
                $soma += $cpf[$i] * (($t + 1) - $i);
            }
            $digito = ($soma * 10) % 11;
            if ($digito == 10) {
                $digito = 0;
            }
            if ($digito != $cpf[$t]) {
                return false;
            }
        }

        return true;
    }

}
?>
