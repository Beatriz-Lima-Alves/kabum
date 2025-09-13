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
            header('Location: ' . SITE_URL . '/clientes/create');
            exit;
        }
        
        $dados = [
            'name' => trim($_POST['name'] ?? ''),
            'phone' => preg_replace('/\D/', '', $_POST['phone'] ?? ''),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'date_birth' => $_POST['date_birth'] ?? null
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
            flash('error', 'Cliente não encontrado.');
            redirect('clientes');
            return;
        }

        // Buscar cliente usando sua classe DB
        $customer = DB::selectOne("SELECT * FROM clientes WHERE id = ?", [$id]);

        if (!$customer) {
            flash('error', 'Cliente não encontrado.');
            redirect('clientes');
            return;
        }

        // Buscar agendamentos do cliente com informações dos serviços e barbeiros
        $agendamentos = DB::select("
            SELECT 
                a.*,
                s.nome as servico_nome,
                s.valor as servico_valor,
                u.nome as barbeiro_nome
            FROM agendamentos a
            LEFT JOIN servicos s ON a.servico_id = s.id
            LEFT JOIN usuarios u ON a.barbeiro_id = u.id
            WHERE a.cliente_id = ?
            ORDER BY a.data_agendamento DESC
        ", [$id]);

         // Verificar se a consulta retornou dados válidos
        if ($agendamentos === false) {
            // logError('Erro na consulta de agendamentos', [
            //     'cliente_id' => $id,
            //     'user_id' => $_SESSION['user_id'] ?? null
            // ]);
            $agendamentos = []; // Definir como array vazio em caso de erro
        }

        // Garantir que $agendamentos seja sempre um array
        if (!is_array($agendamentos)) {
            $agendamentos = [];
        }

        // Processar agendamentos para garantir compatibilidade
        foreach ($agendamentos as &$agendamento) {
            // Se não tiver valor no agendamento, usar valor do serviço
            if (empty($agendamento['valor']) && !empty($agendamento['servico_valor'])) {
                $agendamento['valor'] = $agendamento['servico_valor'];
            }
            
            // Garantir que campos existam
            $agendamento['servico_nome'] = $agendamento['servico_nome'] ?? 'Serviço não informado';
            $agendamento['barbeiro_nome'] = $agendamento['barbeiro_nome'] ?? 'Barbeiro não informado';
        }
        // Estatísticas do cliente
        $stats = [
            'total_agendamentos' => count($agendamentos),
            'agendamentos_realizados' => count(array_filter($agendamentos, function($a) {
                return $a['status'] === 'realizado';
            })),
            'valor_total_gasto' => array_sum(array_map(function($a) {
                return ($a['status'] === 'realizado' && !empty($a['valor'])) ? $a['valor'] : 0;
            }, $agendamentos)),
            'proximo_agendamento' => $this->getProximoAgendamento($agendamentos)
        ];

        // Passar dados para a view
        $data = [
            'cliente' => $customer,
            'agendamentos' => $agendamentos,
            'stats' => $stats
        ];

        // Incluir a view
        extract($data);
        include __DIR__ . '/../view/customer/show.php';

    } catch (Exception $e) {
        logError('Erro ao exibir cliente: ' . $e->getMessage(), [
            'cliente_id' => $id,
            'user_id' => $_SESSION['user_id'] ?? null
        ]);
        
        flash('error', 'Erro interno do sistema. Tente novamente.');
        redirect('clientes');
    }
}

  
    /**
     * Exibe formulário de edição
     */
    public function edit($id) {
        $this->authController->requireLogin();

        
        $customerModel = new Customer();
        $customer = $customerModel->getById($id);

        if (!$customer) {
            $_SESSION['error'] = 'Cliente não encontrado';
            header('Location: ' . SITE_URL . '/clientes');
            exit;
        }

        // var_dump($customer_edit);exit();
        
        include __DIR__ . '/../view/customer/edit.php';
    }
    
    /**
     * Atualiza dados do cliente
     */
    public function update($id) {
        $this->authController->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/clientes/edit/' . $id);
            exit;
        }
        
        $customerModel = new Customer();
        $customer = $customerModel->getById($id);
        
        if (!$customer) {
            $_SESSION['error'] = 'Cliente não encontrado';
            header('Location: ' . SITE_URL . '/clientes');
            exit;
        }
        
        $dados = [
            'nome' => trim($_POST['name'] ?? ''),
            'telefone' => preg_replace('/\D/', '', $_POST['phone'] ?? ''),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'data_nascimento' => $_POST['date_birth'] ?? null,
            'endereco' => trim($_POST['endereco'] ?? ''),
            'observacoes' => trim($_POST['observacoes'] ?? ''),
            'ativo' => 1
        ];
        
        // Validações
        $errors = $this->validateCliente($dados, $id);
        
        if (!empty($errors)) {
            $_SESSION['error'] = $errors;
            $_SESSION['form_data'] = $dados;
            header('Location: ' . SITE_URL . '/clientes/edit/' . $id);
            exit;
        }
        
        if ($customerModel->update($id, $dados)) {
            $_SESSION['success'] = 'Cliente atualizado com sucesso!';
            header('Location: ' . SITE_URL . '/clientes/show/' . $id);
        } else {
            $_SESSION['error'] = 'Erro ao atualizar cliente';
            header('Location: ' . SITE_URL . '/clientes/edit/' . $id);
        }
        exit;
    }
    
    /**
     * Desativa cliente 
     */
public function delete($id) {
        $this->authController->requireAdmin();
        
        $customerModel = new Customer();
        $customer = $customerModel->getById($id);
        
        if (!$customer) {
            $_SESSION['error'] = 'Cliente não encontrado';
            header('Location: ' . SITE_URL . '/clientes');
            exit;
        }
        
        // Verificar se tem agendamentos futuros
        $agendamentoModel = new Agendamento();
        $agendamentosFuturos = $agendamentoModel->getAll([
            'cliente_id' => $id,
            'data_inicio' => date('Y-m-d'),
            'status' => 'agendado'
        ]);
        
        if (!empty($agendamentosFuturos)) {
            $_SESSION['error'] = 'Não é possível desativar cliente com agendamentos futuros';
            header('Location: ' . SITE_URL . '/clientes/show/' . $id);
            exit;
        }
        
        if ($customerModel->deactivate($id)) {
            $_SESSION['success'] = 'Cliente desativado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao desativar cliente';
        }
        
        header('Location: ' . SITE_URL . '/clientes');
        exit;
    }
    /**
     * Validação dos dados do cliente
     */
    private function validateCliente($dados, $customerId = null) {
        $errors = [];
        
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
            if ($customerModel->telefoneExists($dados['phone'], $customerId)) {
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

}
?>
