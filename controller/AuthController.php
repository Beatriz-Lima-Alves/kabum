<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/User.php';

/**
 * Controller responsável pela autenticação de usuários
 */
class AuthController {
    
    /**
     * Exibe a página de login
     */
    public function login() {
        // Verifica se está logado
        if ($this->isLoggedIn()) {
            header('Location: ' . SITE_URL . '/portal');
            exit;
        }
        
        include __DIR__ . '/../view/auth/login.php';
    }
    
    /**
     * Processa o login do usuário
     */
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/login');
            exit;
        }
        
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['password'] ?? '';
        
        if (empty($email) || empty($senha)) {
            $_SESSION['error'] = 'Email e senha são obrigatórios';
            header('Location: ' . SITE_URL . '/login');
            exit;
        }
        
        $userModel = new User();
        $user = $userModel->getByEmail($email);
        
        if ($user && password_verify($senha, $user['password'])) {
            if (!$user['active']) {
                $_SESSION['error'] = 'Usuário inativo. Contate o administrador.';
                header('Location: ' . SITE_URL . '/login');
                exit;
            }
            
            // Login bem-sucedido
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['login_time'] = time();
            
            // Regenerar ID da sessão para segurança
            session_regenerate_id(true);
            
            $_SESSION['success'] = 'Login realizado com sucesso!';
            header('Location: ' . SITE_URL . '/portal');
            exit;
        } else {
            $_SESSION['error'] = 'Email ou senha incorretos';
            header('Location: ' . SITE_URL . '/login');
            exit;
        }
    }
    
    /**
     * Realiza logout do usuário
     */
    public function logout() {
        session_destroy();
        header('Location: ' . SITE_URL . '/login');
        exit;
    }
    
    /**
     * Processa cadastro de novo usuário
     */
    public function processRegister() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/registro');
            exit;
        }
        
        $dados = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? ''
            ];
        
        // Validações
        $errors = [];
        
        if (empty($dados['name'])) {
            $errors[] = 'Nome é obrigatório';
        }
        
        if (empty($dados['email']) || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email válido é obrigatório';
        }
        
        if (strlen($dados['password']) < 6) {
            $errors[] = 'Senha deve ter pelo menos 6 caracteres';
        }
        
        if ($dados['password'] !== $dados['confirm_password']) {
            $errors[] = 'Senhas não conferem';
        }
        
        // Verificar se email já existe
        $userModel = new User();
        if ($userModel->getByEmail($dados['email'])) {
            $errors[] = 'Email já cadastrado no sistema';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $dados;
            header('Location: ' . SITE_URL . '/registro');
            exit;
        }
        
        // Criar usuário
        $dados['password'] = password_hash($dados['password'], PASSWORD_DEFAULT);
        
        $userId = $userModel->create($dados);
        
        if ($userId) {
            $_SESSION['success'] = 'Usuário cadastrado com sucesso!';
            header('Location: ' . SITE_URL . '/login');
        } else {
            $_SESSION['error'] = 'Erro ao cadastrar usuário';
            header('Location: ' . SITE_URL . '/registro');
        }
        exit;
    }
    
    /**
     * Verifica se usuário está logado
     */
    public function isLoggedIn() {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        // Verificar timeout da sessão
        if (isset($_SESSION['login_time']) && 
            (time() - $_SESSION['login_time']) > SESSION_TIMEOUT) {
            session_destroy();
            return false;
        }
        
        return true;
    }
    
    /**
     * Requer que usuário esteja logado
     */
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            $_SESSION['error'] = 'Você precisa estar logado para acessar esta página';
            header('Location: ' . SITE_URL . '/login');
            exit;
        }
    }
    
    /**
     * Obtém dados do usuário logado
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_nome'],
            'email' => $_SESSION['user_email'],
        ];
    }
}
?>
