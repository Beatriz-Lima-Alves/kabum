<?php
/**
 * Processar cadastro de cliente
 */

// Verificar se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . SITE_URL . '/clientes/create');
    exit;
}

// Inicializar arrays de erro
$errors = [];
$success = false;

// Validar dados
$nome = trim($_POST['nome'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$email = trim($_POST['email'] ?? '');
$data_nascimento = trim($_POST['data_nascimento'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$observacoes = trim($_POST['observacoes'] ?? '');

// Validações
if (empty($nome)) {
    $errors[] = 'Nome é obrigatório';
} elseif (strlen($nome) < 2) {
    $errors[] = 'Nome deve ter pelo menos 2 caracteres';
}

if (empty($telefone)) {
    $errors[] = 'Telefone é obrigatório';
} else {
    // Limpar telefone (apenas números)
    $telefone_clean = preg_replace('/\D/', '', $telefone);
    if (strlen($telefone_clean) < 10) {
        $errors[] = 'Telefone deve ter pelo menos 10 dígitos';
    }
}

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'E-mail inválido';
}

if (!empty($data_nascimento)) {
    $data_nascimento_timestamp = strtotime($data_nascimento);
    if (!$data_nascimento_timestamp) {
        $errors[] = 'Data de nascimento inválida';
    } elseif ($data_nascimento_timestamp > time()) {
        $errors[] = 'Data de nascimento não pode ser futura';
    }
}

// Se houver erros, retornar para o formulário
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header('Location: ' . SITE_URL . '/clientes/create');
    exit;
}

try {
    // Conectar ao banco
    $pdo = getPDO();
    
    // Verificar se telefone já existe
    $stmt = $pdo->prepare("SELECT id FROM clientes WHERE telefone = ?");
    $stmt->execute([$telefone_clean]);
    if ($stmt->fetch()) {
        $errors[] = 'Este telefone já está cadastrado';
    }
    
    // Verificar se email já existe (se preenchido)
    if (!empty($email)) {
        $stmt = $pdo->prepare("SELECT id FROM clientes WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Este e-mail já está cadastrado';
        }
    }
    
    // Se houver erros de duplicação, retornar
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header('Location: ' . SITE_URL . '/clientes/create');
        exit;
    }
    
    // Inserir cliente
    $sql = "INSERT INTO clientes (nome, telefone, email, data_nascimento, endereco, observacoes, data_cadastro) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nome,
        $telefone_clean,
        $email ?: null,
        $data_nascimento ?: null,
        $endereco ?: null,
        $observacoes ?: null
    ]);
    
    $cliente_id = $pdo->lastInsertId();
    
    // Limpar dados do formulário
    unset($_SESSION['form_data']);
    unset($_SESSION['errors']);
    
    // Mensagem de sucesso
    $_SESSION['success'] = 'Cliente cadastrado com sucesso!';
    
    // Redirecionar para a lista de clientes
    header('Location: ' . SITE_URL . '/clientes');
    exit;
    
} catch (PDOException $e) {
    // Log do erro (em produção, não mostrar detalhes)
    error_log('Erro ao cadastrar cliente: ' . $e->getMessage());
    
    $errors[] = 'Erro interno. Tente novamente.';
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    
    header('Location: ' . SITE_URL . '/clientes/create');
    exit;
}
?>

