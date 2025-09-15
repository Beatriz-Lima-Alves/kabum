<?php
require_once __DIR__ . '/../model/User.php';

class UserController {
    private $authController;
    
    public function __construct() {

    }
    
    /**
     * Lista todos os usuários (Para funcionalidade futura)
     */
    public function index() {
                
        $userModel = new User();
        $users = $userModel->getAll();
        
        include __DIR__ . '/../view/user/index.php';
    }
    
    /**
     * Exibe formulário de novo usuário
     */
    public function create() {
        include __DIR__ . '/../view/user/create.php';
    }
    
    /**
     * Salva novo usuário
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/registro');
            exit;
        }
        
        $dados = [
            'name' => trim($_POST['nome'] ?? ''),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'password' => $_POST['senha'] ?? '',
            'confirm_password' => $_POST['confirmar_senha'] ?? ''
        ];
        
        // Validações
        $errors = $this->validateUsuario($dados);
        
        if (!empty($errors)) {
            $_SESSION['error'] = $errors;
            $_SESSION['form_data'] = $dados;
            header('Location: ' . SITE_URL . '/login');
            exit;
        }
        
        // Preparar dados para salvar
        $dados['password'] = password_hash($dados['password'], PASSWORD_DEFAULT);
       
        $userModel = new User();
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
     * Exibe formulário de edição (Para funcionalidade futura)
     */
    public function edit($id) {
                
               
        $userModel = new User();
        $user = $userModel->getById($id);
        
        if (!$user) {

            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: ' . SITE_URL . '/usuarios');
            exit;
        }
        
        include __DIR__ . '/../view/user/edit.php';
    }
    
    /**
     * Atualiza dados do usuário  (Para funcionalidade futura)
     */
    public function update($id) {
                
       if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/usuarios/edit/' . $id);
            exit;
        }
        
        $userModel = new User();
        $user = $userModel->getById($id);
        
        if (!$user) {
            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: ' . SITE_URL . '/usuarios');
            exit;
        }
        
        $dados = [
            'name' => trim($_POST['nome'] ?? ''),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL)
            
        ];
        
        // Validações
        $errors = $this->validateUsuarioUpdate($dados, $id);
        
        if (!empty($errors)) {
            $_SESSION['error'] = $errors;
            $_SESSION['form_data'] = $dados;
            header('Location: ' . SITE_URL . '/usuarios/edit/' . $id);
            exit;
        }
        
        if ($userModel->update($id, $dados)) {
            $_SESSION['success'] = 'Usuário atualizado com sucesso!';
            
            
            header('Location: ' . SITE_URL . '/usuarios/show/' . $id);
        } else {
            $_SESSION['error'] = 'Erro ao atualizar usuário';
            header('Location: ' . SITE_URL . '/usuarios/edit/' . $id);
        }
        exit;
    }
    
    /**
     * Exibe formulário de verificação de email
     */
    public function changePassword() {

        include __DIR__ . '/../view/user/change_password.php';
        
    }
    
    
    /**
     * Exibe formulário de alteração de senha
     */
    public function editPassword($id) {
        include __DIR__ . '/../view/user/edit_password.php';
    }
    
    /**
     * Processa alteração de senha
     */
    public function checkUser() {
                
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/forgotten_password');
            exit;
        }
        $userModel = new User();
        $user = $userModel->getByEmail($_POST['email']);
        
        if (!$user) {
            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: ' . SITE_URL . '/login');
            exit;
        }
        else{
            header('Location: ' . SITE_URL . '/edit_password/'.$user['id']);
            exit;
            
        }
    }
    
    public function updatePassword(){
        if (empty($_POST['id'])) {
            $_SESSION['error'] = 'Usuário inválido';
            header('Location: ' . SITE_URL . '/login');
            exit;
        }

        $userModel = new User();
        $user = $userModel->getById($_POST['id']);

        // 2. Verifica se token é válido e não expirou
        if (!$user) {
            $_SESSION['error'] = 'Usuário inválido ou desativado';
            header('Location: ' . SITE_URL . '/login');
            exit;
        }

        // 3. Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/forgotten_password');
            exit;
        }

        $newPassword = $_POST['senha'] ?? '';
        $confirmPassword = $_POST['confirma_senha'] ?? '';
        $errors = [];

        // 4. Valida nova senha
        if (empty($newPassword) || $newPassword == "") {
            $errors[] = 'Nova senha é obrigatória';
        } elseif (strlen($newPassword) < 6) {
            $errors[] = 'Nova senha deve ter pelo menos 6 caracteres';
        }

        if ($newPassword !== $confirmPassword) {
            $errors[] = 'Confirmação de senha não confere';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = $errors;
            header('Location: ' . SITE_URL . '/edit_password/'.$_POST['id']);
            exit;
        }

        if ($userModel->updatePassword($user['id'], $newPassword)) {

            $_SESSION['success'] = 'Senha redefinida com sucesso!';
            header('Location: ' . SITE_URL . '/login');
        } else {
            $_SESSION['error'] = 'Erro ao redefinir senha';
            header('Location: ' . SITE_URL . '/edit_password/'.$_POST['id']);
        }

    }


    /**
     * Desativa usuário (Para funcionalidade futura)
     */
    public function delete($id) {
                
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = 'Você não pode desativar sua própria conta';
            header('Location: ' . SITE_URL . '/usuarios');
            exit;
        }
        
        $userModel = new User();
        $user = $userModel->getById($id);

        
        if (!$user) {
            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: ' . SITE_URL . '/usuarios');
            exit;
        }
        
        if ($userModel->delete($id)) {
            $_SESSION['success'] = 'Usuário desativado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao desativar usuário';
        }
        
        header('Location: ' . SITE_URL . '/usuarios');
        exit;
    }

     /**
     * Validação para novo usuário
     */
    private function validateUsuario($dados) {
        $errors = [];
        
        // Nome obrigatório
        if (empty($dados['name'])) {
            $errors[] = 'Nome é obrigatório';
        } elseif (strlen($dados['name']) < 2) {
            $errors[] = 'Nome deve ter pelo menos 2 caracteres';
        }
        
        // Email obrigatório e único
        if (empty($dados['email'])) {
            $errors[] = 'Email é obrigatório';
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido';
        } else {
            $userModel = new User();
            if ($userModel->getByEmail($dados['email'])) {
                $errors[] = 'Este email já está cadastrado';
            }
        }
        
        // Senha obrigatória
        if (empty($dados['password'])) {
            $errors[] = 'Senha é obrigatória';
        } elseif (strlen($dados['password']) < 6) {
            $errors[] = 'Senha deve ter pelo menos 6 caracteres';
        }
        
        if ($dados['password'] !== $dados['confirm_password']) {
            $errors[] = 'Confirmação de senha não confere';
        }
        
        return $errors;
    }
    
    /**
     * Validação para atualização de usuário
     */
    private function validateUsuarioUpdate($dados, $userId) {
        $errors = [];
        
        // Nome obrigatório
        if (empty($dados['name'])) {
            $errors[] = 'Nome é obrigatório';
        } elseif (strlen($dados['name']) < 2) {
            $errors[] = 'Nome deve ter pelo menos 2 caracteres';
        }
        
        // Email obrigatório e único
        if (empty($dados['email'])) {
            $errors[] = 'Email é obrigatório';
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido';
        } else {
            $userModel = new User();
            $userExistente = $userModel->getByEmail($dados['email']);
            if ($userExistente && $userExistente['id'] != $userId) {
                $errors[] = 'Este email já está cadastrado para outro usuário';
            }
        }
        
        return $errors;
    }
}
?>
